<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div class="flex flex-col gap-1">
            <h1 class="text-body-text dark:text-body-text-dark text-3xl font-black leading-tight tracking-[-0.033em]">
                <?php echo e(__('patient.orders.menu_title')); ?>

            </h1>
            <p class="text-neutral-text dark:text-neutral-text-dark text-base font-normal leading-normal">
                <?php echo e(__('patient.orders.subtitle')); ?>

            </p>
        </div>
    </div>

    <!-- Current Order Section -->
    <section class="mb-10">
        <h2 class="text-body-text dark:text-body-text-dark text-xl font-bold leading-tight tracking-[-0.015em] mb-4">
            <?php echo e(__('patient.orders.current_order')); ?>

        </h2>
        <div
            class="rounded-xl border border-border-light dark:border-border-dark shadow-sm bg-card-light dark:bg-card-dark p-6">
            <!-- Order Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                <div>
                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                        <?php echo e(__('patient.orders.order_id')); ?>: TAS-1138-9901
                    </p>
                    <p class="text-2xl font-bold text-body-text dark:text-body-text-dark">
                        <?php echo e(__('patient.orders.status.ready')); ?>

                    </p>
                </div>
                <div class="flex items-center gap-2 text-sm font-medium text-neutral-text dark:text-neutral-text-dark">
                    <span class="material-symbols-outlined text-base">calendar_today</span>
                    <span><?php echo e(__('patient.orders.ordered_on')); ?>: Oct 26, 2023</span>
                </div>
            </div>

            <!-- Progress Tracker -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <!-- Step 1: Pending -->
                    <div class="flex flex-col items-center relative">
                        <div class="rounded-full flex items-center justify-center size-8 bg-success text-white">
                            <span class="material-symbols-outlined text-lg">check</span>
                        </div>
                        <p class="text-xs font-medium text-center mt-2">
                            <?php echo e(__('patient.orders.status.pending')); ?></p>
                    </div>
                    <div class="flex-1 h-0.5 bg-success"></div>

                    <!-- Step 2: In Process -->
                    <div class="flex flex-col items-center relative">
                        <div class="rounded-full flex items-center justify-center size-8 bg-success text-white">
                            <span class="material-symbols-outlined text-lg">check</span>
                        </div>
                        <p class="text-xs font-medium text-center mt-2">
                            <?php echo e(__('patient.orders.status.in_process')); ?></p>
                    </div>
                    <div class="flex-1 h-0.5 bg-warning"></div>

                    <!-- Step 3: Ready -->
                    <div class="flex flex-col items-center relative">
                        <div class="rounded-full flex items-center justify-center size-8 bg-warning text-white">
                            <span class="material-symbols-outlined text-lg animate-pulse">local_shipping</span>
                        </div>
                        <p class="text-xs font-bold text-center mt-2 text-warning">
                            <?php echo e(__('patient.orders.status.ready')); ?></p>
                    </div>
                    <div class="flex-1 h-0.5 bg-border-light dark:bg-border-dark"></div>

                    <!-- Step 4: Completed -->
                    <div class="flex flex-col items-center relative">
                        <div
                            class="rounded-full flex items-center justify-center size-8 bg-border-light dark:bg-border-dark text-neutral-text dark:text-neutral-text-dark">
                            <span class="material-symbols-outlined text-lg">task_alt</span>
                        </div>
                        <p class="text-xs font-medium text-center mt-2 text-neutral-text dark:text-neutral-text-dark">
                            <?php echo e(__('patient.orders.status.completed')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Pickup Info -->
            <div
                class="bg-background-light dark:bg-background-dark rounded-lg p-4 flex flex-col sm:flex-row justify-between items-start gap-4">
                <div>
                    <p class="text-base font-bold text-body-text dark:text-body-text-dark">
                        <?php echo e(__('patient.orders.estimated_collection')); ?>: Today at 4:30 PM
                    </p>
                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                        Farmacia del Sol - 123 Health St, Suite 100
                    </p>
                    <a href="#"
                        class="text-sm font-bold text-primary hover:underline mt-1 inline-flex items-center gap-1">
                        <?php echo e(__('patient.orders.view_on_map')); ?>

                        <span class="material-symbols-outlined text-base">open_in_new</span>
                    </a>
                </div>
                <button
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-primary text-white text-sm font-bold leading-normal w-full sm:w-auto hover:bg-primary/90 transition">
                    <span class="truncate"><?php echo e(__('patient.orders.view_details')); ?></span>
                </button>
            </div>
        </div>
    </section>

    <!-- Order History Section -->
    <section>
        <h2 class="text-body-text dark:text-body-text-dark text-xl font-bold leading-tight tracking-[-0.015em] mb-4">
            <?php echo e(__('patient.orders.order_history')); ?>

        </h2>

        <!-- Filters -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
            <div class="relative w-full sm:max-w-xs">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-neutral-text dark:text-neutral-text-dark">search</span>
                <input
                    class="w-full h-10 pl-10 pr-4 rounded-lg border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:ring-primary focus:border-primary"
                    placeholder="<?php echo e(__('patient.orders.search_placeholder')); ?>" type="text" />
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select
                    class="w-full sm:w-auto h-10 px-3 rounded-lg border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:ring-primary focus:border-primary">
                    <option><?php echo e(__('patient.orders.filter_by_status')); ?></option>
                    <option><?php echo e(__('patient.orders.status.collected')); ?></option>
                    <option><?php echo e(__('patient.orders.status.cancelled')); ?></option>
                </select>
                <input
                    class="w-full sm:w-auto h-10 px-3 rounded-lg border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark focus:ring-primary focus:border-primary"
                    type="date" />
            </div>
        </div>

        <!-- Order List -->
        <div class="space-y-3">
            <!-- Order 1 -->
            <div
                class="rounded-lg border border-border-light dark:border-border-dark shadow-sm bg-card-light dark:bg-card-dark p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex-1 w-full">
                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                        <?php echo e(__('patient.orders.order_id')); ?>: TAS-1098-7654
                    </p>
                    <p class="text-base font-bold text-body-text dark:text-body-text-dark">
                        Ibuprofen 200mg, Paracetamol 500mg
                    </p>
                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Oct 15, 2023
                    </p>
                </div>
                <div class="flex items-center justify-between w-full sm:w-auto gap-4">
                    <span
                        class="inline-flex items-center rounded-full bg-success/10 px-3 py-1 text-sm font-medium text-success">
                        <?php echo e(__('patient.orders.status.collected')); ?>

                    </span>
                    <button
                        class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-4 bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark text-sm font-medium leading-normal hover:bg-border-light dark:hover:bg-border-dark transition">
                        <span class="truncate"><?php echo e(__('patient.orders.view_details')); ?></span>
                    </button>
                </div>
            </div>

            <!-- Order 2 -->
            <div
                class="rounded-lg border border-border-light dark:border-border-dark shadow-sm bg-card-light dark:bg-card-dark p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex-1 w-full">
                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                        <?php echo e(__('patient.orders.order_id')); ?>: TAS-1097-1234
                    </p>
                    <p class="text-base font-bold text-body-text dark:text-body-text-dark">
                        Amoxicillin 250mg
                    </p>
                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Sep 28, 2023
                    </p>
                </div>
                <div class="flex items-center justify-between w-full sm:w-auto gap-4">
                    <span
                        class="inline-flex items-center rounded-full bg-red-500/10 px-3 py-1 text-sm font-medium text-red-500">
                        <?php echo e(__('patient.orders.status.cancelled')); ?>

                    </span>
                    <button
                        class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-4 bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark text-sm font-medium leading-normal hover:bg-border-light dark:hover:bg-border-dark transition">
                        <span class="truncate"><?php echo e(__('patient.orders.view_details')); ?></span>
                    </button>
                </div>
            </div>

            <!-- Order 3 -->
            <div
                class="rounded-lg border border-border-light dark:border-border-dark shadow-sm bg-card-light dark:bg-card-dark p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex-1 w-full">
                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                        <?php echo e(__('patient.orders.order_id')); ?>: TAS-1095-5566
                    </p>
                    <p class="text-base font-bold text-body-text dark:text-body-text-dark">
                        Aspirin 81mg
                    </p>
                    <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Sep 12, 2023
                    </p>
                </div>
                <div class="flex items-center justify-between w-full sm:w-auto gap-4">
                    <span
                        class="inline-flex items-center rounded-full bg-success/10 px-3 py-1 text-sm font-medium text-success">
                        <?php echo e(__('patient.orders.status.collected')); ?>

                    </span>
                    <button
                        class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-4 bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark text-sm font-medium leading-normal hover:bg-border-light dark:hover:bg-border-dark transition">
                        <span class="truncate"><?php echo e(__('patient.orders.view_details')); ?></span>
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>
<?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/patient/partials/orders-content.blade.php ENDPATH**/ ?>