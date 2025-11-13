

<?php $__env->startSection('title', __('patient.penalties.title')); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $user = Auth::user();
    ?>

    <?php echo $__env->make('components.navbar', ['type' => 'dashboard', 'user' => $user], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-1">
        <?php echo $__env->make('components.sidebar', [
            'user' => $user,
            'type' => 'patient',
            'currentRoute' => Route::currentRouteName(),
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Main Content -->
        <main class="flex-1 p-6 sm:p-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="flex flex-wrap justify-between gap-4 items-start mb-8">
                    <div class="flex min-w-72 flex-col gap-2">
                        <h1
                            class="text-body-text dark:text-body-text-dark text-4xl font-black leading-tight tracking-[-0.033em]">
                            <?php echo e(__('patient.penalties.title')); ?>

                        </h1>
                        <p class="text-neutral-text dark:text-neutral-text-dark text-base font-normal leading-normal">
                            <?php echo e(__('patient.penalties.subtitle')); ?>

                        </p>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <button
                            class="flex items-center justify-center gap-2 h-10 px-4 text-body-text dark:text-body-text-dark bg-card-light dark:bg-card-dark border border-border-light dark:border-border-dark rounded-lg text-sm font-medium hover:bg-background-light dark:hover:bg-background-dark transition">
                            <?php echo e(__('patient.penalties.read_policy')); ?>

                        </button>
                        <button
                            class="flex items-center justify-center gap-2 h-10 px-4 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition">
                            <?php echo e(__('patient.penalties.contact_support')); ?>

                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <div
                        class="flex flex-col gap-2 rounded-xl p-6 border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark">
                        <p class="text-body-text dark:text-body-text-dark text-base font-medium leading-normal">
                            <?php echo e(__('patient.penalties.total_penalties')); ?>

                        </p>
                        <p class="text-body-text dark:text-body-text-dark tracking-light text-3xl font-bold leading-tight">
                            3</p>
                    </div>
                    <div
                        class="flex flex-col gap-2 rounded-xl p-6 border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark">
                        <p class="text-body-text dark:text-body-text-dark text-base font-medium leading-normal">
                            <?php echo e(__('patient.penalties.total_amount_due')); ?>

                        </p>
                        <p class="text-red-600 dark:text-red-400 tracking-light text-3xl font-bold leading-tight">
                            $5.00</p>
                    </div>
                </div>

                <!-- Penalty Records Table -->
                <div class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark">
                    <!-- Table Header -->
                    <div
                        class="flex justify-between items-center gap-2 p-4 border-b border-border-light dark:border-border-dark">
                        <p class="text-lg font-semibold text-body-text dark:text-body-text-dark">
                            <?php echo e(__('patient.penalties.penalty_record')); ?>

                        </p>
                        <div class="flex gap-2">
                            <button
                                class="p-2 rounded-lg text-body-text dark:text-body-text-dark border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark hover:bg-background-light dark:hover:bg-background-dark transition">
                                <span class="material-symbols-outlined">calendar_today</span>
                            </button>
                            <button
                                class="p-2 rounded-lg text-body-text dark:text-body-text-dark border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark hover:bg-background-light dark:hover:bg-background-dark transition">
                                <span class="material-symbols-outlined">filter_list</span>
                            </button>
                        </div>
                    </div>

                    <!-- Table Content -->
                    <div class="p-2 @container">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-border-light dark:border-border-dark">
                                        <th
                                            class="px-4 py-3 text-left text-neutral-text dark:text-neutral-text-dark text-sm font-medium leading-normal">
                                            <?php echo e(__('patient.penalties.date')); ?>

                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-neutral-text dark:text-neutral-text-dark text-sm font-medium leading-normal">
                                            <?php echo e(__('patient.penalties.reason')); ?>

                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-neutral-text dark:text-neutral-text-dark text-sm font-medium leading-normal">
                                            <?php echo e(__('patient.penalties.order_number')); ?>

                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-neutral-text dark:text-neutral-text-dark text-sm font-medium leading-normal">
                                            <?php echo e(__('patient.penalties.consequence')); ?>

                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-neutral-text dark:text-neutral-text-dark text-sm font-medium leading-normal">
                                            <?php echo e(__('patient.penalties.action')); ?>

                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Row 1 -->
                                    <tr
                                        class="border-b border-border-light dark:border-border-dark hover:bg-background-light dark:hover:bg-background-dark/50 transition">
                                        <td
                                            class="h-[72px] px-4 py-2 text-neutral-text dark:text-neutral-text-dark text-sm font-normal leading-normal">
                                            15 Oct 2023
                                        </td>
                                        <td class="h-[72px] px-4 py-2">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="material-symbols-outlined text-orange-500 text-base">warning</span>
                                                <span
                                                    class="inline-flex items-center rounded-full bg-orange-100 dark:bg-orange-900/50 px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:text-orange-300">
                                                    <?php echo e(__('patient.penalties.reason_types.late_cancellation')); ?>

                                                </span>
                                            </div>
                                        </td>
                                        <td
                                            class="h-[72px] px-4 py-2 text-neutral-text dark:text-neutral-text-dark text-sm font-normal leading-normal">
                                            TAS-1024
                                        </td>
                                        <td class="h-[72px] px-4 py-2">
                                            <div class="flex flex-col">
                                                <span class="text-body-text dark:text-body-text-dark font-medium">
                                                    <?php echo e(__('patient.penalties.consequence_types.warning_issued')); ?>

                                                </span>
                                                <span class="text-neutral-text dark:text-neutral-text-dark text-xs">
                                                    <?php echo e(__('patient.penalties.consequence_types.no_charge')); ?>

                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-[72px] px-4 py-2">
                                            <a href="#"
                                                class="text-primary text-sm font-bold leading-normal tracking-[0.015em] hover:underline">
                                                <?php echo e(__('patient.penalties.view_details')); ?>

                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Row 2 -->
                                    <tr
                                        class="border-b border-border-light dark:border-border-dark hover:bg-background-light dark:hover:bg-background-dark/50 transition">
                                        <td
                                            class="h-[72px] px-4 py-2 text-neutral-text dark:text-neutral-text-dark text-sm font-normal leading-normal">
                                            28 Sep 2023
                                        </td>
                                        <td class="h-[72px] px-4 py-2">
                                            <div class="flex items-center gap-2">
                                                <span class="material-symbols-outlined text-red-500 text-base">error</span>
                                                <span
                                                    class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900/50 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-300">
                                                    <?php echo e(__('patient.penalties.reason_types.missed_payment')); ?>

                                                </span>
                                            </div>
                                        </td>
                                        <td
                                            class="h-[72px] px-4 py-2 text-neutral-text dark:text-neutral-text-dark text-sm font-normal leading-normal">
                                            TAS-0956
                                        </td>
                                        <td class="h-[72px] px-4 py-2">
                                            <div class="flex flex-col">
                                                <span class="text-red-600 dark:text-red-400 font-bold">$5.00</span>
                                                <span class="text-neutral-text dark:text-neutral-text-dark text-xs">
                                                    <?php echo e(__('patient.penalties.consequence_types.cancellation_fee')); ?>

                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-[72px] px-4 py-2">
                                            <a href="#"
                                                class="text-primary text-sm font-bold leading-normal tracking-[0.015em] hover:underline">
                                                <?php echo e(__('patient.penalties.view_details')); ?>

                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Row 3 -->
                                    <tr class="hover:bg-background-light dark:hover:bg-background-dark/50 transition">
                                        <td
                                            class="h-[72px] px-4 py-2 text-neutral-text dark:text-neutral-text-dark text-sm font-normal leading-normal">
                                            12 Aug 2023
                                        </td>
                                        <td class="h-[72px] px-4 py-2">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="material-symbols-outlined text-orange-500 text-base">warning</span>
                                                <span
                                                    class="inline-flex items-center rounded-full bg-orange-100 dark:bg-orange-900/50 px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:text-orange-300">
                                                    <?php echo e(__('patient.penalties.reason_types.late_cancellation')); ?>

                                                </span>
                                            </div>
                                        </td>
                                        <td
                                            class="h-[72px] px-4 py-2 text-neutral-text dark:text-neutral-text-dark text-sm font-normal leading-normal">
                                            TAS-0812
                                        </td>
                                        <td class="h-[72px] px-4 py-2">
                                            <div class="flex flex-col">
                                                <span class="text-body-text dark:text-body-text-dark font-medium">
                                                    <?php echo e(__('patient.penalties.consequence_types.warning_issued')); ?>

                                                </span>
                                                <span class="text-neutral-text dark:text-neutral-text-dark text-xs">
                                                    <?php echo e(__('patient.penalties.consequence_types.no_charge')); ?>

                                                </span>
                                            </div>
                                        </td>
                                        <td class="h-[72px] px-4 py-2">
                                            <a href="#"
                                                class="text-primary text-sm font-bold leading-normal tracking-[0.015em] hover:underline">
                                                <?php echo e(__('patient.penalties.view_details')); ?>

                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/patient/penalties.blade.php ENDPATH**/ ?>