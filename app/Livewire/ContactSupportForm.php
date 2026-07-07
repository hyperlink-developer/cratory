<?php

namespace App\Livewire;

use App\Mail\SupportContactMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class ContactSupportForm extends Component
{
    public $name = '';
    public $email = '';
    public $message = '';
    public $isSuccess = false;
    
    // Honeypot field
    public $website = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string|max:5000',
        'website' => 'present|max:0', // Must be empty
    ];

    public function submitForm()
    {
        // Simple rate limiting: 3 attempts per IP per minute
        $executed = RateLimiter::attempt(
            'contact-support:' . request()->ip(),
            $maxAttempts = 3,
            function() {
                $this->validate();

                // If honeypot is filled, act like it succeeded but do nothing
                if (!empty($this->website)) {
                    $this->handleSuccess();
                    return;
                }

                // Send Email
                Mail::to('cratory.support@yagneshbhanani.com')->send(
                    new SupportContactMail($this->name, $this->email, $this->message)
                );

                $this->handleSuccess();
            }
        );

        if (! $executed) {
            $seconds = RateLimiter::availableIn('contact-support:' . request()->ip());
            $this->addError('rate_limit', "Too many attempts. Please try again in {$seconds} seconds.");
            
            // Dispatch error event for UI
            $this->dispatch('notify', message: "Too many attempts. Try again later.", type: 'error');
        }
    }
    
    private function handleSuccess()
    {
        $this->isSuccess = true;
        
        $this->dispatch('notify', message: 'Message sent successfully! We will get back to you soon.', type: 'success');
        
        // Reset fields
        $this->reset(['name', 'email', 'message', 'website']);
    }

    public function resetSuccess()
    {
        $this->isSuccess = false;
    }

    public function render()
    {
        return view('livewire.contact-support-form');
    }
}
