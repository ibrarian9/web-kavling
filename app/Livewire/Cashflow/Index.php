<?php

namespace App\Livewire\Cashflow;

use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $filter_project_id = '';
    public $filter_unit_id = '';
    public $filter_month = ''; // format YYYY-MM
    public $view_mode = 'global'; // 'global', 'project', atau 'unit'
    public $showManualModal = false;

    // Audit Trail Detail Modal
    public $showDetailModal = false;
    public $selectedTransactionId = null;

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
        if (Project::count() > 0) {
            $this->project_id = Project::first()->id;
        }
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
        if (!$user || !$user->isFounder()) {
            session()->flash('error', 'Hanya Founder yang berhak menghapus data transaksi arus kas.');
            return;
        }

        $trx = CashflowTransaction::findOrFail($id);
        $trxId = $trx->id;
        $description = $trx->description;
        $amount = number_format($trx->amount, 0, ',', '.');

        $trx->delete();

        \App\Services\ActivityLogger::log(
            'CASHFLOW_DELETED',
            "Founder menghapus transaksi arus kas #TRX-{$trxId}: {$description} (Rp {$amount})"
        );

        session()->flash('success', "Transaksi mutasi kas #TRX-{$trxId} berhasil dihapus.");
    }

    private function resolveAuditTrail(CashflowTransaction $t): array
    {
        // 1. Source Menu Determination
        $sourceMenu = '📝 Input Direct Modul Arus Kas';
        if ($t->reference_type) {
            if ($t->reference_type === \App\Models\ManualInvoice::class) {
                $sourceMenu = '📄 Menu Invoice Manual / Kuitansi';
            } elseif ($t->reference_type === \App\Models\InstallmentPayment::class) {
                $sourceMenu = '💳 Menu Cicilan Pembeli & Setoran Unit';
            } elseif ($t->reference_type === \App\Models\Booking::class) {
                $sourceMenu = '📌 Menu Booking Unit & Penjualan';
            } elseif ($t->reference_type === \App\Models\ProjectPayment::class) {
                $sourceMenu = '🏗️ Menu Detail Proyek (Lahan & Operasional)';
            } elseif ($t->reference_type === \App\Models\WorkerSalaryPayment::class) {
                $sourceMenu = '👷 Menu Penggajian Worker / Detail Unit';
            }
        } else {
            $descLower = strtolower($t->description ?? '');
            if (str_contains($descLower, 'material') || str_contains($descLower, 'semen') || str_contains($descLower, 'pasir') || str_contains($descLower, 'bata')) {
                $sourceMenu = '🧱 Menu Detail Unit (Belanja Material)';
            } elseif (str_contains($descLower, 'lapangan') || str_contains($descLower, 'operasional')) {
                $sourceMenu = '🛠️ Menu Pengeluaran Lapangan / Operasional';
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
        $this->showManualModal = true;
    }

    public function resetForm()
    {
        $this->type = 'masuk';
        $this->category = 'operasional';
        $this->amount = 0;
        $this->transaction_date = date('Y-m-d');
        $this->description = '';
    }

    public function saveTransaction()
    {
        $this->validate([
            'project_id' => 'required|exists:projects,id',
            'type' => 'required|in:masuk,keluar',
            'amount' => 'required|numeric|min:1000',
            'description' => 'required|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        CashflowTransaction::create([
            'project_id' => $this->project_id,
            'type' => $this->type,
            'category' => $this->category,
            'amount' => $this->amount,
            'transaction_date' => $this->transaction_date,
            'description' => $this->description,
            'created_by' => auth()->id(),
        ]);

        session()->flash('success', 'Transaksi Arus Kas berhasil dicatat!');
        $this->showManualModal = false;
    }

    public function render()
    {
        $query = CashflowTransaction::with(['project', 'creator']);

        if ($this->view_mode === 'project' && $this->filter_project_id) {
            $query->where('project_id', $this->filter_project_id);
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
        $availableUnits = $this->filter_project_id ? Unit::where('project_id', $this->filter_project_id)->get() : Unit::all();

        // Global Statistics
        $globalMasuk = CashflowTransaction::where('type', 'masuk')->sum('amount');
        $globalKeluar = CashflowTransaction::where('type', 'keluar')->sum('amount');
        $globalNet = $globalMasuk - $globalKeluar;

        // Current Filter Statistics
        $filteredMasuk = (clone $query)->where('type', 'masuk')->sum('amount');
        $filteredKeluar = (clone $query)->where('type', 'keluar')->sum('amount');
        $filteredNet = $filteredMasuk - $filteredKeluar;

        // Breakdown per project
        $monthFilter = $this->filter_month;
        $projectBreakdown = Project::withCount('units')
            ->get()
            ->map(function ($p) use ($monthFilter) {
                $masukQ = CashflowTransaction::where('project_id', $p->id)->where('type', 'masuk');
                $keluarQ = CashflowTransaction::where('project_id', $p->id)->where('type', 'keluar');

                if ($monthFilter) {
                    $parts = explode('-', $monthFilter);
                    if (count($parts) === 2) {
                        $masukQ->whereYear('transaction_date', $parts[0])->whereMonth('transaction_date', $parts[1]);
                        $keluarQ->whereYear('transaction_date', $parts[0])->whereMonth('transaction_date', $parts[1]);
                    }
                }

                $masuk = $masukQ->sum('amount');
                $keluar = $keluarQ->sum('amount');
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'masuk' => $masuk,
                    'keluar' => $keluar,
                    'net' => $masuk - $keluar,
                ];
            });

        // 1. Historical Trend Data (6 Months Trend)
        $trendMonths = collect();
        for ($i = 5; $i >= 0; $i--) {
            $trendMonths->push(now()->subMonths($i)->format('Y-m'));
        }

        $chartLabels = [];
        $chartMasuk = [];
        $chartKeluar = [];

        foreach ($trendMonths as $m) {
            $parts = explode('-', $m);
            $year = $parts[0];
            $monthNum = $parts[1];

            $qM = CashflowTransaction::query();
            if ($this->view_mode === 'project' && $this->filter_project_id) {
                $qM->where('project_id', $this->filter_project_id);
            }
            $qM->whereYear('transaction_date', $year)->whereMonth('transaction_date', $monthNum);

            $mMasuk = (clone $qM)->where('type', 'masuk')->sum('amount');
            $mKeluar = (clone $qM)->where('type', 'keluar')->sum('amount');

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
            'selectedTransaction' => $selectedTransaction,
            'auditTrailInfo' => $auditTrailInfo,
        ])->layout('components.layouts.app', ['title' => 'Arus Kas Per-Proyek, Per-Unit & Konsolidasi Global']);
    }
}
