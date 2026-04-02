<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class Users extends Component
{
    use WithPagination;

    public string $search = '';

    public string $roleFilter = '';

    public string $statusFilter = '';

    public string $creatorFilter = '';

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public ?int $selectedUserId = null;

    public string $selectedRole = '';

    public string $deactivationReason = '';

    public bool $showRoleModal = false;

    public bool $showDetailModal = false;

    public bool $showDeactivateModal = false;

    public bool $showActivateModal = false;

    public bool $showApproveCreatorModal = false;

    public bool $showRejectCreatorModal = false;

    public bool $showDeleteModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'creatorFilter' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRoleFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedCreatorFilter(): void
    {
        $this->resetPage();
    }

    public function sortByName(): void
    {
        if ($this->sortBy === 'name') {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = 'name';
            $this->sortDirection = 'asc';
        }
    }

    public function sortByCreatedAt(): void
    {
        if ($this->sortBy === 'created_at') {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = 'created_at';
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'roleFilter', 'statusFilter', 'creatorFilter', 'sortBy', 'sortDirection']);
        $this->resetPage();
    }

    public function openRoleModal(int $userId): void
    {
        $this->closeModals();
        $user = User::findOrFail($userId);
        $this->selectedUserId = $userId;
        $this->selectedRole = $user->getRoleNames()->first() ?? 'user';
        $this->showRoleModal = true;
    }

    public function openDetailModal(int $userId): void
    {
        $this->closeModals();
        $this->selectedUserId = $userId;
        $this->showDetailModal = true;
    }

    public function openDeactivateModal(int $userId): void
    {
        $this->closeModals();
        $this->selectedUserId = $userId;
        $this->deactivationReason = '';
        $this->showDeactivateModal = true;
    }

    public function openActivateModal(int $userId): void
    {
        $this->closeModals();
        $this->selectedUserId = $userId;
        $this->showActivateModal = true;
    }

    public function openApproveCreatorModal(int $userId): void
    {
        $this->closeModals();
        $this->selectedUserId = $userId;
        $this->showApproveCreatorModal = true;
    }

    public function openRejectCreatorModal(int $userId): void
    {
        $this->closeModals();
        $this->selectedUserId = $userId;
        $this->showRejectCreatorModal = true;
    }

    public function openDeleteModal(int $userId): void
    {
        $this->closeModals();
        $this->selectedUserId = $userId;
        $this->showDeleteModal = true;
    }

    public function closeModals(): void
    {
        $this->showRoleModal = false;
        $this->showDetailModal = false;
        $this->showDeactivateModal = false;
        $this->showActivateModal = false;
        $this->showApproveCreatorModal = false;
        $this->showRejectCreatorModal = false;
        $this->showDeleteModal = false;
        $this->selectedUserId = null;
        $this->selectedRole = '';
        $this->deactivationReason = '';
    }

    public function saveRole(): void
    {
        $this->validate([
            'selectedRole' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($this->selectedUserId);

        if ($user->id === auth()->id() && $this->selectedRole !== 'admin') {
            $this->dispatch('toast', type: 'error', message: __('ui.cannot_change_own_role'));
            $this->closeModals();

            return;
        }

        $user->syncRoles([$this->selectedRole]);

        $user->update([
            'is_creator' => in_array($this->selectedRole, ['creator', 'admin'], true),
            'creator_requested_at' => null,
        ]);

        $this->dispatch('toast', type: 'success', message: __('ui.role_updated_successfully'));
        $this->closeModals();
    }

    public function deactivateUser(): void
    {
        $this->deactivationReason = trim($this->deactivationReason);

        $this->validate([
            'deactivationReason' => 'required|string|min:3|max:500',
        ]);

        $user = User::findOrFail($this->selectedUserId);

        if ($user->id === auth()->id()) {
            $this->dispatch('toast', type: 'error', message: __('ui.cannot_deactivate_self'));
            $this->closeModals();

            return;
        }

        $user->update([
            'is_active' => false,
            'deactivation_reason' => $this->deactivationReason,
        ]);

        $this->dispatch('toast', type: 'success', message: __('ui.user_deactivated_successfully'));
        $this->closeModals();
    }

    public function activateUser(): void
    {
        $user = User::findOrFail($this->selectedUserId);

        $user->update([
            'is_active' => true,
            'deactivation_reason' => null,
        ]);

        $this->dispatch('toast', type: 'success', message: __('ui.user_activated_successfully'));
        $this->closeModals();
    }

    public function approveCreator(): void
    {
        $user = User::findOrFail($this->selectedUserId);
        $user->update([
            'creator_requested_at' => null,
            'is_creator' => true,
        ]);
        $user->assignRole('creator');
        $this->dispatch('toast', type: 'success', message: __('ui.creator_approved_successfully'));
        $this->closeModals();
    }

    public function rejectCreator(): void
    {
        $user = User::findOrFail($this->selectedUserId);
        $user->update([
            'creator_requested_at' => null,
            'is_creator' => false,
        ]);
        $this->dispatch('toast', type: 'success', message: __('ui.creator_request_rejected'));
        $this->closeModals();
    }

    public function deleteUser(): void
    {
        $user = User::findOrFail($this->selectedUserId);

        if ($user->id === auth()->id()) {
            $this->dispatch('toast', type: 'error', message: __('ui.cannot_delete_self'));
            $this->closeModals();

            return;
        }

        if ($user->hasRole('admin')) {
            $adminCount = User::role('admin')->count();
            if ($adminCount <= 1) {
                $this->dispatch('toast', type: 'error', message: __('ui.cannot_delete_last_admin'));
                $this->closeModals();

                return;
            }
        }

        $user->delete();
        $this->dispatch('toast', type: 'success', message: __('ui.user_deleted_successfully'));
        $this->closeModals();
    }

    public function getUsersProperty(): LengthAwarePaginator
    {
        return User::query()
            ->withCount(['posts', 'courses', 'resources'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->role($this->roleFilter);
            })
            ->when($this->statusFilter === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($this->statusFilter === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->when($this->creatorFilter === 'pending', function ($query) {
                $query->whereNotNull('creator_requested_at');
            })
            ->when($this->creatorFilter === 'approved', function ($query) {
                $query->role('creator');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);
    }

    public function getRolesProperty(): Collection
    {
        return Role::all();
    }

    public function getStatsProperty(): array
    {
        return [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'creators' => User::role('creator')->count(),
            'pending' => User::whereNotNull('creator_requested_at')->count(),
        ];
    }

    public function getSelectedUserProperty(): ?User
    {
        if (! $this->selectedUserId) {
            return null;
        }

        return User::withCount(['posts', 'courses', 'resources'])
            ->with(['roles', 'permissions'])
            ->find($this->selectedUserId);
    }

    public function render()
    {
        return view('livewire.admin.users', [
            'users' => $this->users,
            'roles' => $this->roles,
            'stats' => $this->stats,
            'selectedUser' => $this->selectedUser,
        ]);
    }
}
