<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\RedemptionRepositoryInterface;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use Illuminate\Support\Facades\Log;

class RedemptionController extends Controller
{
    public function __construct(
        private readonly RedemptionRepositoryInterface $redemptionRepository,
        private readonly AuditLogRepositoryInterface $auditLogRepository
    ) {
    }

    public function index()
    {
        $redemptions = [];
        try {
            $redemptions = $this->redemptionRepository->all();
        } catch (\Exception $e) {
            Log::error('Error fetching redemptions: ' . $e->getMessage());
        }

        return view('admin.redemptions.index', [
            'breadcrumbs' => ['Dashboard' => route('admin.dashboard'), 'Redemptions' => null],
            'redemptions' => $redemptions
        ]);
    }

    public function show(string $id)
    {
        try {
            $redemption = $this->redemptionRepository->find($id);
            if (!$redemption) {
                abort(404, 'Redemption not found');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching redemption: ' . $e->getMessage());
            abort(404, 'Redemption not found');
        }

        return view('admin.redemptions.show', [
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard'),
                'Redemptions' => route('admin.redemptions.index'),
                'Redemption Details' => null
            ],
            'redemption' => $redemption
        ]);
    }

    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:PENDING,APPROVED,REJECTED'
        ]);

        try {
            $oldRedemption = $this->redemptionRepository->find($id);
            if (!$oldRedemption) {
                abort(404, 'Redemption not found');
            }

            $this->redemptionRepository->updateStatus($id, $validated['status']);

            $this->auditLogRepository->log(
                action: 'UPDATE_REDEMPTION_STATUS',
                description: "Updated redemption status to {$validated['status']}",
                userId: auth()->id() ?? 'system',
                metadata: [
                    'resource' => 'redemptions',
                    'resourceId' => $id,
                    'oldData' => ['status' => $oldRedemption['status'] ?? 'UNKNOWN'],
                    'newData' => ['status' => $validated['status']],
                ]
            );

            return redirect()->route('admin.redemptions.index')->with('success', 'Redemption status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating redemption status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating redemption status.');
        }
    }
}
