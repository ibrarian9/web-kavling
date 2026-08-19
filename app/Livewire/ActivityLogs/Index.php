<?php

namespace App\Livewire\ActivityLogs;

use App\Models\ActivityLog;
use App\Traits\WithDatePeriodFilter;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithDatePeriodFilter;

    public $activeTab = 'database'; // 'database' (operational), 'notifications', or 'file'
    public $search = '';
    public $actionFilter = '';

    protected $queryString = [
        'activeTab' => ['except' => 'database'],
        'search' => ['except' => ''],
        'actionFilter' => ['except' => ''],
        'datePeriod' => ['except' => 'all'],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses khusus untuk Admin Utama / Supervisor.');
        }
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingActionFilter()
    {
        $this->resetPage();
    }

    public function clearDatabaseLogs()
    {
        if (!auth()->user()->isSuperAdmin()) return;
        
        ActivityLog::query()->delete();
        \App\Services\ActivityLogger::log('SYSTEM_CLEAR_LOGS', auth()->user()->name . ' membersihkan seluruh riwayat Log Aktivitas Sistem di database.');
        session()->flash('success', 'Riwayat Log Aktivitas Database berhasil dibersihkan.');
    }

    public function clearFileLog()
    {
        if (!auth()->user()->isSuperAdmin()) return;

        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, '');
            \App\Services\ActivityLogger::log('SYSTEM_CLEAR_FILE_LOG', auth()->user()->name . ' membersihkan isi file laravel.log.');
            session()->flash('success', 'File storage/logs/laravel.log berhasil dikosongkan.');
        }
    }

    public function clearDeprecationLog()
    {
        if (!auth()->user()->isSuperAdmin()) return;

        $depPath = storage_path('logs/php-deprecation-warnings.log');
        if (File::exists($depPath)) {
            File::put($depPath, '');
            \App\Services\ActivityLogger::log('SYSTEM_CLEAR_DEP_LOG', auth()->user()->name . ' membersihkan isi file log deprecations.');
            session()->flash('success', 'File storage/logs/php-deprecation-warnings.log berhasil dikosongkan.');
        }
    }

    public function render()
    {
        if (!auth()->user() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses khusus untuk Admin Utama / Supervisor.');
        }

        $operationalCount = ActivityLog::where('action', 'not like', 'NOTIF_%')->where('action', 'not like', 'NOTIFICATION_%')->count();
        $notificationCount = ActivityLog::where(function ($q) {
            $q->where('action', 'like', 'NOTIF_%')
              ->orWhere('action', 'like', 'NOTIFICATION_%');
        })->count();

        $logsQuery = ActivityLog::with('user')->latest();

        if ($this->activeTab === 'notifications') {
            $logsQuery->where(function ($q) {
                $q->where('action', 'like', 'NOTIF_%')
                  ->orWhere('action', 'like', 'NOTIFICATION_%');
            });
        } elseif ($this->activeTab === 'database') {
            $logsQuery->where(function ($q) {
                $q->where('action', 'not like', 'NOTIF_%')
                  ->where('action', 'not like', 'NOTIFICATION_%');
            });
        }

        if ($this->search) {
            $logsQuery->where(function ($q) {
                $q->where('user_name', 'like', '%' . $this->search . '%')
                  ->orWhere('user_role', 'like', '%' . $this->search . '%')
                  ->orWhere('action', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->actionFilter) {
            $logsQuery->where('action', $this->actionFilter);
        }

        if ($this->datePeriod !== 'all') {
            $this->applyDatePeriodFilter($logsQuery, 'created_at');
        }

        $databaseLogs = $logsQuery->paginate(25);

        // Parse raw laravel.log lines
        $rawLogLines = [];
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            $content = File::get($logPath);
            $lines = array_filter(explode("\n", $content));
            $lines = array_reverse(array_slice($lines, -300)); // Get last 300 lines

            foreach ($lines as $line) {
                if ($this->search && stripos($line, $this->search) === false) {
                    continue;
                }
                $rawLogLines[] = $line;
            }
        }

        // Parse Deprecations Log lines
        $deprecationLines = [];
        $depPath = storage_path('logs/php-deprecation-warnings.log');
        if (File::exists($depPath)) {
            $depContent = File::get($depPath);
            $depLines = array_filter(explode("\n", $depContent));
            $depLines = array_reverse(array_slice($depLines, -300));
            foreach ($depLines as $line) {
                if ($this->search && stripos($line, $this->search) === false) {
                    continue;
                }
                $deprecationLines[] = $line;
            }
        }
        
        // Also include deprecation entries from laravel.log
        if (File::exists($logPath)) {
            foreach ($rawLogLines as $line) {
                if (stripos($line, 'deprecated') !== false || stripos($line, 'deprecation') !== false) {
                    if (!in_array($line, $deprecationLines)) {
                        $deprecationLines[] = $line;
                    }
                }
            }
        }

        $availableActionsQuery = ActivityLog::query();
        if ($this->activeTab === 'notifications') {
            $availableActionsQuery->where(function ($q) {
                $q->where('action', 'like', 'NOTIF_%')
                  ->orWhere('action', 'like', 'NOTIFICATION_%');
            });
        } elseif ($this->activeTab === 'database') {
            $availableActionsQuery->where(function ($q) {
                $q->where('action', 'not like', 'NOTIF_%')
                  ->where('action', 'not like', 'NOTIFICATION_%');
            });
        }
        $availableActions = $availableActionsQuery->select('action')->distinct()->pluck('action');

        return view('livewire.activity-logs.index', [
            'databaseLogs' => $databaseLogs,
            'rawLogLines' => $rawLogLines,
            'deprecationLines' => $deprecationLines,
            'availableActions' => $availableActions,
            'operationalCount' => $operationalCount,
            'notificationCount' => $notificationCount,
            'datePeriod' => $this->datePeriod,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ])->layout('components.layouts.app', ['title' => 'System Log & Audit Trail - Founder']);
    }
}
