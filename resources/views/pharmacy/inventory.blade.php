<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('pharmacy.inventory.title') }} - Te Acerco Salud</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "secondary": "#2ECC71",
                        "accent": "#F39C12",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                        "success": "#28A745",
                        "warning": "#FFC107",
                        "danger": "#DC3545",
                        "neutral-text": "#617589",
                        "neutral-text-dark": "#90a4b8",
                        "body-text": "#111418",
                        "body-text-dark": "#f0f2f4",
                        "border-light": "#f0f2f4",
                        "border-dark": "#2a3b4c",
                        "card-light": "#ffffff",
                        "card-dark": "#1a2734",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark">
    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">

            @include('components.topbar', ['user' => auth()->user(), 'type' => 'pharmacy'])

            <div class="flex flex-1">
                @include('components.sidebar', [
                    'user' => auth()->user(),
                    'type' => 'pharmacy',
                    'currentRoute' => 'pharmacy.inventory',
                ])

                <main class="flex-1 p-4 sm:p-6 lg:p-10">
                    <div class="mx-auto max-w-7xl space-y-6">

                        <!-- Page Header -->
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <h1
                                class="text-4xl font-black leading-tight tracking-[-0.033em] text-body-text dark:text-body-text-dark">
                                {{ __('pharmacy.inventory.title') }}
                            </h1>
                            <button
                                class="flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-white text-base font-bold hover:bg-primary/90">
                                <span class="material-symbols-outlined">add</span>
                                {{ __('pharmacy.inventory.add_new_medication') }}
                            </button>
                        </div>

                        <!-- Filters & Search -->
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-card-light dark:bg-card-dark p-4 rounded-xl border border-border-light dark:border-border-dark">
                            <div class="relative flex-1 w-full">
                                <span
                                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-neutral-text dark:text-neutral-text-dark">search</span>
                                <input type="text" placeholder="{{ __('pharmacy.inventory.search_placeholder') }}"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark placeholder:text-neutral-text dark:placeholder:text-neutral-text-dark focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    class="px-4 py-2 rounded-lg bg-primary text-white text-sm font-medium">{{ __('pharmacy.inventory.filter_all') }}</button>
                                <button
                                    class="px-4 py-2 rounded-lg text-body-text dark:text-body-text-dark border border-border-light dark:border-border-dark hover:bg-background-light dark:hover:bg-background-dark text-sm font-medium">{{ __('pharmacy.inventory.filter_low_stock') }}</button>
                                <button
                                    class="px-4 py-2 rounded-lg text-body-text dark:text-body-text-dark border border-border-light dark:border-border-dark hover:bg-background-light dark:hover:bg-background-dark text-sm font-medium">{{ __('pharmacy.inventory.filter_out_of_stock') }}</button>
                            </div>
                        </div>

                        <!-- Inventory Table -->
                        <div
                            class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-border-light dark:divide-border-dark">
                                    <thead class="bg-background-light dark:bg-background-dark">
                                        <tr>
                                            <th class="py-4 px-6 text-left">
                                                <input type="checkbox"
                                                    class="h-4 w-4 rounded border-border-light dark:border-border-dark text-primary focus:ring-primary">
                                            </th>
                                            <th
                                                class="py-4 px-6 text-left text-sm font-bold text-body-text dark:text-body-text-dark">
                                                {{ __('pharmacy.inventory.medication_info') }}</th>
                                            <th
                                                class="py-4 px-6 text-left text-sm font-bold text-body-text dark:text-body-text-dark">
                                                {{ __('pharmacy.inventory.product_code') }}</th>
                                            <th
                                                class="py-4 px-6 text-left text-sm font-bold text-body-text dark:text-body-text-dark">
                                                {{ __('pharmacy.inventory.current_stock') }}</th>
                                            <th
                                                class="py-4 px-6 text-left text-sm font-bold text-body-text dark:text-body-text-dark">
                                                {{ __('pharmacy.inventory.min_max_stock') }}</th>
                                            <th
                                                class="py-4 px-6 text-left text-sm font-bold text-body-text dark:text-body-text-dark">
                                                {{ __('pharmacy.inventory.unit_price') }}</th>
                                            <th
                                                class="py-4 px-6 text-left text-sm font-bold text-body-text dark:text-body-text-dark">
                                                {{ __('pharmacy.inventory.status') }}</th>
                                            <th
                                                class="py-4 px-6 text-right text-sm font-bold text-body-text dark:text-body-text-dark">
                                                {{ __('pharmacy.inventory.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-border-light dark:divide-border-dark">
                                        @php
                                            $medications = [
                                                [
                                                    'name' => 'Ibuprofen 200mg',
                                                    'generic' => 'Ibuprofen',
                                                    'code' => 'IBU-200-TAB',
                                                    'stock' => 43,
                                                    'min' => 25,
                                                    'max' => 100,
                                                    'price' => 8.5,
                                                    'status' => 'in_stock',
                                                    'editing' => false,
                                                ],
                                                [
                                                    'name' => 'Amoxicillin 500mg',
                                                    'generic' => 'Amoxicillin',
                                                    'code' => 'AMX-500-CAP',
                                                    'stock' => 15,
                                                    'min' => 20,
                                                    'max' => 100,
                                                    'price' => 18.25,
                                                    'status' => 'low_stock',
                                                    'editing' => true,
                                                ],
                                                [
                                                    'name' => 'Amoxicillin 250mg',
                                                    'generic' => 'Amoxicillin',
                                                    'code' => 'AMX-250-SYR',
                                                    'stock' => 0,
                                                    'min' => 20,
                                                    'max' => 100,
                                                    'price' => 25.0,
                                                    'status' => 'out_of_stock',
                                                    'editing' => false,
                                                ],
                                                [
                                                    'name' => 'Loratadine 10mg',
                                                    'generic' => 'Loratadine',
                                                    'code' => 'LOR-10-TAB',
                                                    'stock' => 88,
                                                    'min' => 25,
                                                    'max' => 100,
                                                    'price' => 15.75,
                                                    'status' => 'in_stock',
                                                    'editing' => false,
                                                ],
                                            ];
                                        @endphp

                                        @foreach ($medications as $med)
                                            <tr class="hover:bg-background-light dark:hover:bg-background-dark/50">
                                                <td class="py-4 px-6">
                                                    <input type="checkbox"
                                                        class="h-4 w-4 rounded border-border-light dark:border-border-dark text-primary focus:ring-primary">
                                                </td>
                                                <td class="py-4 px-6">
                                                    <div class="font-semibold text-body-text dark:text-body-text-dark">
                                                        {{ $med['name'] }}</div>
                                                    <div class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                                        {{ $med['generic'] }}</div>
                                                </td>
                                                <td class="py-4 px-6 text-neutral-text dark:text-neutral-text-dark">
                                                    {{ $med['code'] }}</td>
                                                <td
                                                    class="py-4 px-6 font-semibold text-body-text dark:text-body-text-dark">
                                                    @if ($med['editing'])
                                                        <input type="number" value="{{ $med['stock'] }}"
                                                            class="w-20 px-2 py-1 rounded border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark focus:ring-2 focus:ring-primary">
                                                    @else
                                                        {{ $med['stock'] }}
                                                    @endif
                                                </td>
                                                <td class="py-4 px-6 text-neutral-text dark:text-neutral-text-dark">
                                                    {{ $med['min'] }} / {{ $med['max'] }}</td>
                                                <td class="py-4 px-6 text-neutral-text dark:text-neutral-text-dark">
                                                    ${{ number_format($med['price'], 2) }}</td>
                                                <td class="py-4 px-6">
                                                    @if ($med['status'] === 'in_stock')
                                                        <span
                                                            class="inline-flex items-center gap-1.5 rounded-full bg-success/20 px-2 py-1 text-xs font-medium text-success">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                                                            {{ __('pharmacy.inventory.stock_status.in_stock') }}
                                                        </span>
                                                    @elseif($med['status'] === 'low_stock')
                                                        <span
                                                            class="inline-flex items-center gap-1.5 rounded-full bg-warning/20 px-2 py-1 text-xs font-medium text-warning">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                                                            {{ __('pharmacy.inventory.stock_status.low_stock') }}
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center gap-1.5 rounded-full bg-danger/20 px-2 py-1 text-xs font-medium text-danger">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-danger"></span>
                                                            {{ __('pharmacy.inventory.stock_status.out_of_stock') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-6">
                                                    @if ($med['editing'])
                                                        <div class="flex justify-end gap-2">
                                                            <button
                                                                class="px-3 py-2 rounded-lg bg-success text-white text-sm font-semibold hover:bg-success/90">{{ __('pharmacy.inventory.save') }}</button>
                                                            <button
                                                                class="px-3 py-2 rounded-lg bg-neutral-text/20 text-body-text dark:text-body-text-dark text-sm font-semibold hover:bg-neutral-text/30">{{ __('pharmacy.inventory.cancel') }}</button>
                                                        </div>
                                                    @else
                                                        <div class="flex justify-end gap-2">
                                                            <button
                                                                class="p-2 rounded-md text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark hover:text-body-text dark:hover:text-body-text-dark">
                                                                <span
                                                                    class="material-symbols-outlined text-base">edit</span>
                                                            </button>
                                                            <button
                                                                class="p-2 rounded-md text-danger hover:bg-danger/10 hover:text-danger">
                                                                <span
                                                                    class="material-symbols-outlined text-base">delete</span>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div
                                class="flex items-center justify-between p-4 border-t border-border-light dark:border-border-dark">
                                <span
                                    class="text-sm text-neutral-text dark:text-neutral-text-dark">{{ __('pharmacy.inventory.showing_results', ['from' => 1, 'to' => 4, 'total' => 27]) }}</span>
                                <div class="flex items-center gap-2">
                                    <button
                                        class="flex items-center justify-center h-9 w-9 rounded-md border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">
                                        <span class="material-symbols-outlined text-base">chevron_left</span>
                                    </button>
                                    <button
                                        class="flex items-center justify-center h-9 w-9 rounded-md bg-primary text-white text-sm font-semibold">1</button>
                                    <button
                                        class="flex items-center justify-center h-9 w-9 rounded-md border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark hover:bg-background-light dark:hover:bg-background-dark text-sm">2</button>
                                    <button
                                        class="flex items-center justify-center h-9 w-9 rounded-md border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark hover:bg-background-light dark:hover:bg-background-dark text-sm">3</button>
                                    <span class="text-neutral-text dark:text-neutral-text-dark">...</span>
                                    <button
                                        class="flex items-center justify-center h-9 w-9 rounded-md border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark hover:bg-background-light dark:hover:bg-background-dark text-sm">7</button>
                                    <button
                                        class="flex items-center justify-center h-9 w-9 rounded-md border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">
                                        <span class="material-symbols-outlined text-base">chevron_right</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </main>
            </div>
        </div>
    </div>
</body>

</html>
