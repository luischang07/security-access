<!-- Pharmacy Chains Management -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-body-text dark:text-body-text-dark">
            <?php echo e(__('admin.chains.title')); ?>

        </h1>
        <p class="text-neutral-text dark:text-neutral-text-dark mt-1">
            <?php echo e(__('admin.chains.subtitle')); ?>

        </p>
    </div>
    <button
        class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity">
        <span class="material-symbols-outlined">add</span>
        <?php echo e(__('admin.chains.add_chain')); ?>

    </button>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            <?php echo e(__('admin.chains.total_chains')); ?>

        </p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">24</p>
    </div>
    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            <?php echo e(__('admin.chains.total_branches')); ?>

        </p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">186</p>
    </div>
    <div
        class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
        <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
            <?php echo e(__('admin.chains.avg_branches')); ?>

        </p>
        <p class="text-4xl font-bold text-body-text dark:text-body-text-dark">7.8</p>
    </div>
</div>

<!-- Chains Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php for($i = 1; $i <= 9; $i++): ?>
        <div
            class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-lg bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-2xl">local_pharmacy</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-body-text dark:text-body-text-dark">
                            <?php echo e(__('admin.chains.chain_name', ['number' => $i])); ?>

                        </h3>
                        <p class="text-sm text-neutral-text dark:text-neutral-text-dark">
                            ID: CHAIN-<?php echo e(str_pad($i, 4, '0', STR_PAD_LEFT)); ?>

                        </p>
                    </div>
                </div>
                <button class="text-neutral-text dark:text-neutral-text-dark hover:text-primary">
                    <span class="material-symbols-outlined">more_vert</span>
                </button>
            </div>

            <div class="space-y-3 mb-4">
                <div class="flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-neutral-text dark:text-neutral-text-dark text-lg">
                        store
                    </span>
                    <span class="text-body-text dark:text-body-text-dark">
                        <?php echo e($i * 5 + 10); ?> <?php echo e(__('admin.chains.branches')); ?>

                    </span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-neutral-text dark:text-neutral-text-dark text-lg">
                        location_on
                    </span>
                    <span class="text-body-text dark:text-body-text-dark">
                        <?php echo e(__('admin.chains.coverage_regions', ['count' => rand(3, 8)])); ?>

                    </span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-neutral-text dark:text-neutral-text-dark text-lg">
                        badge
                    </span>
                    <span class="text-body-text dark:text-body-text-dark">
                        <?php echo e(rand(50, 300)); ?> <?php echo e(__('admin.chains.employees')); ?>

                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-border-light dark:border-border-dark">
                <span
                    class="px-2 py-1 text-xs font-semibold rounded-full <?php echo e($i % 3 == 0 ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' : 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200'); ?>">
                    <?php echo e($i % 3 == 0 ? __('common.status.pending') : __('common.status.active')); ?>

                </span>
                <div class="flex gap-2">
                    <button class="px-3 py-1 text-sm text-primary hover:bg-primary/10 rounded-lg transition-colors">
                        <?php echo e(__('common.actions.view')); ?>

                    </button>
                    <button class="px-3 py-1 text-sm text-primary hover:bg-primary/10 rounded-lg transition-colors">
                        <?php echo e(__('common.actions.edit')); ?>

                    </button>
                </div>
            </div>
        </div>
    <?php endfor; ?>
</div>

<!-- Pagination -->
<div class="mt-6 flex items-center justify-center">
    <div class="flex gap-2">
        <button
            class="px-3 py-1 rounded-lg border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">
            <?php echo e(__('common.pagination.previous')); ?>

        </button>
        <button class="px-3 py-1 rounded-lg bg-primary text-white">1</button>
        <button
            class="px-3 py-1 rounded-lg border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">
            2
        </button>
        <button
            class="px-3 py-1 rounded-lg border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">
            3
        </button>
        <button
            class="px-3 py-1 rounded-lg border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">
            <?php echo e(__('common.pagination.next')); ?>

        </button>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\laravel\Te-Acerco-Salud\resources\views\admin\partials\chains-content.blade.php ENDPATH**/ ?>