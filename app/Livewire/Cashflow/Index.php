<?php

namespace App\Livewire\Cashflow;

use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $filter_project_id = '';
    public $filter_unit_id = '';
    public $filter_month = ''; // format YYYY-MM
    public $view_mode = 'global'; // 'global', 'project', atau 'unit'
    public $showManualModal = false;

    // Audit Trail Detail Modal
    public $showDetailModal = false;
    public $selectedTransactionId = null;

    // Image Modal (Foto Resi Pembayaran Modal)
    public bool $showImageModal = false;
    public string $imageModalUrl = '';
    public string $imageModalTitle = '';

    public function openImageModal(string $url, string $title = ''): void
    {
        $this->imageModalUrl = $url;
        $this->imageModalTitle = $title ?: 'Foto Resi Bukti Transfer / Transaksi';
        $this->showImageModal = true;
    }

    public function closeImageModal(): void
    {
        $this->showImageModal = false;
        $this->imageModalUrl = '';
        $this->imageModalTitle = '';
    }

    protected $queryString = [
        'view_mode' => ['except' => 'global'],
        'filter_project_id' => ['except' => ''],
        'filter_unit_id' => ['except' => ''],
        'filter_month' => ['except' => ''],
    ];

    // Manual Transaction Form
    public $project_id = '';
    public $type = 'masuk';
    public $category = 'operasional';
    public $amount = 0;
    public $transaction_date = '';
    public $description = '';
    public $receipt_photo = null;

    public function boot()
    {
        if (empty($this->filter_month)) {
            $this->filter_month = date('Y-m');
        }
    }

    public function mount()
    {
        $user = auth()->user();
        if (!$user->isFounder() && !$user->isFinance()) {
            session()->flash('error', 'Akses ditolak. Modul Laporan Arus Kas & Global bersifat rahasia (Hanya untuk Founder dan Finance).');
            return redirect()->route('dashboard');
        }

        $this->filter_month = date('Y-m');
        $this->transaction_date = date('Y-m-d');
        $this->project_id = '';
    }

    public function updatedFilterProjectId()
    {
        $this->filter_unit_id = '';
        $this->resetPage();
    }

    public function updatedFilterUnitId()
    {
        $this->resetPage();
    }

    public function updatedFilterMonth()
    {
        $this->resetPage();
    }

    public function updatedViewMode()
    {
        $this->resetPage();
    }

    public function setAllTime()
    {
        $this->filter_month = '';
        $this->resetPage();
    }

    public function setCurrentMonth()
    {
        $this->filter_month = date('Y-m');
        $this->resetPage();
    }

    public function openDetailModal($id)
    {
        $this->selectedTransactionId = $id;
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedTransactionId = null;
    }

    public function deleteTransaction($id)
    {
        $user = auth()->user();
        if (!$user || !$user->isSuperAdmin()) {
            session()->flash('error', 'Hanya Founder dan Supervisor yang berhak menghapus data transaksi arus kas.');
            return;
        }

        $trx = CashflowTransaction::findOrFail($id);
        $trxId = $trx->id;
        $description = $trx->description;
        $amount = number_format($trx->amount, 0, ',', '.');

        // Cascade cleanup to underlying source payment record if exists
        if ($trx->reference_type && $trx->reference_id) {
            if ($trx->reference_type === \App\Models\InstallmentPayment::class) {
                $payment = \App\Models\InstallmentPayment::find($trx->reference_id);
                if ($payment) {
                    $installment = $payment->unitInstallment;
                    $payment->delete();

                    if ($installment) {
                        $totalPaid = $installment->payments()->sum('amount_paid') + (float)$installment->down_payment;
                        $remainingBalance = max(0, (float)$installment->total_price - $totalPaid);
                        $newStatus = ($remainingBalance <= 0) ? 'lunas' : 'berjalan';
                        $installment->update([
                            'total_paid' => $totalPaid,
                            'remaining_balance' => $remainingBalance,
                            'status' => $newStatus,
                        ]);
                    }
                }
            } elseif ($trx->reference_type === \App\Models\Booking::class) {
                $booking = \App\Models\Booking::with('unit')->find($trx->reference_id);
                if ($booking) {
                    if ($booking->unit && \App\Enums\UnitStatus::isBooked($booking->unit->status)) {
                        $booking->unit->update(['status' => \App\Enums\UnitStatus::TERSEDIA->value]);
                    }
                    if ($booking->receipt_photo_path) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($booking->receipt_photo_path);
                    }
                    $booking->delete();
                }
            } elseif ($trx->reference_type === \App\Models\WeeklyMaterialPurchase::class) {
                $purchase = \App\Models\WeeklyMaterialPurchase::find($trx->reference_id);
                if ($purchase) {
                    if ($purchase->receipt_photo_path) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($purchase->receipt_photo_path);
                    }
                    $purchase->delete();
                }
            } elseif ($trx->reference_type === \App\Models\WorkerSalaryPayment::class) {
                $sp = \App\Models\WorkerSalaryPayment::find($trx->reference_id);
                if ($sp) {
                    $sp->delete();
                }
            } elseif ($trx->reference_type === \App\Models\EmployeePayrollPayment::class) {
                $ep = \App\Models\EmployeePayrollPayment::find($trx->reference_id);
                if ($ep) {
                    $ep->delete();
                }
            } elseif ($trx->reference_type === \App\Models\ProjectPayment::class) {
                $pp = \App\Models\ProjectPayment::find($trx->reference_id);
                if ($pp) {
                    if ($pp->receipt_photo_path) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($pp->receipt_photo_path);
                    }
                    $pp->delete();
                }
            } elseif ($trx->reference_type === \App\Models\ManualInvoice::class) {
                $mi = \App\Models\ManualInvoice::find($trx->reference_id);
                if ($mi) {
                    $mi->delete();
                }
            }
        }

        if ($trx->receipt_photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($trx->receipt_photo_path);
        }

        $trx->delete();

        \App\Services\ActivityLogger::log(
            'CASHFLOW_DELETED',
            "Founder menghapus transaksi arus kas #TRX-{$trxId}: {$description} (Rp {$amount}) beserta data sumbernya"
        );

        $msg = "Transaksi mutasi kas #TRX-{$trxId} dan data sumber terikat berhasil dihapus.";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
    }

    public bool $showEditModal = false;
    public $editingTransactionId = null;
    public $edit_project_id = '';
    public string $edit_description = '';
    public string $edit_category = '';
    public float $edit_amount = 0;
    public string $edit_transaction_date = '';

    public function editTransaction($id): void
    {
        $user = auth()->user();
        if (!$user->isAdminOrFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Admin Utama dan Finance yang berhak mengedit data transaksi arus kas.');
            return;
        }

        $trx = CashflowTransaction::findOrFail($id);
        $this->editingTransactionId = $trx->id;
        $this->edit_project_id = $trx->project_id ? (string)$trx->project_id : '';
        $this->edit_description = $trx->description;
        $this->edit_category = $trx->category;
        $this->edit_amount = (float) $trx->amount;
        $this->edit_transaction_date = $trx->transaction_date ? \Carbon\Carbon::parse($trx->transaction_date)->format('Y-m-d') : date('Y-m-d');
        $this->showEditModal = true;
    }

    public function updateTransaction(): void
    {
        $user = auth()->user();
        if (!$user->isAdminOrFounder() && !$user->isFinance()) {
            session()->flash('error', 'Hanya Admin Utama dan Finance yang berhak mengedit data transaksi arus kas.');
            return;
        }

        $this->validate([
            'edit_project_id' => 'nullable|exists:projects,id',
            'edit_description' => 'required|string|max:255',
            'edit_category' => 'required|string',
            'edit_amount' => 'required|numeric|min:0',
            'edit_transaction_date' => 'required|date',
        ]);

        $trx = CashflowTransaction::findOrFail($this->editingTransactionId);
        $oldDesc = $trx->description;
        $oldAmount = number_format($trx->amount, 0, ',', '.');

        $trx->update([
            'project_id' => !empty($this->edit_project_id) ? (int)$this->edit_project_id : null,
            'description' => $this->edit_description,
            'category' => $this->edit_category,
            'amount' => $this->edit_amount,
            'transaction_date' => $this->edit_transaction_date,
        ]);

        $newAmount = number_format($this->edit_amount, 0, ',', '.');
        \App\Services\ActivityLogger::log(
            'CASHFLOW_UPDATED',
            "User {$user->name} memperbarui transaksi arus kas #TRX-{$trx->id}: Keterangan lama '{$oldDesc}' (Rp {$oldAmount}) -> baru '{$this->edit_description}' (Rp {$newAmount})"
        );

        $this->showEditModal = false;
        $this->editingTransactionId = null;
        $msg = "Transaksi mutasi kas #TRX-{$trx->id} berhasil diperbarui!";
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
    }

    private function resolveAuditTrail(CashflowTransaction $t): array
    {
        // 1. Source Menu Determination
        $sourceMenu = 'Input Langsung Modul Arus Kas';
        if ($t->reference_type) {
            if ($t->reference_type === \App\Models\ManualInvoice::class) {
                $sourceMenu = 'Menu Invoice Manual / Kuitansi';
            } elseif ($t->reference_type === \App\Models\InstallmentPayment::class) {
                $sourceMenu = 'Menu Cicilan Pembeli & Setoran Unit';
            } elseif ($t->reference_type === \App\Models\Booking::class) {
                $sourceMenu = 'Menu Booking Unit & Penjualan';
            } elseif ($t->reference_type === \App\Models\ProjectPayment::class) {
                $sourceMenu = 'Menu Detail Proyek (Lahan & Operasional)';
            } elseif ($t->reference_type === \App\Models\WorkerSalaryPayment::class) {
                $sourceMenu = 'Menu Penggajian Pekerja / Detail Unit';
            }
        } else {
            $descLower = strtolower($t->description ?? '');
            if (str_contains($descLower, 'material') || str_contains($descLower, 'semen') || str_contains($descLower, 'pasir') || str_contains($descLower, 'bata')) {
                $sourceMenu = 'Menu Belanja Material Unit';
            } elseif (str_contains($descLower, 'lapangan') || str_contains($descLower, 'operasional')) {
                $sourceMenu = 'Menu Pengeluaran Lapangan / Operasional';
            }
        }

        // 2. Day, Date, and Time Formatting (Hari, Tanggal, Jam)
        $dayNameCreated = $t->created_at ? $t->created_at->locale('id')->isoFormat('dddd') : '-';
        $fullCreatedAt = $t->created_at 
            ? $t->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY [pukul] HH:mm:ss [WIB]')
            : '-';

        $dayNameTrx = $t->transaction_date ? $t->transaction_date->locale('id')->isoFormat('dddd') : '-';
        $fullTrxDate = $t->transaction_date 
            ? $t->transaction_date->locale('id')->isoFormat('dddd, D MMMM YYYY')
            : '-';

        $inputtedBy = [
            'name' => $t->creator->name ?? 'Sistem Keuangan',
            'email' => $t->creator->email ?? '-',
            'role' => $t->creator->role ?? 'Sistem',
            'created_at' => $fullCreatedAt,
            'day' => $dayNameCreated,
            'time' => $t->created_at ? $t->created_at->format('H:i') . ' WIB' : '-',
        ];

        $approvedBy = [
            'name' => 'Tim Finance / Founder',
            'role' => 'Finance & Management ACC',
            'status' => 'Disetujui & Sah (Lunas)',
            'approved_at' => $fullTrxDate,
            'day' => $dayNameTrx,
            'notes' => 'Tercatat dalam laporan mutasi arus kas resmi',
        ];

        $referenceDetail = null;

        if ($t->reference_type && $t->reference_id) {
            if ($t->reference_type === \App\Models\ManualInvoice::class) {
                $inv = \App\Models\ManualInvoice::with('creator')->find($t->reference_id);
                if ($inv) {
                    $referenceDetail = [
                        'type' => 'Invoice Manual',
                        'number' => $inv->invoice_number,
                        'recipient' => $inv->recipient_name,
                        'creator' => $inv->creator->name ?? 'Finance',
                    ];
                    $approvedBy['name'] = $inv->creator->name ?? 'Tim Finance / Founder';
                    $approvedBy['notes'] = 'Diterbitkan via Invoice Manual ke ' . $inv->recipient_name;
                }
            } elseif ($t->reference_type === \App\Models\InstallmentPayment::class) {
                $pay = \App\Models\InstallmentPayment::with(['installment.unit.project', 'creator'])->find($t->reference_id);
                if ($pay) {
                    $unitCode = $pay->installment->unit->code ?? '-';
                    $referenceDetail = [
                        'type' => 'Setoran Cicilan Pembeli',
                        'number' => 'REF-' . substr($pay->uuid ?? (string)$pay->id, 0, 8),
                        'recipient' => 'Klien Pembeli Unit ' . $unitCode,
                        'creator' => $pay->creator->name ?? 'Finance',
                    ];
                    $inputtedBy['name'] = $pay->creator->name ?? $inputtedBy['name'];
                    $approvedBy['notes'] = 'Setoran cicilan pembeli unit ' . $unitCode . ' terkonfirmasi lunas';
                }
            } elseif ($t->reference_type === \App\Models\Booking::class) {
                $b = \App\Models\Booking::with(['unit', 'creator'])->find($t->reference_id);
                if ($b) {
                    $referenceDetail = [
                        'type' => 'Booking Fee / Uang Tanda Jadi',
                        'number' => 'BOOK-' . $b->id,
                        'recipient' => $b->buyer_name,
                        'creator' => $b->creator->name ?? 'Sales Marketing',
                    ];
                    $inputtedBy['name'] = $b->creator->name ?? $inputtedBy['name'];
                    $approvedBy['notes'] = 'Booking fee disetujui & dikonversi oleh Tim Finance/Founder';
                }
            } elseif ($t->reference_type === \App\Models\ProjectPayment::class) {
                $pp = \App\Models\ProjectPayment::with(['project', 'creator'])->find($t->reference_id);
                if ($pp) {
                    $referenceDetail = [
                        'type' => 'Pembayaran Lahan Proyek',
                        'number' => 'LAND-PAY-' . $pp->id,
                        'recipient' => 'Penjual Lahan Proyek ' . ($pp->project->name ?? ''),
                        'creator' => $pp->creator->name ?? 'Founder/Finance',
                    ];
                    $inputtedBy['name'] = $pp->creator->name ?? $inputtedBy['name'];
                    $approvedBy['notes'] = 'Pembayaran lahan proyek terverifikasi & tercatat di kuitansi resmi';
                }
            } elseif ($t->reference_type === \App\Models\WorkerSalaryPayment::class) {
                $sp = \App\Models\WorkerSalaryPayment::with(['payroll.worker', 'creator'])->find($t->reference_id);
                if ($sp) {
                    $referenceDetail = [
                        'type' => 'Gaji Worker / Mandor',
                        'number' => 'PAYROLL-' . $sp->id,
                        'recipient' => $sp->payroll->worker->name ?? 'Pekerja Lapangan',
                        'creator' => $sp->creator->name ?? 'Pengawas/Finance',
                    ];
                    $inputtedBy['name'] = $sp->creator->name ?? $inputtedBy['name'];
                    $approvedBy['notes'] = 'Penggajian pekerja lapangan dikonfirmasi & dibayarkan';
                }
            }
        }

        return [
            'source_menu' => $sourceMenu,
            'created_at_full' => $fullCreatedAt,
            'transaction_date_full' => $fullTrxDate,
            'inputted_by' => $inputtedBy,
            'approved_by' => $approvedBy,
            'reference_detail' => $referenceDetail,
        ];
    }

    public function openManualModal()
    {
        $this->resetForm();
        if ($this->filter_project_id && $this->filter_project_id !== 'non_project') {
            $this->project_id = $this->filter_project_id;
        } else {
            $this->project_id = '';
        }
        $this->showManualModal = true;
    }

    public function resetForm()
    {
        $this->project_id = '';
        $this->type = 'masuk';
        $this->category = 'pembayaran_cicilan_pembeli';
        $this->amount = 0;
        $this->transaction_date = date('Y-m-d');
        $this->description = '';
        $this->receipt_photo = null;
    }

    public function updatedType($value): void
    {
        if ($value === 'masuk') {
            $this->category = 'pembayaran_cicilan_pembeli';
        } else {
            $this->category = 'operasional';
        }
    }

    public function closeManualModal(): void
    {
        $this->showManualModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingTransactionId = null;
        $this->resetValidation();
    }

    public function saveTransaction()
    {
        $this->validate([
            'project_id' => 'nullable|exists:projects,id',
            'type' => 'required|in:masuk,keluar',
            'category' => 'nullable|string',
            'amount' => 'required|numeric|min:1000',
            'description' => 'required|string|max:255',
            'transaction_date' => 'required|date',
            'receipt_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,heic,heif,pdf|max:5120',
        ]);

        $receiptPath = null;
        if ($this->receipt_photo) {
            $receiptPath = \App\Services\ImageCompressor::compressAndStore($this->receipt_photo, 'receipts/cashflow');
        }

        CashflowTransaction::create([
            'project_id' => !empty($this->project_id) ? (int)$this->project_id : null,
            'type' => $this->type,
            'category' => $this->category ?: 'operasional',
            'amount' => (float)$this->amount,
            'transaction_date' => $this->transaction_date ?: date('Y-m-d'),
            'description' => $this->description,
            'receipt_photo_path' => $receiptPath,
            'created_by' => auth()->id(),
        ]);

        $this->resetForm();
        $this->resetValidation();
        $this->showManualModal = false;

        $msg = 'Transaksi Arus Kas berhasil dicatat!';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
    }

    public string $search = '';
    public string $typeFilter = '';
    public string $categoryFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = CashflowTransaction::with(['project', 'creator']);

        if (trim($this->search) !== '') {
            $s = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('description', 'like', $s)
                    ->orWhere('category', 'like', $s)
                    ->orWhereHas('project', function ($pq) use ($s) {
                        $pq->where('name', 'like', $s);
                    })
                    ->orWhereHas('creator', function ($cq) use ($s) {
                        $cq->where('name', 'like', $s);
                    });
            });
        }

        if ($this->typeFilter !== '') {
            $query->where('type', $this->typeFilter);
        }

        if ($this->categoryFilter !== '') {
            if ($this->categoryFilter === 'material') {
                $query->whereIn('category', ['material', 'belanja_material']);
            } elseif ($this->categoryFilter === 'pembelian_lahan') {
                $query->whereIn('category', ['pembelian_lahan', 'lahan_proyek']);
            } elseif ($this->categoryFilter === 'upah_tukang') {
                $query->whereIn('category', ['upah_tukang', 'pembayaran_tukang']);
            } elseif ($this->categoryFilter === 'pengeluaran_lain') {
                $query->whereIn('category', ['pengeluaran_lain', 'lain_lain', 'lainnya']);
            } else {
                $query->where('category', $this->categoryFilter);
            }
        }

        if ($this->view_mode === 'project' || $this->filter_project_id !== '') {
            if ($this->filter_project_id === 'non_project') {
                $query->whereNull('project_id');
            } elseif ($this->filter_project_id) {
                $query->where('project_id', $this->filter_project_id);
            }
        }

        if ($this->view_mode === 'unit' || $this->filter_unit_id) {
            if ($this->filter_unit_id) {
                $selectedUnit = Unit::find($this->filter_unit_id);
                if ($selectedUnit) {
                    $query->where(function ($q) use ($selectedUnit) {
                        $q->where('description', 'like', '%' . $selectedUnit->code . '%');
                    });
                }
            }
        }

        if ($this->filter_month) {
            $parts = explode('-', $this->filter_month);
            if (count($parts) === 2) {
                $query->whereYear('transaction_date', $parts[0])
                      ->whereMonth('transaction_date', $parts[1]);
            }
        }

        $transactions = (clone $query)->latest('transaction_date')->latest('id')->paginate(12);
        $projects = Project::orderBy('name')->get();
        $availableUnits = ($this->filter_project_id && $this->filter_project_id !== 'non_project') 
            ? Unit::where('project_id', $this->filter_project_id)->get() 
            : Unit::all();

        // Global Statistics
        $globalMasuk = CashflowTransaction::where('type', 'masuk')->sum('amount');
        $globalKeluar = CashflowTransaction::where('type', 'keluar')->sum('amount');
        $globalNet = $globalMasuk - $globalKeluar;

        // Current Filter Statistics
        $filteredMasuk = (clone $query)->where('type', 'masuk')->sum('amount');
        $filteredKeluar = (clone $query)->where('type', 'keluar')->sum('amount');
        $filteredNet = $filteredMasuk - $filteredKeluar;

        // Breakdown per project optimized with 1 batch query
        $monthFilter = $this->filter_month;
        $cashflowSumQuery = CashflowTransaction::query();
        if ($monthFilter) {
            $parts = explode('-', $monthFilter);
            if (count($parts) === 2) {
                $cashflowSumQuery->whereYear('transaction_date', $parts[0])->whereMonth('transaction_date', $parts[1]);
            }
        }

        $sumsByProject = $cashflowSumQuery->selectRaw('project_id, type, SUM(amount) as total')
            ->groupBy('project_id', 'type')
            ->get()
            ->groupBy('project_id');

        $projectBreakdown = Project::withCount('units')
            ->get()
            ->map(function ($p) use ($sumsByProject) {
                $projectSums = $sumsByProject->get($p->id, collect());
                $masuk = (float) ($projectSums->where('type', 'masuk')->first()?->total ?? 0);
                $keluar = (float) ($projectSums->where('type', 'keluar')->first()?->total ?? 0);

                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'masuk' => $masuk,
                    'keluar' => $keluar,
                    'net' => $masuk - $keluar,
                ];
            });

        // Add Non-Project / Corporate row to breakdown if present
        $nonProjectSums = $sumsByProject->get('', collect())->merge($sumsByProject->get(null, collect()));
        $npMasuk = (float) ($nonProjectSums->where('type', 'masuk')->first()?->total ?? 0);
        $npKeluar = (float) ($nonProjectSums->where('type', 'keluar')->first()?->total ?? 0);
        if ($npMasuk > 0 || $npKeluar > 0) {
            $projectBreakdown->push([
                'id' => 'non_project',
                'name' => 'Non-Proyek / Kantor Pusat',
                'masuk' => $npMasuk,
                'keluar' => $npKeluar,
                'net' => $npMasuk - $npKeluar,
            ]);
        }

        // 1. Historical Trend Data (6 Months Trend) optimized with 1 batch query
        $trendMonths = collect();
        for ($i = 5; $i >= 0; $i--) {
            $trendMonths->push(now()->subMonths($i)->format('Y-m'));
        }

        $sixMonthsAgo = now()->subMonths(5)->startOfMonth()->toDateString();
        $qM = CashflowTransaction::where('transaction_date', '>=', $sixMonthsAgo);
        if ($this->view_mode === 'project' || $this->filter_project_id !== '') {
            if ($this->filter_project_id === 'non_project') {
                $qM->whereNull('project_id');
            } elseif ($this->filter_project_id) {
                $qM->where('project_id', $this->filter_project_id);
            }
        }

        $monthlyTrendSums = $qM->selectRaw('YEAR(transaction_date) as year_num, MONTH(transaction_date) as month_num, type, SUM(amount) as total')
            ->groupBy('year_num', 'month_num', 'type')
            ->get();

        $chartLabels = [];
        $chartMasuk = [];
        $chartKeluar = [];

        foreach ($trendMonths as $m) {
            $parts = explode('-', $m);
            $year = (int)$parts[0];
            $monthNum = (int)$parts[1];

            $mMasuk = $monthlyTrendSums->first(fn($item) => (int)$item->year_num === $year && (int)$item->month_num === $monthNum && $item->type === 'masuk')?->total ?? 0;
            $mKeluar = $monthlyTrendSums->first(fn($item) => (int)$item->year_num === $year && (int)$item->month_num === $monthNum && $item->type === 'keluar')?->total ?? 0;

            $chartLabels[] = \Carbon\Carbon::createFromFormat('Y-m', $m)->translatedFormat('M Y');
            $chartMasuk[] = (float)$mMasuk;
            $chartKeluar[] = (float)$mKeluar;
        }

        // 2. Category Breakdown Kas Masuk (Sales & Income Breakdown)
        $masukCategories = (clone $query)->where('type', 'masuk')
            ->selectRaw('category, sum(amount) as total_amount')
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->get();

        // 3. Category Breakdown Kas Keluar (Costs & Expenses Breakdown)
        $keluarCategories = (clone $query)->where('type', 'keluar')
            ->selectRaw('category, sum(amount) as total_amount')
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->get();

        // Audit Trail Selected Transaction
        $selectedTransaction = null;
        $auditTrailInfo = null;

        if ($this->selectedTransactionId) {
            $selectedTransaction = CashflowTransaction::with(['project', 'creator'])->find($this->selectedTransactionId);
            if ($selectedTransaction) {
                $auditTrailInfo = $this->resolveAuditTrail($selectedTransaction);
            }
        }

        $this->dispatch('cashflow-chart-updated', [
            'labels' => $chartLabels,
            'masuk' => $chartMasuk,
            'keluar' => $chartKeluar,
        ]);

        return view('livewire.cashflow.index', [
            'transactions' => $transactions,
            'projects' => $projects,
            'availableUnits' => $availableUnits,
            'totalMasuk' => $filteredMasuk,
            'totalKeluar' => $filteredKeluar,
            'netCashflow' => $filteredNet,
            'globalMasuk' => $globalMasuk,
            'globalKeluar' => $globalKeluar,
            'globalNet' => $globalNet,
            'projectBreakdown' => $projectBreakdown,
            'chartLabels' => $chartLabels,
            'chartMasuk' => $chartMasuk,
            'chartKeluar' => $chartKeluar,
            'masukCategories' => $masukCategories,
            'keluarCategories' => $keluarCategories,
            'view_mode' => $this->view_mode,
            'filter_project_id' => $this->filter_project_id,
            'filter_unit_id' => $this->filter_unit_id,
            'filter_month' => $this->filter_month,
            'showManualModal' => $this->showManualModal,
            'showDetailModal' => $this->showDetailModal,
            'showEditModal' => $this->showEditModal,
            'editingTransactionId' => $this->editingTransactionId,
            'selectedTransaction' => $selectedTransaction,
            'auditTrailInfo' => $auditTrailInfo,
            'showImageModal' => $this->showImageModal,
            'imageModalUrl' => $this->imageModalUrl,
            'imageModalTitle' => $this->imageModalTitle,
            'receipt_photo' => $this->receipt_photo,
        ])->layout('components.layouts.app', ['title' => 'Arus Kas Per-Proyek, Per-Unit & Konsolidasi Global']);
    }
}
