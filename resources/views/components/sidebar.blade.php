{{--
    Sidebar Component for Patient/Pharmacy/Admin Dashboards
    Props:
    - $type: 'patient'|'pharmacy'|'admin' - determines menu items
    - $user: Current authenticated user
    - $currentRoute: Current route name for active state
    - $useSpa: boolean (optional) - enable SPA navigation with Alpine.js
--}}

@php
    $useSpa = $useSpa ?? false;
    $menus = [
        'patient' => [
            [
                'icon' => 'dashboard',
                'label' => __('patient.dashboard.sidebar.dashboard'),
                'route' => 'patient.dashboard',
            ],
            [
                'icon' => 'receipt_long',
                'label' => __('patient.dashboard.sidebar.my_orders'),
                'route' => 'patient.orders',
            ],
            [
                'icon' => 'history',
                'label' => __('patient.dashboard.sidebar.order_history'),
                'route' => 'patient.orders.history',
            ],
            ['icon' => 'person', 'label' => __('patient.dashboard.sidebar.profile'), 'route' => 'patient.profile'],
            ['icon' => 'help_outline', 'label' => __('patient.dashboard.sidebar.help'), 'route' => 'patient.help'],
        ],
        'pharmacy' => [
            [
                'icon' => 'dashboard',
                'label' => __('pharmacy.dashboard.sidebar.dashboard'),
                'route' => 'pharmacy.dashboard',
            ],
            [
                'icon' => 'receipt_long',
                'label' => __('pharmacy.dashboard.sidebar.orders'),
                'route' => 'pharmacy.orders',
            ],
            [
                'icon' => 'inventory_2',
                'label' => __('pharmacy.dashboard.sidebar.inventory'),
                'route' => 'pharmacy.inventory',
            ],
            [
                'icon' => 'assessment',
                'label' => __('pharmacy.dashboard.sidebar.reports'),
                'route' => 'pharmacy.reports',
            ],
        ],
        'admin' => [
            ['icon' => 'dashboard', 'label' => __('admin.dashboard.sidebar.dashboard'), 'route' => 'admin.dashboard'],
            ['icon' => 'group', 'label' => __('admin.dashboard.sidebar.users'), 'route' => 'admin.users'],
            ['icon' => 'store', 'label' => __('admin.dashboard.sidebar.pharmacies'), 'route' => 'admin.pharmacies'],
            ['icon' => 'receipt_long', 'label' => __('admin.dashboard.sidebar.orders'), 'route' => 'admin.orders'],
            ['icon' => 'warning', 'label' => __('admin.dashboard.sidebar.penalties'), 'route' => 'admin.penalties'],
            ['icon' => 'assessment', 'label' => __('admin.dashboard.sidebar.reports'), 'route' => 'admin.reports'],
        ],
    ];

    $sidebarItems = $menus[$type] ?? [];
    $currentRoute = $currentRoute ?? Route::currentRouteName();
@endphp

<aside
    class="w-64 flex-shrink-0 bg-white dark:bg-background-dark p-4 border-r border-gray-200 dark:border-gray-800 hidden md:flex flex-col">
    <div class="flex h-full flex-col justify-between">
        <div class="flex flex-col gap-4">
            <!-- User Info -->
            <div class="flex items-center gap-3">
                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                    style='background-image: url("https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&background=137fec&color=fff");'>
                </div>
                <div class="flex flex-col">
                    <h1 class="text-gray-900 dark:text-white text-base font-medium leading-normal">
                        {{ $user->name ?? 'User' }}</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-normal leading-normal">
                        {{ $user->email ?? '' }}</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex flex-col gap-2 mt-4">
                @foreach ($sidebarItems as $item)
                    <a class="flex items-center gap-3 px-3 py-2 rounded-lg {{ $currentRoute === $item['route'] ? 'bg-primary/10 text-primary' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-900 dark:text-white' }}"
                        href="{{ route($item['route']) }}" @if ($useSpa) data-spa @endif>
                        <span class="material-symbols-outlined">{{ $item['icon'] }}</span>
                        <p
                            class="text-sm {{ $currentRoute === $item['route'] ? 'font-bold' : 'font-medium' }} leading-normal">
                            {{ $item['label'] }}</p>
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- Bottom Actions -->
        <div class="flex flex-col gap-2">
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-900 dark:text-white"
                href="{{ route('settings') }}" @if ($useSpa) data-spa @endif>
                <span class="material-symbols-outlined">settings</span>
                <p class="text-sm font-medium leading-normal">{{ __('patient.dashboard.sidebar.settings') }}</p>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-500/10 text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-500">
                    <span class="material-symbols-outlined">logout</span>
                    <p class="text-sm font-medium leading-normal">{{ __('patient.dashboard.sidebar.logout') }}</p>
                </button>
            </form>
        </div>
    </div>
</aside>
