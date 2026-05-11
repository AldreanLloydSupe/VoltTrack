<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminAuditLogController extends Controller
{
    public function index(Request $request)
    {
        $admin = $request->user();

        abort_unless($admin && $admin->isAdmin(), 403);

        $search = trim((string) $request->input('search'));

        $logs = AuditLog::query()
            ->with('admin:id,first_name,last_name,email')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.auditLog', [
            'logs' => $logs,
            'search' => $search,
        ]);
    }
}
