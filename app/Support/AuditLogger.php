<?php

namespace App\Support;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogger
{
    public static function log(
        ?User $admin,
        string $action,
        string $description,
        array $metadata = [],
        ?string $module = null,
        ?Request $request = null
    ): void {
        AuditLog::create([
            'admin_id' => $admin?->id,
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
