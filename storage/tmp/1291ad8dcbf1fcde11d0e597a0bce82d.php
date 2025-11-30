<!-- Reports & Analytics -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-body-text dark:text-body-text-dark">
            <?php echo e(__('admin.reports.title')); ?>

        </h1>
        <p class="text-neutral-text dark:text-neutral-text-dark mt-1">
            <?php echo e(__('admin.reports.subtitle')); ?>

        </p>
    </div>
    <div class="flex gap-2">
        <button
            class="flex items-center gap-2 px-4 py-2 border border-border-light dark:border-border-dark rounded-lg hover:bg-background-light dark:hover:bg-background-dark transition-colors">
            <span class="material-symbols-outlined">file_download</span>
            <?php echo e(__('admin.reports.export_report')); ?>

        </button>
        <button
            class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity">
            <span class="material-symbols-outlined">add</span>
            <?php echo e(__('admin.reports.generate_report')); ?>

        </button>
    </div>
</div>

<!-- Date Range Selector -->
<div
    class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm mb-6 p-4">
    <div class="flex items-center gap-4">
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark">
            <?php echo e(__('admin.reports.date_range')); ?>:
        </label>
        <select
            class="px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
            <option><?php echo e(__('admin.reports.last_7_days')); ?></option>
            <option><?php echo e(__('admin.reports.last_30_days')); ?></option>
            <option><?php echo e(__('admin.reports.last_3_months')); ?></option>
            <option><?php echo e(__('admin.reports.last_year')); ?></option>
            <option><?php echo e(__('admin.reports.custom_range')); ?></option>
        </select>
        <button class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity">
            <?php echo e(__('common.actions.apply')); ?>

        </button>
    </div>
</div>

<!-- Key Metrics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            <?php echo e(__('admin.reports.total_revenue')); ?>

        </p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">$125,420</p>
        <p class="text-sm font-medium text-success flex items-center gap-1">
            <span class="material-symbols-outlined text-base">arrow_upward</span> +18.2%
        </p>
    </div>
    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            <?php echo e(__('admin.reports.total_orders')); ?>

        </p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">1,524</p>
        <p class="text-sm font-medium text-success flex items-center gap-1">
            <span class="material-symbols-outlined text-base">arrow_upward</span> +8.2%
        </p>
    </div>
    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            <?php echo e(__('admin.reports.avg_order_value')); ?>

        </p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">$82.25</p>
        <p class="text-sm font-medium text-success flex items-center gap-1">
            <span class="material-symbols-outlined text-base">arrow_upward</span> +5.4%
        </p>
    </div>
    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            <?php echo e(__('admin.reports.completion_rate')); ?>

        </p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">94.2%</p>
        <p class="text-sm font-medium text-success flex items-center gap-1">
            <span class="material-symbols-outlined text-base">trending_up</span> +2.1%
        </p>
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Orders Chart -->
    <div
        class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                <?php echo e(__('admin.reports.orders_trend')); ?>

            </h2>
            <select
                class="text-sm px-3 py-1 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark">
                <option><?php echo e(__('admin.reports.daily')); ?></option>
                <option><?php echo e(__('admin.reports.weekly')); ?></option>
                <option><?php echo e(__('admin.reports.monthly')); ?></option>
            </select>
        </div>
        <div class="h-64 flex items-center justify-center bg-background-light dark:bg-background-dark rounded-lg">
            <svg fill="none" height="250" preserveAspectRatio="none" viewBox="0 0 400 250" width="100%"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M0 200 L40 180 L80 190 L120 150 L160 160 L200 120 L240 130 L280 100 L320 110 L360 80 L400 70"
                    stroke="#137fec" stroke-width="3" fill="none" stroke-linecap="round" />
                <path
                    d="M0 200 L40 180 L80 190 L120 150 L160 160 L200 120 L240 130 L280 100 L320 110 L360 80 L400 70 L400 250 L0 250 Z"
                    fill="url(#gradient1)" opacity="0.3" />
                <defs>
                    <linearGradient id="gradient1" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#137fec" stop-opacity="0.4" />
                        <stop offset="100%" stop-color="#137fec" stop-opacity="0" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div
        class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                <?php echo e(__('admin.reports.revenue_breakdown')); ?>

            </h2>
        </div>
        <div class="h-64 flex items-center justify-center">
            <div class="relative w-48 h-48">
                <svg viewBox="0 0 200 200" class="transform -rotate-90">
                    <circle cx="100" cy="100" r="80" fill="none" stroke="#e5e7eb" stroke-width="40" />
                    <circle cx="100" cy="100" r="80" fill="none" stroke="#137fec" stroke-width="40"
                        stroke-dasharray="377" stroke-dashoffset="95" />
                    <circle cx="100" cy="100" r="80" fill="none" stroke="#2ECC71" stroke-width="40"
                        stroke-dasharray="377" stroke-dashoffset="189" />
                    <circle cx="100" cy="100" r="80" fill="none" stroke="#F39C12" stroke-width="40"
                        stroke-dasharray="377" stroke-dashoffset="283" />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-body-text dark:text-body-text-dark">$125K</p>
                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                            <?php echo e(__('admin.reports.total')); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-4">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-primary"></div>
                <div>
                    <p class="text-sm text-body-text dark:text-body-text-dark font-medium">
                        <?php echo e(__('admin.reports.prescriptions')); ?></p>
                    <p class="text-xs text-neutral-text dark:text-neutral-text-dark">$75K (60%)</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-secondary"></div>
                <div>
                    <p class="text-sm text-body-text dark:text-body-text-dark font-medium">
                        <?php echo e(__('admin.reports.otc_products')); ?></p>
                    <p class="text-xs text-neutral-text dark:text-neutral-text-dark">$37.5K (30%)</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-accent"></div>
                <div>
                    <p class="text-sm text-body-text dark:text-body-text-dark font-medium">
                        <?php echo e(__('admin.reports.delivery_fees')); ?></p>
                    <p class="text-xs text-neutral-text dark:text-neutral-text-dark">$12.5K (10%)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance by Pharmacy Chain -->
