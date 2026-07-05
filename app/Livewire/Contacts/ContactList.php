<?php

namespace App\Livewire\Contacts;

use App\Models\Contact;
use Livewire\Component;
use Livewire\WithPagination;

class ContactList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function deleteContact(string $uuid)
    {
        // TODO: Prevent deletion if invoices exist (handle in real app, or soft delete handles it)
        $contact = Contact::where('uuid', $uuid)->firstOrFail();
        $contact->delete();
        $this->dispatch('notify', ['message' => 'Contact deleted successfully']);
    }

    public function render()
    {
        $contacts = Contact::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('display_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.contacts.contact-list', [
            'contacts' => $contacts
        ])->layout('components.layouts.app', ['title' => 'Contacts']);
    }
}
