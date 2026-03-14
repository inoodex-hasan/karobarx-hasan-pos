<aside class="side-bar">
    <a href="{{ route('home') }}" class="logo tw-flex tw-items-center tw-justify-center tw-h-16 tw-border-b">
        <span class="tw-text-lg tw-font-semibold">{{ Session::get('business.name') }}</span>
    </a>

    {!! Menu::render('admin-sidebar-menu', 'vihocustom') !!}
</aside>
