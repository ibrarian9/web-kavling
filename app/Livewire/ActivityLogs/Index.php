<?php

namespace App\Livewire\ActivityLogs;

use App\Models\ActivityLog;
use App\Services\ActivityLogger;
use App\Traits\WithDatePeriodFilter;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithDatePeriodFilter;

    public string $activeTab = 'database'; // 'database' (operational), 'notifications', or 'file'
    public string $search = '';
    public string $actionFilter = '';

    protected $queryString = [
        'activeTab' => ['except' => 'database'],
        'search' => ['except' => ''],
        'actionFilter' => ['except' => ''],
        'datePeriod' => ['except' => 'all'],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function mount(): void
    {
        if (!auth()->user() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses khusus untuk Admin Utama / Supervisor.');
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingActionFilter(): void
    {
        $this->resetPage();
    }

    public function clearFileLog(): void
    {
        if (!auth()->user()->isSuperAdmin()) return;

        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, '');
            ActivityLogger::log('SYSTEM_CLEAR_FILE_LOG', auth()->user()->name . ' membersihkan isi file laravel.log.');
            session()->flash('success', 'File storage/logs/laravel.log berhasil dikosongkan.');
        }
    }

    public function clearDeprecationLog(): void
    {
        if (!auth()->user()->isSuperAdmin()) return;

        $depPath = storage_path('logs/php-deprecation-warnings.log');
        if (File::exists($depPath)) {
            File::put($depPath, '');
            ActivityLogger::log('SYSTEM_CLEAR_DEP_LOG', auth()->user()->name . ' membersihkan isi file log deprecations.');
            session()->flash('success', 'File storage/logs/php-deprecation-warnings.log berhasil dikosongkan.');
        }
    }

    /**
     * Efficiently read the last N lines from a log file without loading whole file into memory.
     */
    private function readLastLogLines(string $filePath, int $maxLines = 300): array
    {
        if (!File::exists($filePath)) {
            return [];
        }

        $lines = [];
        $file = new \SplFileObject($filePath, 'r');
        $file->seek(PHP_INT_MAX);
        $totalLines = $file->key();

        $startLine = max(0, $totalLines - $maxLines);
        $file->seek($startLine);

        while (!$file->eof()) {
            $line = trim($file->fgets());
            if ($line !== '') {
                $lines[] = $line;
            }
        }

        return array_reverse($lines);
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

        // Parse raw laravel.log lines (only if file tab is active or search active)
        $rawLogLines = [];
        if ($this->activeTab === 'file' || $this->search) {
            $logPath = storage_path('logs/laravel.log');
            $lines = $this->readLastLogLines($logPath, 300);

            foreach ($lines as $line) {
                if ($this->search && stripos($line, $this->search) === false) {
                    continue;
                }
                $rawLogLines[] = $line;
            }
        }

        // Parse Deprecations Log lines
        $deprecationLines = [];
        if ($this->activeTab === 'file' || $this->search) {
            $depPath = storage_path('logs/php-deprecation-warnings.log');
            $depLines = $this->readLastLogLines($depPath, 300);

            foreach ($depLines as $line) {
                if ($this->search && stripos($line, $this->search) === false) {
                    continue;
                }
                $deprecationLines[] = $line;
            }
            
            // Also include deprecation entries from laravel.log
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
