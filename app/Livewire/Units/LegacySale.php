<?php

namespace App\Livewire\Units;

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\InstallmentPayment;
use App\Models\OfficialDocument;
use App\Models\PriceProposal;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Services\ActivityLogger;
use App\Services\ImageCompressor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class LegacySale extends Component
{
    use WithPagination;

    // State untuk Modal Form Catat Unit Masa Lalu
    public bool $showModal = false;

    // Filter & Search Properties
    public string $search = '';
    public string $project_filter = '';
    public string $category_filter = '';

    // Unit Properties
    public ?int $project_id = null;
    public string $code = '';
    public string $type = 'Kavling Standar';
    public string $category = 'kavling';
    public float $land_width = 10.0;
    public float $land_length = 10.0;
    public float $land_area = 100.0;
    public ?float $building_area = null;
    public string $specifications = '';

    // Financial & Sales Properties
    public float $hpp = 100000000;
    public float $final_selling_price = 150000000;
    public string $buyer_name = '';
    public string $buyer_phone = '';
    public string $buyer_address = '';
    public string $sale_date = '';
    public string $payment_method = 'Tunai / Cash Lunas';
    public bool $record_cashflow = false;
    public string $notes = '';

    // Viewer Modal Properties
    public bool $showViewerModal = false;
    public string $viewerUrl = '';
    public string $viewerTitle = '';

    protected function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('units', 'code')->where('project_id', $this->project_id),
            ],
            'category' => 'required|in:kavling,rumah',
            'type' => 'required|string|max:100',
            'land_width' => 'required|numeric|min:0.1',
            'land_length' => 'required|numeric|min:0.1',
            'land_area' => 'required|numeric|min:0.1',
            'building_area' => 'nullable|numeric|min:0',
            'specifications' => 'nullable|string|max:1000',
            'hpp' => 'required|numeric|min:1000',
            'final_selling_price' => 'required|numeric|min:1000',
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:50',
            'buyer_address' => 'nullable|string|max:500',
            'sale_date' => 'required|date',
            'payment_method' => 'required|string',
            'record_cashflow' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    protected function messages(): array
    {
        return [
            'project_id.required' => 'Proyek wajib dipilih.',
            'code.required' => 'Kode unit wajib diisi.',
            'code.unique' => 'Kode unit "' . strtoupper($this->code) . '" sudah terdaftar pada proyek ini! Kode unit tidak boleh sama.',
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || !$user->isAdminOrFounder()) {
            abort(403, 'Akses Ditolak: Hanya Admin dan Founder yang berhak mencatat penjualan unit masa lalu / lunas.');
        }

        $this->sale_date = now()->subYear()->toDateString();
        $this->calculateLandArea();
    }

    public function updatedLandWidth(): void
    {
        $this->calculateLandArea();
    }

    public function updatedLandLength(): void
    {
        $this->calculateLandArea();
    }

    public function calculateLandArea(): void
    {
        $this->land_area = (float)($this->land_width ?? 0) * (float)($this->land_length ?? 0);
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->reset(['code', 'buyer_name', 'buyer_phone', 'buyer_address', 'notes', 'specifications', 'building_area']);
        $this->land_width = 10.0;
        $this->land_length = 10.0;
        $this->calculateLandArea();
        $this->hpp = 100000000;
        $this->final_selling_price = 150000000;
        $this->sale_date = now()->subMonths(6)->toDateString();
        $this->showModal = true;
    }

    public function save(): void
    {
        $user = Auth::user();
        if (!$user || !$user->isAdminOrFounder()) {
            session()->flash('error', 'Hanya Admin dan Founder yang berhak mencatat penjualan unit masa lalu.');
            return;
        }

        $validated = $this->validate();

        DB::transaction(function () use ($user) {
            $project = Project::findOrFail($this->project_id);

            // 1. Create Unit with status 'terjual'
            $unit = Unit::create([
                'project_id' => $this->project_id,
                'code' => strtoupper(trim($this->code)),
                'category' => $this->category,
                'type' => $this->type,
                'land_width' => $this->land_width,
                'land_length' => $this->land_length,
                'land_area' => $this->land_area,
                'building_area' => $this->category === 'rumah' ? $this->building_area : null,
                'specifications' => $this->specifications,
                'hpp' => $this->hpp,
                'final_selling_price' => $this->final_selling_price,
                'status' => 'terjual',
                'created_by' => $user->id,
            ]);

            // 2. Create Booking (status converted)
            Booking::create([
                'project_id' => $this->project_id,
                'unit_id' => $unit->id,
                'buyer_name' => $this->buyer_name,
                'buyer_phone' => $this->buyer_phone,
                'booking_type' => 'unit',
                'booking_amount' => $this->final_selling_price,
                'dp_amount' => $this->final_selling_price,
                'booking_date' => $this->sale_date,
                'status' => 'converted',
                'notes' => 'Pencatatan Penjualan Masa Lalu / Lunas 100% (Sebelum Sistem SIM Properti dibuat). ' . ($this->notes ?? ''),
                'created_by' => $user->id,
            ]);

            // 3. Create PriceProposal (status disetujui)
            $margin = (float)$this->final_selling_price - (float)$this->hpp;
            $proposal = PriceProposal::create([
                'unit_id' => $unit->id,
                'hpp_price' => $this->hpp,
                'proposed_price' => $this->final_selling_price,
                'margin' => $margin,
                'is_below_hpp' => $margin < 0,
                'discount_reason' => $margin < 0 ? 'Penjualan historis di bawah HPP' : null,
                'proposed_by' => $user->id,
                'status' => 'disetujui',
                'notes' => 'Persetujuan otomatis penjualan historis oleh Founder.',
            ]);

            // 4. Create OfficialDocument (SPP)
            $docNumber = 'SPP/HISTORIS/' . strtoupper($project->name) . '/' . date('Y/m', strtotime($this->sale_date)) . '/' . str_pad($unit->id, 4, '0', STR_PAD_LEFT);
            $officialDoc = OfficialDocument::create([
                'unit_id' => $unit->id,
                'price_proposal_id' => $proposal->id,
                'document_number' => $docNumber,
                'buyer_name' => $this->buyer_name,
                'buyer_contact' => $this->buyer_phone,
                'buyer_address' => $this->buyer_address ?: '-',
                'issued_by' => $user->id,
                'issued_at' => $this->sale_date,
            ]);

            // 5. Create UnitInstallment & InstallmentPayment (LUNAS 100%)
            $installment = UnitInstallment::create([
                'unit_id' => $unit->id,
                'official_document_id' => $officialDoc->id,
                'total_price' => $this->final_selling_price,
                'down_payment' => $this->final_selling_price,
                'installment_count' => 1,
                'installment_amount' => $this->final_selling_price,
                'start_date' => $this->sale_date,
                'status' => 'lunas',
            ]);

            InstallmentPayment::create([
                'unit_installment_id' => $installment->id,
                'payment_date' => $this->sale_date,
                'amount_paid' => $this->final_selling_price,
                'payment_method' => $this->payment_method,
                'notes' => 'Pelunasan Historis Masa Lalu (Terjual & Lunas 100%)',
                'created_by' => $user->id,
            ]);

            // 6. Record to Cashflow if checkbox is checked
            if ($this->record_cashflow) {
                CashflowTransaction::create([
                    'project_id' => $this->project_id,
                    'type' => 'masuk',
                    'category' => 'penjualan_unit',
                    'amount' => $this->final_selling_price,
                    'transaction_date' => $this->sale_date,
                    'description' => "Pencatatan Penjualan Masa Lalu (Lunas 100%): {$this->buyer_name} (Unit Kode {$unit->code})",
                    'reference_type' => Unit::class,
                    'reference_id' => $unit->id,
                    'created_by' => $user->id,
                ]);
            }

            // 7. Record System Log
            ActivityLogger::log(
                'LEGACY_UNIT_CREATED',
                "Founder mencatat unit historis terjual & lunas 100%: Kode Unit {$unit->code} ({$this->buyer_name} - Rp " . number_format($this->final_selling_price, 0, ',', '.') . ")"
            );
        });

        session()->flash('success', 'Penjualan unit masa lalu (terjual & lunas 100%) atas nama ' . $this->buyer_name . ' (Unit ' . $this->code . ') berhasil dicatat dan masuk ke sistem!');
        $this->showModal = false;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedProjectFilter(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function openViewerModal(string $type, string $url, string $title = ''): void
    {
        $this->viewerType = $type;
        $this->viewerUrl = $url;
        $this->viewerTitle = $title ?: 'Pratinjau Dokumen SPP PDF';
        $this->showViewerModal = true;
    }

    public function closeViewerModal(): void
    {
        $this->showViewerModal = false;
        $this->viewerType = '';
        $this->viewerUrl = '';
        $this->viewerTitle = '';
    }

    public function render()
    {
        $query = Unit::with(['project', 'officialDocument', 'installment'])
            ->where('status', 'terjual');

        if (!empty($this->search)) {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('code', 'like', $term)
                  ->orWhere('type', 'like', $term)
                  ->orWhereHas('officialDocument', function ($docQ) use ($term) {
                      $docQ->where('buyer_name', 'like', $term)
                           ->orWhere('buyer_contact', 'like', $term)
                           ->orWhere('document_number', 'like', $term);
                  });
            });
        }

        if (!empty($this->project_filter)) {
            $query->where('project_id', $this->project_filter);
        }

        if (!empty($this->category_filter)) {
            $query->where('category', $this->category_filter);
        }

        $legacyUnits = $query->latest('id')->paginate(10);
        $projects = Project::orderBy('name')->get();

        return view('livewire.units.legacy-sale', [
            'legacyUnits' => $legacyUnits,
            'projects' => $projects,
        ])->layout('components.layouts.app', ['title' => 'Input Penjualan Masa Lalu (Terjual & Lunas)']);
    }
}
