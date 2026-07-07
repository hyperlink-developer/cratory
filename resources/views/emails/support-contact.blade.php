<x-mail::message>
# New Support Request

You have received a new support request from your website's contact form.

**Name:** {{ $name }}  
**Email:** {{ $email }}

**Message:**
<x-mail::panel>
{{ $messageContent }}
</x-mail::panel>

You can reply directly to this email to respond to the user.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
