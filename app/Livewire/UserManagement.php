<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $name, $email, $password, $user_id;
    public $isOpen = 0;
    public $selectedRoles = [];

    public function render()
    {
        $users = User::with('roles')->paginate(10);
        $roles = Role::all();

        return view('livewire.user-management', [
            'users' => $users,
            'roles' => $roles,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields(){
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->user_id = '';
        $this->selectedRoles = [];
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'password' => 'required_if:user_id,null',
        ]);

        $user = User::updateOrCreate(['id' => $this->user_id], [
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        $user->syncRoles($this->selectedRoles);

        session()->flash('message', 
            $this->user_id ? 'User Updated Successfully.' : 'User Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();

        $this->openModal();
    }

    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'User Deleted Successfully.');
    }

    public function sendVerificationEmail($userId)
    {
        $user = User::findOrFail($userId);
        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            session()->flash('message', 'Verification email sent successfully.');
        } else {
            session()->flash('message', 'Email is already verified.');
        }
    }

    public function sendPasswordReset($userId)
    {
        $user = User::findOrFail($userId);
        app('auth.password.broker')->sendResetLink($user->only('email'));
        session()->flash('message', 'Password reset link sent successfully.');
    }
}
