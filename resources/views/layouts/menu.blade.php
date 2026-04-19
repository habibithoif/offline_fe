@foreach($items as $item)
    @php
        $isActive = request()->is(ltrim(parse_url(url($item['path']), PHP_URL_PATH), '/'));
    @endphp
    <li class="nav-item {{ $isActive ? 'menu-is-opening menu-open' : '' }}">
        <a href="{{ isset($item['path']) ? url($item['path']) : '#' }}" 
           class="nav-link {{ $isActive ? 'active' : '' }}">
            <i class="{{ $item['icon'] }} nav-icon"></i>
            <p>
                {{ $item['display'] }}
                @if(!empty($item['children']))
                    <i class="right fas fa-angle-left"></i>
                @endif
            </p>
        </a>
        @if(!empty($item['children']))
            <ul class="nav nav-treeview" style="{{ $isActive ? 'display: block;' : 'display: none;' }}">
                @foreach($item['children'] as $child)
                    @include('layouts.menu', ['items' => [$child]]) 
                @endforeach
            </ul>
        @endif
    </li>
@endforeach



<script src="{{ asset('template_app') }}/plugins/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        let currentPath = window.location.pathname;

        $(".nav-link").each(function () {
            let menuPath = new URL($(this).attr("href"), window.location.origin).pathname; // Ambil path tanpa domain

            if (menuPath === currentPath) {
                $(this).addClass("active"); // Tambahkan class active ke menu yang cocok
                let parentNavItem = $(this).closest(".nav-item");

                // Tambahkan class 'menu-is-opening menu-open' agar parent terbuka
                parentNavItem.addClass("menu-is-opening menu-open");
                parentNavItem.children(".nav-treeview").css("display", "block");

                // Pastikan semua parent di atasnya juga terbuka
                parentNavItem.parents(".nav-item").addClass("menu-is-opening menu-open");
                parentNavItem.parents(".nav-treeview").css("display", "block");
            }
        });
    });

</script>


