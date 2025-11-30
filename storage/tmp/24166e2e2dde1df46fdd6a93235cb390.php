<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            <?php echo e(__('admin.dashboard.total_patients')); ?></p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">
            <?php echo e(number_format($stats['total_patients'])); ?></p>
        <p
            class="text-sm font-medium <?php echo e($stats['patients_growth'] >= 0 ? 'text-success' : 'text-danger'); ?> flex items-center gap-1">
            <span class="material-symbols-outlined text-base">
                <?php echo e($stats['patients_growth'] >= 0 ? 'arrow_upward' : 'arrow_downward'); ?>

            </span>
            <?php echo e($stats['patients_growth'] >= 0 ? '+' : ''); ?><?php echo e(number_format($stats['patients_growth'], 1)); ?>%
        </p>
    </div>

    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            <?php echo e(__('admin.dashboard.active_pharmacies')); ?></p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">
            <?php echo e(number_format($stats['active_pharmacies'])); ?></p>
        <p
            class="text-sm font-medium <?php echo e($stats['pharmacies_growth'] >= 0 ? 'text-success' : 'text-danger'); ?> flex items-center gap-1">
            <span class="material-symbols-outlined text-base">
                <?php echo e($stats['pharmacies_growth'] >= 0 ? 'arrow_upward' : 'arrow_downward'); ?>

            </span>
            <?php echo e($stats['pharmacies_growth'] >= 0 ? '+' : ''); ?><?php echo e(number_format($stats['pharmacies_growth'], 1)); ?>%
        </p>
    </div>

    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            <?php echo e(__('admin.dashboard.prescriptions_today')); ?></p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">
            <?php echo e(number_format($stats['prescriptions_today'])); ?></p>
        <p
            class="text-sm font-medium <?php echo e($stats['prescriptions_growth'] >= 0 ? 'text-success' : 'text-danger'); ?> flex items-center gap-1">
            <span class="material-symbols-outlined text-base">
                <?php echo e($stats['prescriptions_growth'] >= 0 ? 'arrow_upward' : 'arrow_downward'); ?>

            </span>
            <?php echo e($stats['prescriptions_growth'] >= 0 ? '+' : ''); ?><?php echo e(number_format($stats['prescriptions_growth'], 1)); ?>%
        </p>
    </div>

    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            <?php echo e(__('admin.dashboard.avg_fulfillment')); ?></p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">
            <?php echo e(number_format($stats['avg_fulfillment_time'], 1)); ?>h</p>
        <p
            class="text-sm font-medium <?php echo e($stats['fulfillment_trend'] <= 0 ? 'text-success' : 'text-danger'); ?> flex items-center gap-1">
            <span class="material-symbols-outlined text-base">
                <?php echo e($stats['fulfillment_trend'] <= 0 ? 'arrow_downward' : 'arrow_upward'); ?>

            </span>
            <?php echo e($stats['fulfillment_trend'] <= 0 ? '' : '+'); ?><?php echo e(number_format($stats['fulfillment_trend'], 1)); ?>%
        </p>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Chart Card -->
    <div
        class="lg:col-span-2 flex flex-col gap-4 rounded-xl border border-border-light dark:border-border-dark p-6 bg-card-light dark:bg-card-dark shadow-sm">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
                    <?php echo e(__('admin.dashboard.new_registrations')); ?></h2>
                <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                    <?php echo e(__('admin.dashboard.last_30_days')); ?></p>
            </div>
            <div class="flex items-baseline gap-2">
                <p class="text-2xl font-bold text-body-text dark:text-body-text-dark">
                    <?php echo e(number_format($registrations['total'])); ?></p>
                <p class="text-sm font-medium <?php echo e($registrations['growth'] >= 0 ? 'text-success' : 'text-danger'); ?>">
                    <?php echo e($registrations['growth'] >= 0 ? '+' : ''); ?><?php echo e(number_format($registrations['growth'], 1)); ?>%
                </p>
            </div>
        </div>
        <div
            class="flex min-h-[220px] flex-1 items-center justify-center bg-background-light dark:bg-background-dark rounded-lg">
            <svg fill="none" height="200" preserveAspectRatio="none" viewBox="0 0 500 200" width="100%"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M0 150 L50 120 L100 130 L150 90 L200 100 L250 70 L300 85 L350 60 L400 75 L450 50 L500 40"
                    stroke="#137fec" stroke-width="3" fill="none" stroke-linecap="round" />
                <path
                    d="M0 150 L50 120 L100 130 L150 90 L200 100 L250 70 L300 85 L350 60 L400 75 L450 50 L500 40 L500 200 L0 200 Z"
                    fill="url(#gradient)" opacity="0.3" />
                <defs>
                    <linearGradient id="gradient" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#137fec" stop-opacity="0.4" />
                        <stop offset="100%" stop-color="#137fec" stop-opacity="0" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="flex flex-col gap-4">
        <div
            class="flex flex-col gap-4 rounded-xl border border-border-light dark:border-border-dark p-6 bg-card-light dark:bg-card-dark shadow-sm">
            <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark">
                <?php echo e(__('admin.dashboard.quick_actions')); ?></h3>
            <div class="flex flex-col gap-2">
                <a href="<?php echo e(route('admin.users')); ?>" data-spa
                    class="flex items-center gap-3 p-3 rounded-lg hover:bg-background-light dark:hover:bg-background-dark transition">
                    <span class="material-symbols-outlined text-primary">group</span>
                    <p class="text-sm font-medium text-body-text dark:text-body-text-dark">
                        <?php echo e(__('admin.dashboard.manage_users')); ?></p>
                </a>
                <a href="<?php echo e(route('admin.pharmacies')); ?>" data-spa
                    class="flex items-center gap-3 p-3 rounded-lg hover:bg-background-light dark:hover:bg-background-dark transition">
                    <span class="material-symbols-outlined text-primary">storefront</span>
                    <p class="text-sm font-medium text-body-text dark:text-body-text-dark">
                        <?php echo e(__('admin.dashboard.manage_pharmacies')); ?></p>
                </a>
                <a href="<?php echo e(route('admin.reports')); ?>" data-spa
                    class="flex items-center gap-3 p-3 rounded-lg hover:bg-background-light dark:hover:bg-background-dark transition">
                    <span class="material-symbols-outlined text-primary">assessment</span>
                    <p class="text-sm font-medium text-body-text dark:text-body-text-dark">
                        <?php echo e(__('admin.dashboard.view_reports')); ?></p>
                </a>
                <a href="<?php echo e(route('admin.penalties')); ?>" data-spa
                    class="flex items-center gap-3 p-3 rounded-lg hover:bg-background-light dark:hover:bg-background-dark transition">
                    <span class="material-symbols-outlined text-primary">gavel</span>
                    <p class="text-sm font-medium text-body-text dark:text-body-text-dark">
                        <?php echo e(__('admin.dashboard.manage_penalties')); ?></p>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div
    class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm mt-6">
    <div class="flex justify-between items-center p-6 border-b border-border-light dark:border-border-dark">
        <h2 class="text-xl font-bold text-body-text dark:text-body-text-dark">
            <?php echo e(__('admin.dashboard.recent_activity')); ?></h2>
        <a href="#"
            class="text-sm font-bold text-primary hover:underline"><?php echo e(__('common.actions.view_all')); ?></a>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            <?php for($i = 1; $i <= 5; $i++): ?>
                <div class="flex items-start gap-4 p-4 rounded-lg border border-border-light dark:border-border-dark">
                    <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-primary">
                            <?php if($i == 1): ?>
                                person_add
                            <?php elseif($i == 2): ?>
                                storefront
                            <?php elseif($i == 3): ?>
                                receipt_long
                            <?php elseif($i == 4): ?>
                                warning
                            <?php else: ?>
                                settings
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-body-text dark:text-body-text-dark">
                            <?php if($i == 1): ?>
                                <?php echo e(__('admin.dashboard.activity.new_user')); ?>

                            <?php elseif($i == 2): ?>
                                <?php echo e(__('admin.dashboard.activity.pharmacy_registered')); ?>

                            <?php elseif($i == 3): ?>
                                <?php echo e(__('admin.dashboard.activity.order_completed')); ?>

                            <?php elseif($i == 4): ?>
                                <?php echo e(__('admin.dashboard.activity.penalty_issued')); ?>

                            <?php else: ?>
                                <?php echo e(__('admin.dashboard.activity.system_update')); ?>

                            <?php endif; ?>
                        </h3>
                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                            <?php echo e(__('admin.dashboard.activity.time_ago', ['time' => $i * 5])); ?>

                        </p>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\laravel\Te-Acerco-Salud\resources\views\admin\partials\dashboard-content.blade.php ENDPATH**/ ?>