<?php

namespace App\Livewire\Units;

use App\Models\Booking;
use App\Models\Project;
use App\Models\Unit;
use App\Models\WorkerAssignment;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $project_id;
    public $showModal = false;

    // Unit Form State
    public $selected_project_id = '';
    public $code = '';
    public $type = 'kavling';
    public $category = 'kavling';
    public $infra_type = 'parit';
    public $building_area = 0;
    public $floors_count = 1;
    public $specifications = '';
    public $land_width = 10.00;
    public $land_length = 10.00;
    public $land_area = 100.00;
    public $hpp = null;
    public $editingUnitId = null;

    public $category_filter = '';
    public $status_filter = '';
    public string $search = '';
    public string $viewMode = 'table'; // 'table' or 'siteplan'

    // Booking Modal State
    public $showBookingModal = false;
    public $bookingUnitId = null;
    public $bookingUnitCode = '';
    public $buyer_name = '';
    public $buyer_phone = '';
    public $booking_amount = 5000000;
    public $dp_amount = 25000000;
    public $booking_notes = '';
    public $receipt_photo = null;

    // Computed preview properties for auto calculation
    public $previewExcessArea = 0;
    public $previewExcessCost = 0;
    public $previewRecommendedHpp = 0;

    protected $queryString = ['project_id', 'category_filter', 'status_filter', 'search'];

    public function mount()
    {
        if ($this->project_id) {
            $this->selected_project_id = $this->project_id;
        }
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['search', 'status_filter', 'category_filter', 'project_id'])) {
            $this->resetPage();
        }
        if (in_array($propertyName, ['selected_project_id', 'land_width', 'land_length', 'land_area'])) {
            $this->calculateLandPreview();
        }
        if ($propertyName === 'category' && $this->category !== 'infrastruktur') {
            $this->type = $this->category;
        }
    }

    public function calculateLandPreview()
    {
        if ($this->land_width > 0 && $this->land_length > 0 && ($this->land_area <= 0)) {
            $this->land_area = $this->land_width * $this->land_length;
        }

        if ($this->selected_project_id && $this->category !== 'infrastruktur') {
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
        $this->infra_type = 'parit';
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
        if (!$user->isAdminOrFounder() && !$user->isSupervisor()) {
            session()->flash('error', 'Hanya Founder, Admin, dan Supervisor yang berhak mengedit stok dan unit.');
            return;
        }

        $this->validate([
            'selected_project_id' => 'required|exists:projects,id',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('units', 'code')
                    ->where('project_id', $this->selected_project_id)
                    ->ignore($this->editingUnitId),
            ],
            'category' => 'required|in:kavling,rumah,infrastruktur',
            'infra_type' => 'nullable|string',
            'building_area' => 'nullable|numeric|min:0',
            'floors_count' => 'nullable|integer|min:1',
            'land_area' => 'required|numeric|min:0',
            'hpp' => 'nullable|numeric|min:0',
        ], [
            'selected_project_id.required' => 'Proyek wajib dipilih.',
            'code.required' => 'Kode unit wajib diisi.',
            'code.unique' => 'Kode unit "' . strtoupper($this->code) . '" sudah terdaftar pada proyek ini! Kode unit tidak boleh sama.',
        ]);

        $project = Project::findOrFail($this->selected_project_id);

        $excessLandArea = 0;
        $excessCost = 0;
        $finalHpp = 0;
        $unitType = $this->category;
        $unitStatus = 'tersedia';

        if ($this->category === 'infrastruktur') {
            $unitType = $this->infra_type ?: 'parit';
            $finalHpp = $this->hpp ?? 0;
            $unitStatus = $this->editingUnitId ? Unit::find($this->editingUnitId)->status : 'infrastruktur';
        } else {
            $excessLandArea = max(0, $this->land_area - $project->standard_land_area);
            $excessCost = $excessLandArea * $project->excess_price_per_sqm;
            $finalHpp = $this->hpp ?? ($project->base_price + $excessCost);
            $unitStatus = $this->editingUnitId ? Unit::find($this->editingUnitId)->status : 'tersedia';
        }

        $isEdit = !empty($this->editingUnitId);

        $unit = Unit::updateOrCreate(
            ['id' => $this->editingUnitId],
            [
                'project_id' => $project->id,
                'code' => strtoupper($this->code),
                'type' => $unitType,
                'category' => $this->category,
                'building_area' => $this->category === 'rumah' ? $this->building_area : null,
                'floors_count' => $this->category === 'rumah' ? $this->floors_count : 1,
                'specifications' => $this->specifications,
                'land_width' => $this->land_width,
                'land_length' => $this->land_length,
                'land_area' => $this->land_area,
                'excess_land_area' => $excessLandArea,
                'excess_cost' => $excessCost,
                'hpp' => $finalHpp,
                'status' => $unitStatus,
                'created_by' => auth()->id(),
            ]
        );

        if ($isEdit) {
            \App\Services\ActivityLogger::log('UNIT_UPDATED', "Data Unit {$unit->code} (Proyek: {$project->name}) diperbarui oleh " . auth()->user()->name);
        } else {
            \App\Services\ActivityLogger::log('UNIT_CREATED', "Unit baru {$unit->code} (Proyek: {$project->name}) berhasil ditambahkan ke sistem.");
        }

        $label = $this->category === 'infrastruktur' ? 'Infrastruktur Kawasan (' . strtoupper($unitType) . ')' : ucfirst($unit->category);
        session()->flash('success', 'Data unit ' . $unit->code . ' (' . $label . ') berhasil disimpan!');
        $this->closeModal();
    }

    public function editUnit($id)
    {
        $unit = Unit::findOrFail($id);
        $this->editingUnitId = $unit->id;
        $this->selected_project_id = $unit->project_id;
        $this->code = $unit->code;
        $this->category = $unit->category ?? ($unit->status === 'infrastruktur' ? 'infrastruktur' : 'kavling');
        $this->type = $unit->type;
        $this->infra_type = $unit->category === 'infrastruktur' ? $unit->type : 'parit';
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

    public function deleteUnit($id)
    {
        $user = auth()->user();
        if (!$user || !$user->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak menghapus unit dari sistem.');
            return;
        }

        $unit = Unit::findOrFail($id);
        $code = $unit->code;

        \App\Services\CascadeDeletionService::deleteUnit($unit);

        \App\Services\ActivityLogger::log('UNIT_DELETED', "Founder menghapus Unit {$code} dari sistem.");
        session()->flash('success', 'Unit ' . $code . ' berhasil dihapus dari sistem!');
    }

    // Direct In-System Booking Methods
    public function openBookingModal($unitId)
    {
        $unit = Unit::findOrFail($unitId);
        if ($unit->category === 'infrastruktur' || $unit->status === 'infrastruktur') {
            session()->flash('error', 'Unit Fasilitas Umum / Infrastruktur tidak dapat dibooking untuk penjualan.');
            return;
        }

        $this->bookingUnitId = $unit->id;
        $this->bookingUnitCode = $unit->code;
        $this->buyer_name = '';
        $this->buyer_phone = '';
        $this->booking_amount = 5000000;
        $this->dp_amount = 0;
        $this->booking_notes = 'Booking unit ' . $unit->code . ' via sistem.';
        $this->receipt_photo = null;
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
            'receipt_photo' => 'nullable|image|max:2048',
        ]);

        $unit = Unit::findOrFail($this->bookingUnitId);

        $receiptPath = null;
        if ($this->receipt_photo) {
            $receiptPath = $this->receipt_photo->store('receipts/bookings', 'public');
        }

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
            'receipt_photo_path' => $receiptPath,
            'created_by' => auth()->id(),
        ]);

        $unit->update(['status' => 'booked']);

        \App\Services\ActivityLogger::log('BOOKING_CREATED', "Booking fee unit {$unit->code} atas nama {$this->buyer_name} tercatat di sistem.");

        session()->flash('success', 'Booking unit ' . $unit->code . ' atas nama ' . $this->buyer_name . ' berhasil dicatat di sistem!');
        $this->showBookingModal = false;
    }

    public function render()
    {
        $query = Unit::with([
            'project',
            'creator',
            'activeAssignments.worker',
            'installment.payments',
            'officialDocument.proposal',
            'proposals',
            'activeBooking',
            'bookings',
        ]);

        if ($this->search) {
            $search = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', $search)
                  ->orWhere('type', 'like', $search)
                  ->orWhere('category', 'like', $search)
                  ->orWhere('specifications', 'like', $search)
                  ->orWhereHas('project', function ($pQ) use ($search) {
                      $pQ->where('name', 'like', $search);
                  })
                  ->orWhereHas('activeAssignments.worker', function ($wQ) use ($search) {
                      $wQ->where('name', 'like', $search);
                  });
            });
        }

        $user = auth()->user();
        if ($user && $user->isPengawasProject()) {
            $assignedProjectIds = WorkerAssignment::where('user_id', $user->id)
                ->where('status', 'active')
                ->pluck('project_id');
            $query->whereIn('project_id', $assignedProjectIds);
        }

        if ($this->project_id) {
            $query->where('project_id', $this->project_id);
        }

        if ($this->category_filter) {
            $query->where('category', $this->category_filter);
        }

        if ($this->status_filter) {
            if ($this->status_filter === 'terjual') {
                $query->whereIn('status', ['terjual', 'disetujui', 'converted']);
            } elseif ($this->status_filter === 'booked') {
                $query->whereIn('status', ['booked', 'menunggu_persetujuan']);
            } elseif ($this->status_filter === 'infrastruktur') {
                $query->where(function ($q) {
                    $q->where('category', 'infrastruktur')
                      ->orWhere('status', 'infrastruktur');
                });
            } else {
                $query->where('status', $this->status_filter);
            }
        }

        if ($this->viewMode === 'siteplan') {
            $units = $query->orderBy('code', 'asc')->get();
        } else {
            $units = $query->latest()->paginate(12);
        }

        if ($user && $user->isPengawasProject()) {
            $assignedProjectIds = WorkerAssignment::where('user_id', $user->id)
                ->where('status', 'active')
                ->pluck('project_id');
            $projects = Project::where('status', 'aktif')->whereIn('id', $assignedProjectIds)->get();
        } else {
            $projects = Project::where('status', 'aktif')->get();
        }

        $unitPaymentsData = [];
        foreach ($units as $unit) {
            $dealPrice = 0;
            $paidAmount = 0;
            $isSold = in_array($unit->status, ['disetujui', 'booked', 'terjual', 'converted']);

            if ($unit->installment) {
                $dealPrice = (float)$unit->installment->total_price;
                $paidAmount = (float)$unit->installment->down_payment + (float)$unit->installment->payments->sum('amount_paid');
            } elseif ($unit->final_selling_price > 0) {
                $dealPrice = (float)$unit->final_selling_price;
            } elseif ($unit->officialDocument) {
                $dealPrice = (float)($unit->officialDocument->proposal->proposed_price ?? 0);
            } elseif ($prop = $unit->proposals->where('status', 'disetujui')->first()) {
                $dealPrice = (float)$prop->proposed_price;
            }

            $booking = $unit->activeBooking ?? $unit->bookings->first();
            if ($booking) {
                if (!$unit->installment) {
                    if ($dealPrice <= 0) {
                        $dealPrice = (float)($booking->total_price ?? $booking->booking_amount);
                    }
                    $paidAmount = (float)$booking->booking_amount + (float)$booking->dp_amount;
                }
            }

            $remainingAmount = max(0, $dealPrice - $paidAmount);

            $unitPaymentsData[$unit->id] = [
                'deal_price' => $dealPrice,
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
                'is_sold' => $isSold,
            ];
        }

        return view('livewire.units.index', [
            'units' => $units,
            'projects' => $projects,
            'unitPaymentsData' => $unitPaymentsData,
            'showModal' => $this->showModal,
            'showBookingModal' => $this->showBookingModal,
        ])->layout('components.layouts.app', ['title' => 'Manajemen Unit Kavling, Rumah & Infrastruktur']);
    }
}
