<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct(
        private readonly AuditLogRepositoryInterface $auditLogs,
    ) {
    }

    public function index(Request $request)
    {
        $rawLogs = $this->auditLogs->getRecent(100);

        $logs = array_map(function ($log) {
            $createdAt = null;
            if (isset($log['timestamp'])) {
                if (is_object($log['timestamp']) && method_exists($log['timestamp'], 'get')) {
                    $createdAt = $log['timestamp']->get()->format('Y-m-d H:i:s');
                } elseif (is_numeric($log['timestamp'])) {
                    $createdAt = date('Y-m-d H:i:s', $log['timestamp']);
                } else {
                    $createdAt = (string) $log['timestamp'];
                }
            } elseif (isset($log['createdAt'])) {
                $createdAt = (string) $log['createdAt'];
            }

            return [
                'id' => $log['id'] ?? null,
                'createdAt' => $createdAt,
                'action' => $log['action'] ?? 'LOG',
                'description' => $log['description'] ?? '',
                'userEmail' => $log['userEmail'] ?? ($log['performedBy']['name'] ?? 'Sistema'),
                'userId' => $log['userId'] ?? ($log['performedBy']['uid'] ?? null),
                'ipAddress' => $log['ipAddress'] ?? ($log['ip'] ?? 'N/A'),
            ];
        }, $rawLogs);

        return view('admin.audit.index', compact('logs'));
    }
}
