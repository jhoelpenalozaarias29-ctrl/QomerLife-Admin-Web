<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuditLogRepositoryInterface $auditLogRepository
    ) {
    }

    public function index(Request $request)
    {
        $users = [];
        $search = trim($request->query('search', ''));
        $role = strtoupper(trim($request->query('role', '')));
        $status = strtoupper(trim($request->query('status', '')));

        try {
            if ($search !== '') {
                $users = $this->userRepository->search($search);
            } else {
                $users = $this->userRepository->all();
            }

            if ($role !== '') {
                $users = array_filter($users, fn($u) => strtoupper($u->role ?? 'USER') === $role);
            }

            if ($status !== '') {
                $users = array_filter($users, fn($u) => strtoupper($u->status ?? 'ACTIVE') === $status);
            }

            $users = array_values($users);
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
        }

        $totalUsers = count($users);
        $totalPoints = 0;
        $adminCount = 0;
        foreach ($users as $u) {
            $totalPoints += (int)($u->attributes['ecoPoints'] ?? 0);
            if (strtoupper($u->role ?? '') === 'ADMIN') $adminCount++;
        }

        return view('admin.users.index', [
            'breadcrumbs' => ['Dashboard' => route('admin.dashboard'), 'Usuarios' => null],
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'status' => $status,
            'kpis' => [
                'total' => $totalUsers,
                'points' => $totalPoints,
                'admins' => $adminCount,
            ]
        ]);
    }

    public function show(string $uid)
    {
        try {
            $user = $this->userRepository->findByUid($uid);
            if (!$user) {
                abort(404, 'User not found');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching user: ' . $e->getMessage());
            abort(404, 'User not found');
        }

        return view('admin.users.show', [
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard'),
                'Users' => route('admin.users.index'),
                'User Details' => null
            ],
            'user' => $user
        ]);
    }

    public function edit(string $uid)
    {
        try {
            $user = $this->userRepository->findByUid($uid);
            if (!$user) {
                abort(404, 'User not found');
            }
        } catch (\Exception $e) {
            Log::error('Error fetching user for edit: ' . $e->getMessage());
            abort(404, 'User not found');
        }

        return view('admin.users.edit', [
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard'),
                'Users' => route('admin.users.index'),
                'Edit User' => null
            ],
            'user' => $user
        ]);
    }

    public function update(Request $request, string $uid)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string|in:USER,ADMIN,SUPERVISOR',
            'status' => 'required|string|in:ACTIVE,INACTIVE,SUSPENDED',
            'ecoPoints' => 'required|integer|min:0',
            'ecoLevel' => 'required|integer|min:1',
        ]);

        try {
            $oldUser = $this->userRepository->findByUid($uid);
            if (!$oldUser) {
                abort(404, 'User not found');
            }

            $this->userRepository->update($uid, $validated);

            $this->auditLogRepository->log(
                action: 'UPDATE_USER',
                description: "Updated user {$validated['email']}",
                userId: auth()->id() ?? 'system',
                metadata: [
                    'resource' => 'users',
                    'resourceId' => $uid,
                    'oldData' => ['name' => $oldUser->name, 'email' => $oldUser->email, 'role' => $oldUser->role],
                    'newData' => $validated,
                ]
            );

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error updating user.');
        }
    }

    public function destroy(string $uid)
    {
        try {
            $user = $this->userRepository->findByUid($uid);
            if (!$user) {
                abort(404, 'User not found');
            }

            $this->userRepository->delete($uid);

            $this->auditLogRepository->log(
                action: 'DELETE_USER',
                description: "Deleted user {$user->email}",
                userId: auth()->id() ?? 'system',
                metadata: ['resource' => 'users', 'resourceId' => $uid]
            );

            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->route('admin.users.index')->with('error', 'Error deleting user.');
        }
    }
}
