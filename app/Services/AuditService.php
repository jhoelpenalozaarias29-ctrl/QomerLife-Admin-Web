<?php

namespace App\Services;

use App\Repositories\Contracts\AuditLogRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    public function __construct(
        private readonly AuditLogRepositoryInterface $auditLogs,
    ) {
    }

    public function log(string $action, string $description, ?array $metadata = null): void
    {
        $user = Auth::user();
        
        $this->auditLogs->log(
            action: $action,
            description: $description,
            userId: $user?->getAuthIdentifier(),
            metadata: $metadata,
        );
    }
}
