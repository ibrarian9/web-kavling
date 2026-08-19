<?php

namespace App\Livewire\DailyActivityReports;

use App\Models\DailyActivityReport;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Traits\WithDatePeriodFilter;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithDatePeriodFilter;

    // Filters
    public string $search = '';
    public ?int $filter_user_id = null;
    public ?int $filter_project_id = null;
    public string $filter_lead_stage = '';
    public string $filter_lead_source = '';
    public string $filter_start_date = '';
    public string $filter_end_date = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filter_user_id' => ['except' => null],
        'filter_project_id' => ['except' => null],
        'filter_lead_stage' => ['except' => ''],
        'filter_lead_source' => ['except' => ''],
        'datePeriod' => ['except' => 'all'],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    // Modal Form & Detail State
    public bool $showReportModal = false;
    public bool $showDetailModal = false;
    public ?DailyActivityReport $selectedReport = null;
    public ?int $editingReportId = null;

    // Export PDF & Viewer State
    public bool $showExportPdfModal = false;
    public bool $showViewerModal = false;
    public string $viewerUrl = '';
    public string $viewerTitle = '';

    public string $export_period = 'month'; // 'day', 'week', 'month', 'all'
    public string $export_date = '';
    public string $export_week_start = '';
    public string $export_week_end = '';
    public string $export_month = '';
    public ?int $export_user_id = null;
    public ?int $export_project_id = null;
    public string $export_lead_stage = '';
    public string $export_lead_source = '';

    public function openExportPdfModal(): void
    {
        $this->export_period = 'month';
        $this->export_date = now()->toDateString();
        $this->export_week_start = now()->startOfWeek()->toDateString();
        $this->export_week_end = now()->endOfWeek()->toDateString();
        $this->export_month = now()->format('Y-m');
        $this->export_user_id = $this->filter_user_id;
        $this->export_project_id = $this->filter_project_id;
        $this->export_lead_stage = $this->filter_lead_stage;
        $this->export_lead_source = $this->filter_lead_source;
        $this->showExportPdfModal = true;
    }

    public function closeExportPdfModal(): void
    {
        $this->showExportPdfModal = false;
    }

    public function openViewerModal(string $url, string $title = ''): void
    {
        $this->viewerUrl = $url;
        $this->viewerTitle = $title ?: 'Pratinjau PDF Laporan Aktivitas Harian';
        $this->showViewerModal = true;
    }

    public function closeViewerModal(): void
    {
        $this->showViewerModal = false;
        $this->viewerUrl = '';
        $this->viewerTitle = '';
    }

    public function getExportPdfUrlProperty(): string
    {
        $params = [
            'period' => $this->export_period,
            'date' => $this->export_date,
            'week_start' => $this->export_week_start,
            'week_end' => $this->export_week_end,
            'month' => $this->export_month,
            'user_id' => $this->export_user_id,
            'project_id' => $this->export_project_id,
            'lead_stage' => $this->export_lead_stage,
            'lead_source' => $this->export_lead_source,
            'search' => $this->search,
            'filter_start_date' => $this->filter_start_date,
            'filter_end_date' => $this->filter_end_date,
        ];

        return route('daily-activity-reports.export-pdf', array_filter($params, fn($v) => !is_null($v) && $v !== ''));
    }

    public ?int $user_id = null;
    public ?int $project_id = null;
    public ?int $unit_id = null;
    public string $report_date = '';
    public string $client_name = '';
    public string $client_phone = '';
    public string $lead_source = 'whatsapp';
    public string $interaction_type = 'chat_wa';
    public string $lead_stage = 'warm';
    public string $payment_type = 'tanpa_dp';
    public $deal_amount = 0;
    public string $notes = '';
    public string $follow_up_date = '';

    public function mount(): void
    {
        $user = auth()->user();
        if (!$user->isAdminOrFounder() && !$user->isSupervisor() && !$user->isMarketing() && !$user->isFinance()) {
            abort(403, 'Akses ditolak ke Daily Activity Report.');
        }

        $this->report_date = now()->toDateString();
        $this->user_id = auth()->id();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->filter_user_id = null;
        $this->filter_project_id = null;
        $this->filter_lead_stage = '';
        $this->filter_lead_source = '';
        $this->filter_start_date = '';
        $this->filter_end_date = '';
        $this->datePeriod = 'all';
        $this->startDate = '';
        $this->endDate = '';
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetReportForm();
        $this->showReportModal = true;
    }

    public function resetReportForm(): void
    {
        $this->resetValidation();
        $this->editingReportId = null;
        $this->user_id = auth()->id();
        $this->project_id = null;
        $this->unit_id = null;
        $this->report_date = now()->toDateString();
        $this->client_name = '';
        $this->client_phone = '';
        $this->lead_source = 'whatsapp';
        $this->interaction_type = 'chat_wa';
        $this->lead_stage = 'warm';
        $this->payment_type = 'tanpa_dp';
        $this->deal_amount = 0;
        $this->notes = '';
        $this->follow_up_date = '';
    }

    public function editReport(int $id): void
    {
        $report = DailyActivityReport::findOrFail($id);
        $user = auth()->user();

        if (!$user->isAdminOrFounder() && !$user->isSupervisor() && $report->user_id !== $user->id) {
            $msg = 'Anda hanya dapat mengedit laporan aktivitas harian Anda sendiri.';
            session()->flash('error', $msg);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Gagal!', 'message' => $msg]);
            return;
        }

        $this->resetValidation();
        $this->editingReportId = $report->id;
        $this->user_id = $report->user_id;
        $this->project_id = $report->project_id;
        $this->unit_id = $report->unit_id;
        $this->report_date = $report->report_date ? $report->report_date->format('Y-m-d') : now()->toDateString();
        $this->client_name = $report->client_name;
        $this->client_phone = $report->client_phone;
        $this->lead_source = $report->lead_source;
        $this->interaction_type = $report->interaction_type;
        $this->lead_stage = $report->lead_stage;
        $this->payment_type = $report->payment_type;
        $this->deal_amount = (float)$report->deal_amount;
        $this->notes = $report->notes ?? '';
        $this->follow_up_date = $report->follow_up_date ? $report->follow_up_date->format('Y-m-d') : '';

        $this->showReportModal = true;
    }

    public function showReportDetail(int $id): void
    {
        $this->selectedReport = DailyActivityReport::with(['user', 'project', 'unit'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedReport = null;
    }

    public function saveReport(): void
    {
        $user = auth()->user();

        $this->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:50',
            'report_date' => 'required|date',
            'lead_source' => 'required|string',
            'interaction_type' => 'required|string',
            'lead_stage' => 'required|string',
            'payment_type' => 'required|string',
            'deal_amount' => 'nullable|numeric|min:0',
        ]);

        $assignedUserId = ($user->isAdminOrFounder() || $user->isSupervisor()) && $this->user_id ? $this->user_id : auth()->id();

        $payload = [
            'user_id' => $assignedUserId,
            'project_id' => $this->project_id ?: null,
            'unit_id' => $this->unit_id ?: null,
            'report_date' => $this->report_date,
            'client_name' => $this->client_name,
            'client_phone' => $this->client_phone,
            'lead_source' => $this->lead_source,
            'interaction_type' => $this->interaction_type,
            'lead_stage' => $this->lead_stage,
            'payment_type' => $this->payment_type,
            'deal_amount' => (float)$this->deal_amount,
            'notes' => $this->notes ?: null,
            'follow_up_date' => $this->follow_up_date ?: null,
        ];

        if ($this->editingReportId) {
            $report = DailyActivityReport::findOrFail($this->editingReportId);
            if (!$user->isAdminOrFounder() && !$user->isSupervisor() && $report->user_id !== $user->id) {
                $msg = 'Akses ditolak.';
                session()->flash('error', $msg);
                $this->dispatch('notify', ['type' => 'error', 'title' => 'Gagal!', 'message' => $msg]);
                return;
            }
            $report->update($payload);
            ActivityLogger::log('DAILY_REPORT_UPDATED', "Laporan aktivitas harian prospek '{$this->client_name}' diperbarui oleh " . auth()->user()->name);
            $msg = 'Laporan aktivitas harian prospek ' . $this->client_name . ' berhasil diperbarui!';
        } else {
            $report = DailyActivityReport::create($payload);
            ActivityLogger::log('DAILY_REPORT_CREATED', "Laporan aktivitas harian prospek '{$this->client_name}' dicatat oleh " . auth()->user()->name);
            $msg = 'Laporan aktivitas harian prospek ' . $this->client_name . ' berhasil disimpan!';
        }

        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Berhasil!', 'message' => $msg]);
        $this->showReportModal = false;
        $this->resetReportForm();
    }

    public function deleteReport(int $id): void
    {
        $user = auth()->user();
        $report = DailyActivityReport::findOrFail($id);

        if (!$user->isFounder() && !$user->isSupervisor() && $report->user_id !== $user->id) {
            $msg = 'Hanya Founder, Supervisor, atau sales bersangkutan yang berhak menghapus laporan.';
            session()->flash('error', $msg);
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Gagal!', 'message' => $msg]);
            return;
        }

        $clientName = $report->client_name;
        $report->delete();

        ActivityLogger::log('DAILY_REPORT_DELETED', "Laporan aktivitas harian prospek '{$clientName}' (ID #{$id}) dihapus oleh " . auth()->user()->name);

        $msg = 'Laporan aktivitas harian prospek ' . $clientName . ' berhasil dihapus.';
        session()->flash('success', $msg);
        $this->dispatch('notify', ['type' => 'success', 'title' => 'Dihapus!', 'message' => $msg]);
    }

    public function render()
    {
        $user = auth()->user();
        $query = DailyActivityReport::with(['user', 'project', 'unit']);

        // Non-founder/non-supervisor marketing users see their own reports by default, unless Founder or Admin
        if (!$user->isAdminOrFounder() && !$user->isSupervisor() && $user->isMarketing()) {
            $query->where('user_id', $user->id);
        } elseif ($this->filter_user_id) {
            $query->where('user_id', $this->filter_user_id);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('client_name', 'like', '%' . $this->search . '%')
                  ->orWhere('client_phone', 'like', '%' . $this->search . '%')
                  ->orWhere('notes', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filter_project_id) {
            $query->where('project_id', $this->filter_project_id);
        }

        if ($this->filter_lead_stage) {
            $query->where('lead_stage', $this->filter_lead_stage);
        }

        if ($this->filter_lead_source) {
            $query->where('lead_source', $this->filter_lead_source);
        }

        if ($this->datePeriod !== 'all') {
            $this->applyDatePeriodFilter($query, 'report_date');
        } elseif ($this->filter_start_date || $this->filter_end_date) {
            if ($this->filter_start_date) {
                $query->whereDate('report_date', '>=', $this->filter_start_date);
            }
            if ($this->filter_end_date) {
                $query->whereDate('report_date', '<=', $this->filter_end_date);
            }
        }

        $reports = $query->latest('report_date')->latest('id')->paginate(15);

        // Stats summary calculation
        $baseStatsQuery = DailyActivityReport::query();
        if (!$user->isAdminOrFounder() && !$user->isSupervisor() && $user->isMarketing()) {
            $baseStatsQuery->where('user_id', $user->id);
        }

        $totalReportsCount = (clone $baseStatsQuery)->count();
        $todayReportsCount = (clone $baseStatsQuery)->whereDate('report_date', now()->toDateString())->count();
        $hotDealsCount = (clone $baseStatsQuery)->whereIn('lead_stage', ['hot_deal', 'booking', 'cash_lunas'])->count();
        $totalDealVolume = (clone $baseStatsQuery)->whereIn('lead_stage', ['booking', 'cash_lunas'])->sum('deal_amount');

        // Lead source distribution
        $topLeadSources = (clone $baseStatsQuery)
            ->selectRaw('lead_source, count(*) as count')
            ->groupBy('lead_source')
            ->orderByDesc('count')
            ->limit(3)
            ->get();

        $allProjects = Project::where('status', 'aktif')->orderBy('name')->get();
        $allMarketingUsers = User::whereIn('role', ['marketing', 'founder', 'supervisor'])->orderBy('name')->get();
        
        $availableUnits = collect();
        if ($this->project_id) {
            $availableUnits = Unit::where('project_id', $this->project_id)->orderBy('code')->get();
        }

        return view('livewire.daily-activity-reports.index', [
            'reports' => $reports,
            'totalReportsCount' => $totalReportsCount,
            'todayReportsCount' => $todayReportsCount,
            'hotDealsCount' => $hotDealsCount,
            'totalDealVolume' => $totalDealVolume,
            'topLeadSources' => $topLeadSources,
            'allProjects' => $allProjects,
            'allMarketingUsers' => $allMarketingUsers,
            'availableUnits' => $availableUnits,
        ])->layout('components.layouts.app', ['title' => 'Daily Activity Report - Laporan Harian Marketing']);
    }
}
