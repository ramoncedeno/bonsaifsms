<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;

class UserManagement extends Component
{

    public $name, $email, $password, $user_id;
    public $isOpen = 0;
    public $isSaving = false;
    public $selectedRoles = [];
    public $verificationSentFor = [];
    public $sendingVerificationEmailFor = null;
    public $passwordResetSentFor = [];
    public $sendingPasswordResetFor = null;

    public function render()
    {
        $users = User::with('roles')->paginate(20);
        $roles = Role::where('name', '!=', 'user')->get();

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
        $this->isSaving = true;

        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'password' => 'required_if:user_id,null',
        ]);

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $userData['password'] = bcrypt($this->password);
        } else if (empty($this->user_id)) { // Only set a default password for new users if not provided
            $userData['password'] = bcrypt('password'); // Default password
        }

        $user = User::updateOrCreate(['id' => $this->user_id], $userData);

        if ($this->user_id == 1) {
            $this->selectedRoles[] = 'admin';
        }

        if (!$this->user_id) { // Only send verification email for newly created users
            $user->sendEmailVerificationNotification();
        }

        $user->syncRoles($this->selectedRoles);

        session()->flash('message', 
            $this->user_id ? 'User Updated Successfully.' : 'User Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();

        $this->isSaving = false;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();

        if ($id == 1) {
            // Ensure admin role is always selected and disable role modification
            $this->selectedRoles = ['admin'];
        }

        $this->openModal();
    }

    public function delete($id)
    {
        if ($id == 1) {
            session()->flash('message', 'The primary user cannot be deleted.');
            return;
        }
        User::find($id)->delete();
        session()->flash('message', 'User Deleted Successfully.');
    }

    public function sendVerificationEmail($userId)
    {
        $this->sendingVerificationEmailFor = $userId;

        $user = User::findOrFail($userId);
        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            $this->verificationSentFor[] = $userId;
            session()->flash('message', 'Verification email sent successfully.');
        } else {
            session()->flash('message', 'Email is already verified.');
        }

        $this->sendingVerificationEmailFor = null;
    }

    public function sendPasswordReset($userId)
    {
        $this->sendingPasswordResetFor = $userId;

        $user = User::findOrFail($userId);
        app('auth.password.broker')->sendResetLink($user->only('email'));

        $this->passwordResetSentFor[] = $userId;
        session()->flash('message', 'Password reset link sent successfully.');

        $this->sendingPasswordResetFor = null;
    }

    public function activateUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->status = User::STATUS_ACTIVE;
        $user->save();
        session()->flash('message', 'User activated successfully.');
    }

    public function suspendUser($userId)
    {
        if ($userId == 1) {
            session()->flash('message', 'The primary user cannot be suspended.');
            return;
        }

        $user = User::findOrFail($userId);

        if ($user->hasRole('admin')) {
            session()->flash('message', 'Cannot suspend an administrator.');
            return;
        }

        $user->status = User::STATUS_SUSPENDED;
        $user->save();
        session()->flash('message', 'User suspended successfully.');
    }
}