<div
    class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm mb-6">
    <div class="p-6 border-b border-border-light dark:border-border-dark">
        <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
            <?php echo e(__('admin.reports.pharmacy_performance')); ?>

        </h2>
    </div>
    <div class="p-6">
        <table class="w-full">
            <thead>
                <tr class="border-b border-border-light dark:border-border-dark">
                    <th class="text-left py-3 text-sm font-medium text-neutral-text dark:text-neutral-text-dark">
                        <?php echo e(__('admin.reports.pharmacy_chain')); ?>

                    </th>
                    <th class="text-left py-3 text-sm font-medium text-neutral-text dark:text-neutral-text-dark">
                        <?php echo e(__('admin.reports.total_orders')); ?>

                    </th>
                    <th class="text-left py-3 text-sm font-medium text-neutral-text dark:text-neutral-text-dark">
                        <?php echo e(__('admin.reports.revenue')); ?>

                    </th>
                    <th class="text-left py-3 text-sm font-medium text-neutral-text dark:text-neutral-text-dark">
                        <?php echo e(__('admin.reports.avg_rating')); ?>

                    </th>
                    <th class="text-left py-3 text-sm font-medium text-neutral-text dark:text-neutral-text-dark">
                        <?php echo e(__('admin.reports.fulfillment_time')); ?>

                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-light dark:divide-border-dark">
                <?php for($i = 1; $i <= 6; $i++): ?>
                    <?php
                        $chains = [
                            'Farmacia del Ahorro',
                            'Farmacias Guadalajara',
                            'Farmacias Similares',
                            'Farmacias Benavides',
                            'Farmacias San Pablo',
                            'Farmacias Especializadas',
                        ];
                    ?>
                    <tr class="hover:bg-background-light dark:hover:bg-background-dark transition-colors">
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary">local_pharmacy</span>
                                </div>
                                <span class="text-sm font-medium text-body-text dark:text-body-text-dark">
                                    <?php echo e($chains[$i - 1]); ?>

                                </span>
                            </div>
                        </td>
                        <td class="py-4">
                            <span class="text-sm text-body-text dark:text-body-text-dark">
                                <?php echo e(rand(200, 500)); ?>

                            </span>
                        </td>
                        <td class="py-4">
                            <span class="text-sm font-medium text-body-text dark:text-body-text-dark">
                                $<?php echo e(number_format(rand(15000, 35000), 0)); ?>

                            </span>
                        </td>
                        <td class="py-4">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-yellow-500 text-base">star</span>
                                <span class="text-sm text-body-text dark:text-body-text-dark">
                                    <?php echo e(number_format(rand(40, 50) / 10, 1)); ?>

                                </span>
                            </div>
                        </td>
                        <td class="py-4">
                            <span class="text-sm text-body-text dark:text-body-text-dark">
                                <?php echo e(number_format(rand(120, 180) / 60, 1)); ?>h
                            </span>
                        </td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Top Medications -->
<div class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm">
    <div class="p-6 border-b border-border-light dark:border-border-dark">
        <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
            <?php echo e(__('admin.reports.top_medications')); ?>

        </h2>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            <?php for($i = 1; $i <= 8; $i++): ?>
                <?php
                    $medications = [
                        'Paracetamol 500mg',
                        'Ibuprofeno 400mg',
                        'Amoxicilina 500mg',
                        'Losartán 50mg',
                        'Metformina 850mg',
                        'Omeprazol 20mg',
                        'Atorvastatina 20mg',
                        'Salbutamol Inhalador',
                    ];
                    $quantity = rand(150, 500);
                    $maxQuantity = 500;
                    $percentage = ($quantity / $maxQuantity) * 100;
                ?>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-body-text dark:text-body-text-dark">
                            <?php echo e($medications[$i - 1]); ?>

                        </span>
                        <span class="text-sm text-neutral-text dark:text-neutral-text-dark">
                            <?php echo e($quantity); ?> <?php echo e(__('admin.reports.units_sold')); ?>

                        </span>
                    </div>
                    <div class="w-full bg-background-light dark:bg-background-dark rounded-full h-2">
                        <div class="bg-primary h-2 rounded-full" style="width: <?php echo e($percentage); ?>%"></div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\laravel\Te-Acerco-Salud\resources\views\admin\partials\reports-content.blade.php ENDPATH**/ ?>