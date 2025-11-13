<?php
    $user = Auth::user();
?>

<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div class="flex min-w-72 flex-col gap-2">
            <p class="text-gray-900 dark:text-white text-3xl sm:text-4xl font-black leading-tight tracking-[-0.033em]">
                <?php echo e(__('patient.dashboard.welcome', ['name' => $user->name])); ?>

            </p>
            <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">
                <?php echo e(__('patient.dashboard.summary')); ?>

            </p>
        </div>
        <a href="<?php echo e(route('prescription.upload.step1')); ?>"
            class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white gap-2 text-base font-bold leading-normal tracking-[0.015em] shadow-sm hover:bg-primary/90">
            <span class="material-symbols-outlined">upload_file</span>
            <span class="truncate"><?php echo e(__('patient.dashboard.upload_prescription')); ?></span>
        </a>
    </div>

    <div class="space-y-8">
        <!-- Active Orders Section -->
        <section>
            <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-1 pb-4">
                <?php echo e(__('patient.dashboard.active_orders.title')); ?>

            </h2>
            <div
                class="bg-white dark:bg-gray-900/50 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
                <!-- Active Order Item -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-4 border-b border-gray-200 dark:border-gray-800">
                    <div class="flex-1">
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Order #TAS-84321</p>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">
                            <?php echo e(__('patient.dashboard.active_orders.awaiting_confirmation')); ?>

                        </p>
                    </div>
                    <div class="flex-1 w-full">
                        <div class="relative w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                            <div class="absolute top-0 left-0 h-2 bg-yellow-400 rounded-full" style="width: 25%;"></div>
                        </div>
                    </div>
                    <a class="text-primary font-bold text-sm whitespace-nowrap"
                        href="#"><?php echo e(__('patient.dashboard.active_orders.view_details')); ?></a>
                </div>

                <!-- Active Order Item -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-4">
                    <div class="flex-1">
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Order #TAS-84199</p>
                        <p class="text-gray-900 dark:text-white font-semibold mt-1">
                            <?php echo e(__('patient.dashboard.active_orders.ready_for_pickup')); ?>

                        </p>
                    </div>
                    <div class="flex-1 w-full">
                        <div class="relative w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                            <div class="absolute top-0 left-0 h-2 bg-green-500 rounded-full" style="width: 75%;"></div>
                        </div>
                    </div>
                    <a class="text-primary font-bold text-sm whitespace-nowrap"
                        href="#"><?php echo e(__('patient.dashboard.active_orders.view_details')); ?></a>
                </div>
            </div>
        </section>

        <!-- Recent History & Account Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent History -->
            <section class="lg:col-span-2">
                <h2
                    class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-1 pb-4">
                    <?php echo e(__('patient.dashboard.recent_history.title')); ?>

                </h2>
                <div
                    class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
                    <!-- History Items -->
                    <?php for($i = 0; $i < 3; $i++): ?>
                        <div
                            class="flex items-center gap-4 p-4 border-b border-gray-200 dark:border-gray-800 last:border-b-0">
                            <div
                                class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                <span
                                    class="material-symbols-outlined text-gray-600 dark:text-gray-400">local_pharmacy</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-gray-900 dark:text-white text-sm font-bold truncate">Order
                                    #TAS-<?php echo e(84200 + $i); ?></p>
                                <p class="text-gray-500 dark:text-gray-400 text-xs">
                                    <?php echo e(__('patient.dashboard.recent_history.completed')); ?></p>
                            </div>
                            <a href="#"
                                class="text-primary text-sm font-medium whitespace-nowrap"><?php echo e(__('common.actions.view')); ?></a>
                        </div>
                    <?php endfor; ?>
                    <div class="p-4 text-center">
                        <a href="<?php echo e(route('patient.orders')); ?>" class="text-primary font-bold text-sm">
                            <?php echo e(__('patient.dashboard.recent_history.view_all')); ?>

                        </a>
                    </div>
                </div>
            </section>

            <!-- Account Summary -->
            <section>
                <h2
                    class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-1 pb-4">
                    <?php echo e(__('patient.dashboard.account_summary.title')); ?>

                </h2>
                <div
                    class="bg-white dark:bg-gray-900/50 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 space-y-4">
                    <div class="flex justify-between items-center">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            <?php echo e(__('patient.dashboard.account_summary.active_orders')); ?></p>
                        <p class="text-gray-900 dark:text-white font-bold">2</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            <?php echo e(__('patient.dashboard.account_summary.completed')); ?></p>
                        <p class="text-gray-900 dark:text-white font-bold">8</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            <?php echo e(__('patient.dashboard.account_summary.penalties')); ?></p>
                        <p class="text-gray-900 dark:text-white font-bold">0</p>
                    </div>
                    <a href="<?php echo e(route('patient.profile')); ?>"
                        class="flex items-center justify-center h-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 text-sm font-bold text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition mt-4">
                        <?php echo e(__('patient.dashboard.account_summary.view_profile')); ?>

                    </a>
                </div>
            </section>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/patient/partials/dashboard-content.blade.php ENDPATH**/ ?>