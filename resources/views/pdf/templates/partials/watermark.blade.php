@if(isset($template->watermark_type) && $template->watermark_type !== 'none')
    @php
        $watermarkSrc = null;
        if ($template->watermark_type === 'image' && !empty($template->watermark_image_path)) {
            $watermarkSrc = str_starts_with($template->watermark_image_path, 'http') || str_starts_with($template->watermark_image_path, 'data:') 
                ? $template->watermark_image_path 
                : asset('storage/' . $template->watermark_image_path);
        } elseif ($template->watermark_type === 'logo' && !empty($organization->logo_path)) {
            $watermarkSrc = str_starts_with($organization->logo_path, 'http') || str_starts_with($organization->logo_path, 'data:')
                ? $organization->logo_path 
                : asset('storage/' . $organization->logo_path);
        }
    @endphp

    <div style="position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg); opacity: 0.1; z-index: -100; text-align: center; pointer-events: none;">
        @if($template->watermark_type === 'text' && !empty($template->watermark_text))
            <div style="font-size: 80px; font-weight: bold; color: #000; white-space: nowrap;">
                {{ strtoupper($template->watermark_text) }}
            </div>
        @elseif($watermarkSrc)
            <img src="{{ $watermarkSrc }}" style="max-width: 500px; max-height: 500px;">
        @endif
    </div>
@endif
