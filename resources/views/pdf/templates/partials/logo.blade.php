@if((isset($template->show_logo) ? $template->show_logo : true) && !empty($organization->logo_path))
    @php
        $logoSrc = str_starts_with($organization->logo_path, 'http') || str_starts_with($organization->logo_path, 'data:')
            ? $organization->logo_path 
            : asset('storage/' . $organization->logo_path);
    @endphp
    <img src="{{ $logoSrc }}" style="max-height: 80px; max-width: 250px; margin-bottom: 5px;">
@else
    @if(isset($fallbackClass))
        <div class="{{ $fallbackClass }}">{{ $organization->name }}</div>
    @elseif(isset($fallbackTag) && $fallbackTag === 'h2')
        <h2>{{ $organization->name }}</h2>
    @else
        <h2 style="margin-top: 0; margin-bottom: 5px;">{{ $organization->name }}</h2>
    @endif
@endif
