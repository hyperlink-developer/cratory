<?php

namespace App\Livewire\Settings;

use App\Enums\OrgUserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class UserManagement extends Component
{
    public $users = [];
    public $showUserModal = false;
    public $editMode = false;
    public $editingUserId = null;

    public $name = '';
    public $email = '';
    public $password = '';
    public $role = '';

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string|in:' . implode(',', array_map(fn($r) => $r->value, OrgUserRole::cases())),
        ];

        if (!$this->editMode) {
            $rules['password'] = 'nullable|string|min:8';
        }

        return $rules;
    }

    public function mount()
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $org = auth()->user()->currentOrganization;
        $this->users = $org->users()->withPivot('role')->get();
    }

    public function openAddModal()
    {
        $this->reset(['name', 'email', 'password', 'role', 'editMode', 'editingUserId']);
        $this->role = OrgUserRole::Staff->value;
        $this->showUserModal = true;
    }

    public function openEditModal($userId)
    {
        $org = auth()->user()->currentOrganization;
        $user = $org->users()->where('users.id', $userId)->first();
        
        if ($user) {
            $this->editMode = true;
            $this->editingUserId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->pivot->role;
            $this->showUserModal = true;
        }
    }

    public function saveUser()
    {
        $this->validate();
        
        $org = auth()->user()->currentOrganization;

        if ($this->editMode) {
            // Update role in pivot
            $org->users()->updateExistingPivot($this->editingUserId, [
                'role' => $this->role
            ]);
            
            // Optionally update user details
            User::where('id', $this->editingUserId)->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            $this->dispatch('notify', message: 'User updated successfully!');
        } else {
            // Check if user exists by email
            $user = User::where('email', $this->email)->first();

            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password ?: 'password'), // default password if none provided
                    'is_commander' => false,
                ]);
            }

            // Attach to organization if not already
            if (!$org->users()->where('users.id', $user->id)->exists()) {
                $org->users()->attach($user->id, [
                    'role' => $this->role,
                    'is_default_org' => !$user->hasOrganizations(),
                    'status' => 'active',
                ]);
                $this->dispatch('notify', message: 'User added to organization!');
            } else {
                $this->dispatch('notify', message: 'User is already in this organization.');
            }
        }

        $this->showUserModal = false;
        $this->loadUsers();
    }

    public function removeUser($userId)
    {
        $org = auth()->user()->currentOrganization;
        
        if (auth()->id() == $userId) {
            $this->dispatch('notify', message: 'You cannot remove yourself.');
            return;
        }
        
        $org->users()->detach($userId);
        $this->dispatch('notify', message: 'User removed from organization.');
        $this->loadUsers();
    }

    public function render()
    {
        return view('livewire.settings.user-management');
    }
}
