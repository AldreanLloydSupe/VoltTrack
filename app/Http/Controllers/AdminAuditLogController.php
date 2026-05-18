<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

/**
 * Displays admin audit logs with optional search filters.
 */
class AdminAuditLogController extends Controller
{
    /**
     * Shows paginated audit log entries for admins.
     */
    public function index(Request $request)
    {
        $admin = $request->user();

        abort_unless($admin && $admin->isAdmin(), 403);

        $search = trim((string) $request->input('search'));

        // Build searchable audit log query (action, description, and module).
        $logs = AuditLog::query()
            ->with('admin:id,first_name,last_name,email')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.auditLog', [
            'logs' => $logs,
            'search' => $search,
        ]);
    }
}
