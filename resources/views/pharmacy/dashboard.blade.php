@extends('layouts.pharmacy')

@section('title', __('pharmacy.dashboard.title') . ' - Te Acerco Salud')

@section('pharmacy-content')
    <div class="p-4 sm:p-6 lg:p-10">
        <div class="mx-auto max-w-7xl space-y-8">

            <!-- Page Header -->
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="text-4xl font-black leading-tight tracking-[-0.033em] text-body-text dark:text-body-text-dark">
                    {{ __('pharmacy.dashboard.title') }}
                </h1>
                <div class="flex flex-wrap gap-3">
                    <button
                        class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold shadow-sm hover:bg-primary/90 transition">
                        <span class="material-symbols-outlined text-base">add</span>
                        <span class="truncate">{{ __('pharmacy.dashboard.new_order') }}</span>
                    </button>
                    <button
                        class="flex min-w-[84px] cursor-pointer items-center justify-center gap-2 overflow-hidden rounded-lg h-10 px-4 bg-card-light dark:bg-card-dark text-body-text dark:text-body-text-dark border border-border-light dark:border-border-dark text-sm font-bold shadow-sm hover:bg-background-light dark:hover:bg-background-dark transition">
                        <span class="material-symbols-outlined text-base">upload_file</span>
                        <span class="truncate">{{ __('pharmacy.dashboard.export_report') }}</span>
                    </button>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
                    <div class="flex justify-between items-center">
                        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
                            {{ __('pharmacy.dashboard.pending_orders') }}</p>
                        <span class="material-symbols-outlined text-warning">pending_actions</span>
                    </div>
                    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">12</p>
                    <p class="text-sm font-medium text-secondary">+5% vs yesterday</p>
                </div>

                <div
                    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
                    <div class="flex justify-between items-center">
                        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
                            {{ __('pharmacy.dashboard.completed_today') }}</p>
                        <span class="material-symbols-outlined text-success">task_alt</span>
                    </div>
                    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">45</p>
                    <p class="text-sm font-medium text-success">+12% vs yesterday</p>
                </div>

                <div
                    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
                    <div class="flex justify-between items-center">
                        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
                            {{ __('pharmacy.dashboard.revenue_today') }}</p>
                        <span class="material-symbols-outlined text-primary">payments</span>
                    </div>
                    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">$3,240</p>
                    <p class="text-sm font-medium text-secondary">+8% vs yesterday</p>
                </div>

                <div
                    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
                    <div class="flex justify-between items-center">
                        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
                            {{ __('pharmacy.dashboard.low_stock_items') }}</p>
                        <span class="material-symbols-outlined text-danger">inventory</span>
                    </div>
                    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">8</p>
                    <p class="text-sm font-medium text-danger">
                        {{ __('pharmacy.dashboard.requires_attention') }}</p>
                </div>
            </div>

            <!-- Recent Orders -->
            <div
                class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm">
                <div class="flex justify-between items-center p-6 border-b border-border-light dark:border-border-dark">
                    <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                        {{ __('pharmacy.dashboard.recent_orders') }}</h2>
                    <a href="{{ route('pharmacy.orders') }}"
                        class="text-sm font-bold text-primary hover:underline">{{ __('common.actions.view_all') }}</a>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @for ($i = 1; $i <= 5; $i++)
                            <div
                                class="flex items-center justify-between p-4 rounded-lg border border-border-light dark:border-border-dark hover:bg-background-light dark:hover:bg-background-dark transition">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-primary">receipt_long</span>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-body-text dark:text-body-text-dark">Order
                                            #TAS-{{ 1000 + $i }}</h3>
                                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                                            Patient {{ $i }} - {{ $i }} items</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                                                @if ($i == 1) bg-warning/20 text-warning
                                                @elseif($i == 2) bg-success/20 text-success
                                                @else bg-primary/20 text-primary @endif">
                                        @if ($i == 1)
                                            {{ __('pharmacy.orders.status.pending') }}
                                        @elseif($i == 2)
                                            {{ __('pharmacy.orders.status.ready') }}
                                        @else
                                            {{ __('pharmacy.orders.status.processing') }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
