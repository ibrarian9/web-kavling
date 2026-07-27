<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActivityLogger
{
    /**
     * Record a system activity log entry.
     */
    public static function log(string $action, string $description, ?object $user = null): ActivityLog
    {
        $currentUser = $user ?? Auth::user();

        $ip = request()->header('X-Forwarded-For') 
            ? trim(explode(',', request()->header('X-Forwarded-For'))[0])
            : (request()->header('X-Real-IP') ?? request()->ip() ?? '127.0.0.1');

        $userAgent = request()->userAgent() ?? request()->header('User-Agent') ?? 'System/Console';

        $log = ActivityLog::create([
            'user_id' => $currentUser?->id,
            'user_name' => $currentUser?->name ?? 'Guest/System',
            'user_role' => $currentUser?->role ?? 'System',
            'action' => strtoupper($action),
            'description' => $description,
            'ip_address' => $ip,
            'user_agent' => substr($userAgent, 0, 500),
        ]);

        // Also write to standard Laravel log file
        Log::info("[" . strtoupper($action) . "] " . ($currentUser ? $currentUser->name . " ({$currentUser->role}): " : '') . $description);

        return $log;
    }
}
