<?php

namespace App\Livewire\ManualInvoices;

use App\Models\CashflowTransaction;
use App\Models\ManualInvoice;
use App\Models\Project;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $typeFilter = '';
    public string $projectFilter = '';

    // Modal Form State
    public bool $showModal = false;
    public ?int $editingInvoiceId = null;

    public string $invoice_number = '';
    public ?int $project_id = null;
    public ?int $unit_id = null;
    public string $recipient_name = '';
    public string $recipient_phone = '';
    public string $recipient_address = '';
    public string $type = 'masuk';
    public string $category = 'penjualan_unit';
    public $amount = 0;
    public string $invoice_date = '';
    public string $due_date = '';
    public string $payment_method = 'Transfer Bank';
    public string $status = 'lunas';
    public string $description = '';
    public bool $record_cashflow = true;

    // Floating Viewer Modal (Pratinjau PDF di dalam aplikasi)
    public bool $showViewerModal = false;
    public string $viewerUrl = '';
    public string $viewerTitle = '';

    public function mount(): void
    {
        $user = auth()->user();
        if (!$user || (!$user->isFounder() && !$user->isFinance())) {
            abort(403, 'Akses ditolak. Anda tidak memiliki hak akses menu Invoice Manual.');
        }

        $this->invoice_date = date('Y-m-d');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingProjectFilter(): void
    {
        $this->resetPage();
    }

    public function updatedProjectId(): void
    {
        $this->unit_id = null;
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->editingInvoiceId = null;
        $this->invoice_number = '';
        $this->project_id = null;
        $this->unit_id = null;
        $this->recipient_name = '';
        $this->recipient_phone = '';
        $this->recipient_address = '';
        $this->type = 'masuk';
        $this->category = 'penjualan_unit';
        $this->amount = 0;
        $this->invoice_date = date('Y-m-d');
        $this->due_date = '';
        $this->payment_method = 'Transfer Bank';
        $this->status = 'lunas';
        $this->description = '';
        $this->record_cashflow = true;
        $this->showModal = true;
    }

    public function editInvoice(int $id): void
    {
        $invoice = ManualInvoice::findOrFail($id);
        $this->resetValidation();
        $this->editingInvoiceId = $invoice->id;
        $this->invoice_number = $invoice->invoice_number;
        $this->project_id = $invoice->project_id;
        $this->unit_id = $invoice->unit_id;
        $this->recipient_name = $invoice->recipient_name;
        $this->recipient_phone = $invoice->recipient_phone ?? '';
        $this->recipient_address = $invoice->recipient_address ?? '';
        $this->type = $invoice->type;
        $this->category = $invoice->category;
        $this->amount = $invoice->amount;
        $this->invoice_date = $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : date('Y-m-d');
        $this->due_date = $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '';
        $this->payment_method = $invoice->payment_method;
        $this->status = $invoice->status;
        $this->description = $invoice->description ?? '';
        $this->record_cashflow = (bool)$invoice->record_cashflow;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->editingInvoiceId = null;
    }

    public function saveInvoice(): void
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Accounting yang berhak menyimpan invoice manual.');
            return;
        }

        $this->validate([
            'recipient_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000',
            'type' => 'required|in:masuk,keluar',
            'category' => 'required|string',
            'invoice_date' => 'required|date',
            'payment_method' => 'required|string',
            'status' => 'required|in:lunas,pending,draf',
        ]);

        DB::transaction(function () {
            $invoiceData = [
                'project_id' => $this->project_id ?: null,
                'unit_id' => $this->unit_id ?: null,
                'recipient_name' => $this->recipient_name,
                'recipient_phone' => $this->recipient_phone ?: null,
                'recipient_address' => $this->recipient_address ?: null,
                'type' => $this->type,
                'category' => $this->category,
                'amount' => $this->amount,
                'invoice_date' => $this->invoice_date,
                'due_date' => $this->due_date ?: null,
                'payment_method' => $this->payment_method,
                'status' => $this->status,
                'description' => $this->description ?: null,
                'record_cashflow' => $this->record_cashflow,
            ];

            if ($this->editingInvoiceId) {
                $invoice = ManualInvoice::findOrFail($this->editingInvoiceId);
                $invoice->update($invoiceData);
            } else {
                if ($this->invoice_number) {
                    $invoiceData['invoice_number'] = strtoupper(trim($this->invoice_number));
                }
                $invoiceData['created_by'] = Auth::id();
                $invoice = ManualInvoice::create($invoiceData);
            }

            // If unit_id is linked and type is masuk and status is lunas for unit sale, sync unit status & selling price
            if ($invoice->unit_id && $invoice->type === 'masuk' && $invoice->status === 'lunas' && $invoice->category === 'penjualan_unit') {
                $unit = Unit::find($invoice->unit_id);
                if ($unit) {
                    $updateUnitData = [];
                    if ($unit->status === 'tersedia') {
                        $updateUnitData['status'] = 'terjual';
                    }
                    if (!$unit->final_selling_price || $unit->final_selling_price <= 0) {
                        $updateUnitData['final_selling_price'] = $invoice->amount;
                    }
                    if (!empty($updateUnitData)) {
                        $unit->update($updateUnitData);
                    }
                }
            }

            // Sync with Arus Keuangan (CashflowTransaction)
            if ($invoice->record_cashflow && $invoice->status === 'lunas') {
                $cashflow = CashflowTransaction::where('reference_type', ManualInvoice::class)
                    ->where('reference_id', $invoice->id)
                    ->first();

                $cashflowData = [
                    'project_id' => $invoice->project_id,
                    'type' => $invoice->type,
                    'category' => $invoice->category,
                    'amount' => $invoice->amount,
                    'transaction_date' => $invoice->invoice_date,
                    'description' => 'Invoice Manual [' . $invoice->invoice_number . ']: ' . $invoice->recipient_name . ($invoice->description ? ' (' . $invoice->description . ')' : ''),
                    'reference_type' => ManualInvoice::class,
                    'reference_id' => $invoice->id,
                    'created_by' => Auth::id(),
                ];

                if ($cashflow) {
                    $cashflow->update($cashflowData);
                } else {
                    CashflowTransaction::create($cashflowData);
                }
            } else {
                // If status is not lunas or record_cashflow is false, remove cashflow transaction if exists
                CashflowTransaction::where('reference_type', ManualInvoice::class)
                    ->where('reference_id', $invoice->id)
                    ->delete();
            }
        });

        if ($this->editingInvoiceId) {
            \App\Services\ActivityLogger::log('MANUAL_INVOICE_UPDATED', "Invoice manual #{$this->invoice_number} (Penerima: {$this->recipient_name}) diperbarui.");
        } else {
            \App\Services\ActivityLogger::log('MANUAL_INVOICE_CREATED', "Invoice manual baru #{$this->invoice_number} (Penerima: {$this->recipient_name}) diterbitkan sebesar Rp " . number_format($this->amount, 0, ',', '.'));
        }

        session()->flash('success', 'Data Invoice Manual ' . ($this->editingInvoiceId ? 'berhasil diperbarui' : 'berhasil dibuat') . ' & disinkronkan ke Arus Keuangan!');
        $this->closeModal();
    }

    public function deleteInvoice(int $id): void
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Founder dan Accounting yang berhak menghapus invoice manual.');
            return;
        }

        $invoice = ManualInvoice::findOrFail($id);
        $num = $invoice->invoice_number;

        DB::transaction(function () use ($invoice) {
            CashflowTransaction::where('reference_type', ManualInvoice::class)
                ->where('reference_id', $invoice->id)
                ->delete();

            $invoice->delete();
        });

        \App\Services\ActivityLogger::log('MANUAL_INVOICE_DELETED', "Invoice manual #{$num} dihapus dari sistem.");
        session()->flash('success', 'Invoice manual berhasil dihapus & mutasi arus kas terkait dibersihkan.');
    }

    public function openPdfPreview(string $uuid): void
    {
        $invoice = ManualInvoice::where('uuid', $uuid)->firstOrFail();
        $this->viewerUrl = route('manual-invoices.pdf', $invoice->uuid);
        $this->viewerTitle = 'Pratinjau Invoice Manual ' . $invoice->invoice_number;
        $this->showViewerModal = true;
    }

    public function closeViewerModal(): void
    {
        $this->showViewerModal = false;
        $this->viewerUrl = '';
        $this->viewerTitle = '';
    }

    public function render()
    {
        $query = ManualInvoice::with(['project', 'unit', 'creator']);

        if ($this->search) {
            $search = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', $search)
                  ->orWhere('recipient_name', 'like', $search)
                  ->orWhere('recipient_phone', 'like', $search)
                  ->orWhere('description', 'like', $search);
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        if ($this->projectFilter) {
            $query->where('project_id', $this->projectFilter);
        }

        $invoices = $query->latest('invoice_date')->latest('id')->paginate(15);

        $projects = Project::orderBy('name')->get();
        $availableUnits = $this->project_id ? Unit::where('project_id', $this->project_id)->orderBy('code')->get() : collect();

        // Financial Summary Metrics
        $totalMasuk = ManualInvoice::where('status', 'lunas')->where('type', 'masuk')->sum('amount');
        $totalKeluar = ManualInvoice::where('status', 'lunas')->where('type', 'keluar')->sum('amount');
        $totalPending = ManualInvoice::where('status', 'pending')->sum('amount');

        return view('livewire.manual-invoices.index', [
            'invoices' => $invoices,
            'projects' => $projects,
            'availableUnits' => $availableUnits,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'totalPending' => $totalPending,
            'showModal' => $this->showModal,
            'showViewerModal' => $this->showViewerModal,
        ])->layout('components.layouts.app', ['title' => 'Invoice Manual & Arus Keuangan']);
    }
}
