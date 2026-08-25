@php
    $dashboardUrl = route('filament.dashboard');
    $dashboard = $items->first(function ($item) use ($dashboardUrl) {
        return rtrim($item->url, '/') === rtrim($dashboardUrl, '/');
    });
    $groups = [
        'CRM' => [
            '/resources/customers',
            '/resources/leads',
            '/resources/marketing-sources',
        ],
        'Dịch vụ' => [
            '/resources/service-orders',
            '/resources/water-purifiers',
        ],
        'Nhân sự' => [
            '/resources/technicians',
        ],
        'Sản phẩm' => [
            '/resources/products',
            '/resources/product-categories',
        ],
        'Cấu hình máy lọc' => [
            '/resources/purifier-brands',
            '/resources/purifier-models',
        ],
        'Hệ thống' => [
            '/admin/users',
        ],
    ];
@endphp

<nav aria-label="primary" {{ $attributes }}>
    <ol class="space-y-5">
        @if ($dashboard)
            <li>
                <x-filament::nav-link :active-rule="$dashboard->activeRule" :icon="$dashboard->icon" label="Dashboard" :url="$dashboard->url" />
            </li>
        @endif

        @foreach ($groups as $group => $paths)
            @php
                $groupItems = $items->filter(function ($item) use ($paths) {
                    foreach ($paths as $path) {
                        if (strpos($item->url, $path) !== false) {
                            return true;
                        }
                    }

                    return false;
                });
            @endphp
            @if ($groupItems->isNotEmpty())
                <li>
                    <div class="px-4 mb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">{{ $group }}</div>
                    <ol class="space-y-1">
                        @foreach ($groupItems as $item)
                            @php
                                $label = strpos($item->url, '/admin/users') !== false
                                    ? 'Tài khoản quản trị'
                                    : $item->label;
                            @endphp
                            <li><x-filament::nav-link :active-rule="$item->activeRule" :icon="$item->icon" :label="$label" :url="$item->url" /></li>
                        @endforeach
                    </ol>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
