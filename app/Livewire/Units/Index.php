<?php

namespace App\Livewire\Units;

use App\Models\Booking;
use App\Models\Project;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $project_id;
    public $showModal = false;

    // Unit Form State
    public $selected_project_id = '';
    public $code = '';
    public $type = 'kavling';
    public $category = 'kavling';
    public $building_area = 0;
    public $floors_count = 1;
    public $specifications = '';
    public $land_width = 10.00;
    public $land_length = 10.00;
    public $land_area = 100.00;
    public $hpp = null;
    public $editingUnitId = null;

    // Booking Modal State
    public $showBookingModal = false;
    public $bookingUnitId = null;
    public $bookingUnitCode = '';
    public $buyer_name = '';
    public $buyer_phone = '';
    public $booking_amount = 5000000;
    public $dp_amount = 25000000;
    public $booking_notes = '';

    // Computed preview properties for auto calculation
    public $previewExcessArea = 0;
    public $previewExcessCost = 0;
    public $previewRecommendedHpp = 0;

    protected $queryString = ['project_id'];

    public function mount()
    {
        if ($this->project_id) {
            $this->selected_project_id = $this->project_id;
        }
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['selected_project_id', 'land_width', 'land_length', 'land_area'])) {
            $this->calculateLandPreview();
        }
        if ($propertyName === 'category') {
            $this->type = $this->category;
        }
    }

    public function calculateLandPreview()
    {
        if ($this->land_width > 0 && $this->land_length > 0 && ($this->land_area <= 0)) {
            $this->land_area = $this->land_width * $this->land_length;
        }

        if ($this->selected_project_id) {
            $project = Project::find($this->selected_project_id);
            if ($project) {
                $this->previewExcessArea = max(0, $this->land_area - $project->standard_land_area);
                $this->previewExcessCost = $this->previewExcessArea * $project->excess_price_per_sqm;
                $this->previewRecommendedHpp = $project->base_price + $this->previewExcessCost;
                
                if (is_null($this->hpp) || $this->hpp == 0) {
                    $this->hpp = $this->previewRecommendedHpp;
                }
            }
        }
    }

    public function openModal()
    {
        $this->resetInputFields();
        if (Project::count() > 0) {
            $this->selected_project_id = Project::first()->id;
            $this->calculateLandPreview();
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function resetInputFields()
    {
        $this->selected_project_id = '';
        $this->code = '';
        $this->type = 'kavling';
        $this->category = 'kavling';
        $this->building_area = 0;
        $this->floors_count = 1;
        $this->specifications = '';
        $this->land_width = 10.00;
        $this->land_length = 10.00;
        $this->land_area = 100.00;
        $this->hpp = null;
        $this->editingUnitId = null;
        $this->previewExcessArea = 0;
        $this->previewExcessCost = 0;
        $this->previewRecommendedHpp = 0;
    }

    public function saveUnit()
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isSupervisor()) {
            session()->flash('error', 'Hanya Founder dan Supervisor yang berhak mengedit stok dan HPP unit.');
            return;
        }

        $this->validate([
            'selected_project_id' => 'required|exists:projects,id',
            'code' => 'required|string|max:50',
            'category' => 'required|in:kavling,rumah',
            'building_area' => 'nullable|numeric|min:0',
            'floors_count' => 'nullable|integer|min:1',
            'land_area' => 'required|numeric|min:1',
            'hpp' => 'nullable|numeric|min:0',
        ]);

        $project = Project::findOrFail($this->selected_project_id);

        $excessLandArea = max(0, $this->land_area - $project->standard_land_area);
        $excessCost = $excessLandArea * $project->excess_price_per_sqm;
        $finalHpp = $this->hpp ?? ($project->base_price + $excessCost);

        $unit = Unit::updateOrCreate(
            ['id' => $this->editingUnitId],
            [
                'project_id' => $project->id,
                'code' => strtoupper($this->code),
                'type' => $this->category,
                'category' => $this->category,
                'building_area' => $this->category === 'rumah' ? $this->building_area : null,
                'floors_count' => $this->category === 'rumah' ? $this->floors_count : 1,
                'specifications' => $this->category === 'rumah' ? $this->specifications : null,
                'land_width' => $this->land_width,
                'land_length' => $this->land_length,
                'land_area' => $this->land_area,
                'excess_land_area' => $excessLandArea,
                'excess_cost' => $excessCost,
                'hpp' => $finalHpp,
                'status' => $this->editingUnitId ? Unit::find($this->editingUnitId)->status : 'tersedia',
                'created_by' => auth()->id(),
            ]
        );

        session()->flash('success', 'Data unit ' . $unit->code . ' (' . ucfirst($unit->category) . ') berhasil disimpan! HPP Rp ' . number_format($finalHpp, 0, ',', '.'));
        $this->closeModal();
    }

    public function editUnit($id)
    {
        $unit = Unit::findOrFail($id);
        $this->editingUnitId = $unit->id;
        $this->selected_project_id = $unit->project_id;
        $this->code = $unit->code;
        $this->type = $unit->type;
        $this->category = $unit->category ?? $unit->type;
        $this->building_area = $unit->building_area ?? 0;
        $this->floors_count = $unit->floors_count ?? 1;
        $this->specifications = is_array($unit->specifications) ? json_encode($unit->specifications) : ($unit->specifications ?? '');
        $this->land_width = $unit->land_width;
        $this->land_length = $unit->land_length;
        $this->land_area = $unit->land_area;
        $this->hpp = $unit->hpp;

        $this->calculateLandPreview();
        $this->showModal = true;
    }

    // Direct In-System Booking Methods
    public function openBookingModal($unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->bookingUnitId = $unit->id;
        $this->bookingUnitCode = $unit->code;
        $this->buyer_name = '';
        $this->buyer_phone = '';
        $this->booking_amount = 5000000;
        $this->dp_amount = 0;
        $this->booking_notes = 'Booking unit ' . $unit->code . ' via sistem.';
        $this->showBookingModal = true;
    }

    public function saveBooking()
    {
        $user = auth()->user();
        if (!$user->isMarketing() && !$user->isFinance() && !$user->isFounder()) {
            session()->flash('error', 'Hanya tim Sales Marketing, Finance, dan Founder yang berhak mendaftarkan booking unit.');
            return;
        }

        $this->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:50',
            'booking_amount' => 'required|numeric|min:1000',
        ]);

        $unit = Unit::findOrFail($this->bookingUnitId);

        Booking::create([
            'project_id' => $unit->project_id,
            'unit_id' => $unit->id,
            'buyer_name' => $this->buyer_name,
            'buyer_phone' => $this->buyer_phone,
            'booking_type' => 'unit',
            'booking_amount' => $this->booking_amount,
            'dp_amount' => 0,
            'booking_date' => now()->toDateString(),
            'expiry_date' => now()->addDays(14)->toDateString(),

            'status' => 'active',
            'notes' => $this->booking_notes,
            'created_by' => auth()->id(),
        ]);

        $unit->update(['status' => 'booked']);

        session()->flash('success', 'Booking unit ' . $unit->code . ' atas nama ' . $this->buyer_name . ' berhasil dicatat di sistem!');
        $this->showBookingModal = false;
    }

    public function render()
    {
        $query = Unit::with(['project', 'creator', 'activeAssignments.worker']);

        if ($this->project_id) {
            $query->where('project_id', $this->project_id);
        }

        $units = $query->latest()->paginate(12);
        $projects = Project::where('status', 'aktif')->get();

        return view('livewire.units.index', [
            'units' => $units,
            'projects' => $projects,
        ])->layout('components.layouts.app', ['title' => 'Manajemen Unit Kavling & Rumah']);
    }
}
