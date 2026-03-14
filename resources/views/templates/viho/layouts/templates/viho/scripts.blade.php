@php
    $template_asset = function ($path) {
        return asset('templates/viho/assets/'.ltrim($path, '/'));
    };
@endphp

<script src="{{ $template_asset('js/icons/feather-icon/feather.min.js') }}"></script>
<script>
    if (window.feather) {
        feather.replace();
    }
</script>
