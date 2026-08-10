<?php

namespace App\Livewire\ActivityLogs;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $activeTab = 'database'; // 'database' or 'file'
    public $search = '';
    public $actionFilter = '';
    public $dateFilter = '';

    protected $queryString = [
        'activeTab' => ['except' => 'database'],
        'search' => ['except' => ''],
        'actionFilter' => ['except' => ''],
    ];

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->isFounder()) {
            abort(403, 'Akses khusus untuk Founder Executive.');
        }
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
        if (!auth()->user()->isFounder()) return;
        
        ActivityLog::truncate();
        \App\Services\ActivityLogger::log('SYSTEM_CLEAR_LOGS', 'Founder membersihkan seluruh riwayat Log Aktivitas Sistem di database.');
        session()->flash('success', 'Riwayat Log Aktivitas Database berhasil dibersihkan.');
    }

    public function clearFileLog()
    {
        if (!auth()->user()->isFounder()) return;

        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, '');
            \App\Services\ActivityLogger::log('SYSTEM_CLEAR_FILE_LOG', 'Founder membersihkan isi file laravel.log.');
            session()->flash('success', 'File storage/logs/laravel.log berhasil dikosongkan.');
        }
    }

    public function clearDeprecationLog()
    {
        if (!auth()->user()->isFounder()) return;

        $depPath = storage_path('logs/php-deprecation-warnings.log');
        if (File::exists($depPath)) {
            File::put($depPath, '');
            \App\Services\ActivityLogger::log('SYSTEM_CLEAR_DEP_LOG', 'Founder membersihkan isi file log deprecations.');
            session()->flash('success', 'File storage/logs/php-deprecation-warnings.log berhasil dikosongkan.');
        }
    }

    public function render()
    {
        $logsQuery = ActivityLog::with('user')->latest();

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

        $availableActions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('livewire.activity-logs.index', [
            'databaseLogs' => $databaseLogs,
            'rawLogLines' => $rawLogLines,
            'deprecationLines' => $deprecationLines,
            'availableActions' => $availableActions,
        ])->layout('components.layouts.app', ['title' => 'System Log & Audit Trail - Founder']);
    }
}
