@if(isset($template->signature_type) && $template->signature_type !== 'none')
    <div style="margin-bottom: 5px; min-height: 50px; display: flex; align-items: flex-end; justify-content: {{ $align ?? 'center' }};">
        @if($template->signature_type === 'text' && !empty($template->signature_text))
            <div style="font-family: 'Great Vibes', cursive; font-size: 24px; color: #000;">
                {{ $template->signature_text }}
            </div>
        @elseif($template->signature_type === 'image' && !empty($template->signature_image_path))
            @php
                $sigSrc = str_starts_with($template->signature_image_path, 'http') || str_starts_with($template->signature_image_path, 'data:')
                    ? $template->signature_image_path 
                    : asset('storage/' . $template->signature_image_path);
            @endphp
            <img src="{{ $sigSrc }}" style="max-height: 60px; max-width: 200px;">
        @endif
    </div>
@endif
