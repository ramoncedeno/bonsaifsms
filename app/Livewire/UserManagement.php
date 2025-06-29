<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;

class UserManagement extends Component
{
    protected static $layout = 'layouts.app';
    use WithPagination;

    public $selectedRoles = [];

    public function mount()
    {
        $this->selectedRoles = User::with('roles')->get()->keyBy('id')->map(function ($user) {
            return $user->roles->pluck('name')->toArray();
        })->toArray();
    }

    public function assignRole(User $user, $roleName)
    {
        $user->syncRoles([$roleName]);
        session()->flash('message', 'Role assigned successfully.');
    }

    public function render()
    {
        $users = User::with('roles')->paginate(10);
        $roles = Role::all();

        return view('livewire.user-management', [
            'users' => $users,
            'roles' => $roles,
        ])->layout('layouts.app');
    }
}
