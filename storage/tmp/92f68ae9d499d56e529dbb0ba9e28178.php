<!-- Users Management -->
<div id="spa-stats" x-data="adminStats(<?php echo e(json_encode($stats)); ?>)" class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-body-text dark:text-body-text-dark">
                <?php echo e(__('admin.users.title')); ?>

            </h1>
            <p class="text-neutral-text dark:text-neutral-text-dark mt-1">
                <?php echo e(__('admin.users.subtitle')); ?>

            </p>
        </div>
        <div class="flex items-center gap-4">
            <!-- Stats Summary -->
            <div class="flex gap-4 text-sm">
                <div class="text-center">
                    <div class="font-bold text-body-text dark:text-body-text-dark" x-text="stats.total">
                        <?php echo e($stats['total']); ?></div>
                    <div class="text-neutral-text dark:text-neutral-text-dark"><?php echo e(__('admin.users.stats.total')); ?>

                    </div>
                </div>
                <div class="text-center">
                    <div class="font-bold text-green-600 dark:text-green-400" x-text="stats.active">
                        <?php echo e($stats['active']); ?></div>
                    <div class="text-neutral-text dark:text-neutral-text-dark"><?php echo e(__('admin.users.stats.active')); ?>

                    </div>
                </div>
                <div class="text-center">
                    <div class="font-bold text-red-600 dark:text-red-400" x-text="stats.locked">
                        <?php echo e($stats['locked']); ?></div>
                    <div class="text-neutral-text dark:text-neutral-text-dark"><?php echo e(__('admin.users.stats.locked')); ?>

                    </div>
                </div>
            </div>
            <?php if(isset($stats['growth_percentage'])): ?>
                <div class="flex items-center gap-1 text-sm" :class="getGrowthClass(<?php echo e($stats['growth_percentage']); ?>)">
                    <span class="material-symbols-outlined text-base"
                        x-text="getGrowthIcon(<?php echo e($stats['growth_percentage']); ?>)">
                        <?php echo e($stats['growth_percentage'] > 0 ? 'trending_up' : ($stats['growth_percentage'] < 0 ? 'trending_down' : 'trending_flat')); ?>

                    </span>
                    <span class="font-semibold"><?php echo e(abs($stats['growth_percentage'])); ?>%</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Filters -->
<div x-data="adminFilters(<?php echo e(json_encode($filters)); ?>)">
    <form method="GET" action="<?php echo e(route('admin.users')); ?>" x-ref="filterForm"
        class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search"
                    class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
                    <?php echo e(__('common.filters.search')); ?>

                </label>
                <input type="text" id="search" name="search" x-model="filters.search"
                    placeholder="<?php echo e(__('admin.users.search_placeholder')); ?>"
                    class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label for="role"
                    class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
                    <?php echo e(__('admin.users.filter_role')); ?>

                </label>
                <select id="role" name="role" x-model="filters.role"
                    class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value=""><?php echo e(__('common.filters.all')); ?></option>
                    <option value="paciente"><?php echo e(__('admin.users.roles.patient')); ?></option>
                    <option value="administrador"><?php echo e(__('admin.users.roles.administrator')); ?></option>
                    <option value="empleado"><?php echo e(__('admin.users.roles.employee')); ?></option>
                </select>
            </div>
            <div>
                <label for="status"
                    class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
                    <?php echo e(__('admin.users.filter_status')); ?>

                </label>
                <select id="status" name="status" x-model="filters.status"
                    class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value=""><?php echo e(__('common.filters.all')); ?></option>
                    <option value="active"><?php echo e(__('common.status.active')); ?></option>
                    <option value="inactive"><?php echo e(__('common.status.inactive')); ?></option>
                    <option value="locked"><?php echo e(__('common.status.suspended')); ?></option>
                </select>
            </div>
            <div>
                <label for="per_page"
                    class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
                    <?php echo e(__('common.filters.per_page')); ?>

                </label>
                <select id="per_page" name="per_page" x-model="filters.per_page"
                    class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="button" @click="clearFilters"
                    class="px-4 py-2 border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark rounded-lg hover:bg-background-light dark:hover:bg-background-dark transition-colors">
                    <?php echo e(__('common.actions.clear')); ?>

                </button>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div x-show="isLoading" x-cloak
            class="mt-4 flex items-center justify-center gap-2 text-sm text-neutral-text dark:text-neutral-text-dark">
            <span class="material-symbols-outlined animate-spin">progress_activity</span>
            <span><?php echo e(__('common.loading')); ?></span>
        </div>
    </form>
</div>

<!-- Users Table -->
<div id="spa-results"
    class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-background-light dark:bg-background-dark">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
                        <?php echo e(__('admin.users.table.user')); ?>

                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
                        <?php echo e(__('admin.users.table.email')); ?>

                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
                        <?php echo e(__('admin.users.table.role')); ?>

                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
                        <?php echo e(__('admin.users.table.status')); ?>

                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
                        <?php echo e(__('admin.users.table.registered')); ?>

                    </th>
                    <th
                        class="px-6 py-3 text-right text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
                        <?php echo e(__('common.actions.title')); ?>

                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-light dark:divide-border-dark">
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-background-light dark:hover:bg-background-dark transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                    <span
                                        class="text-primary font-medium"><?php echo e(strtoupper(substr($user['nombre'], 0, 2))); ?></span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-body-text dark:text-body-text-dark">
                                        <?php echo e($user['nombre']); ?>

                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-body-text dark:text-body-text-dark"><?php echo e($user['correo']); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex gap-1 flex-wrap">
                                <?php $__currentLoopData = $user['roles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?php echo e($role['color']); ?>-100 dark:bg-<?php echo e($role['color']); ?>-900 text-<?php echo e($role['color']); ?>-800 dark:text-<?php echo e($role['color']); ?>-200">
                                        <?php echo e($role['label']); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($user['status'] === 'locked'): ?>
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                    <?php echo e(__('common.status.suspended')); ?>

                                </span>
                            <?php elseif($user['status'] === 'active'): ?>
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                    <?php echo e(__('common.status.active')); ?>

                                </span>
                            <?php else: ?>
                                <span
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                                    <?php echo e(__('common.status.inactive')); ?>

                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-text dark:text-neutral-text-dark">
                            <?php echo e($user['created_at']->format('M d, Y')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button
                                class="text-primary hover:text-primary/80 mr-3"><?php echo e(__('common.actions.edit')); ?></button>
                            <button
                                class="text-danger hover:text-danger/80"><?php echo e(__('common.actions.delete')); ?></button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6"
                            class="px-6 py-8 text-center text-neutral-text dark:text-neutral-text-dark">
                            <?php echo e(__('admin.users.no_users_found')); ?>

                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-border-light dark:border-border-dark">
        <div class="flex items-center justify-between">
            <div class="text-sm text-neutral-text dark:text-neutral-text-dark">
                <?php if($pagination['total'] > 0): ?>
                    <?php echo e(__('common.pagination.showing')); ?> <span class="font-medium"><?php echo e($pagination['from']); ?></span>
                    <?php echo e(__('common.pagination.to')); ?> <span class="font-medium"><?php echo e($pagination['to']); ?></span>
                    <?php echo e(__('common.pagination.of')); ?> <span class="font-medium"><?php echo e($pagination['total']); ?></span>
                    <?php echo e(__('common.pagination.results')); ?>

                <?php else: ?>
                    <?php echo e(__('admin.users.no_users_found')); ?>

                <?php endif; ?>
            </div>
            <?php if($pagination['last_page'] > 1): ?>
                <div class="flex gap-2">
                    <?php if($pagination['current_page'] > 1): ?>
                        <a href="<?php echo e(route('admin.users', array_merge(request()->except('page'), ['page' => $pagination['current_page'] - 1]))); ?>"
                            class="px-3 py-1 rounded-lg border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">
                            <?php echo e(__('common.pagination.previous')); ?>

                        </a>
                    <?php endif; ?>

                    <?php for($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['last_page'], $pagination['current_page'] + 2); $i++): ?>
                        <?php if($i == $pagination['current_page']): ?>
                            <span class="px-3 py-1 rounded-lg bg-primary text-white"><?php echo e($i); ?></span>
                        <?php else: ?>
                            <a href="<?php echo e(route('admin.users', array_merge(request()->except('page'), ['page' => $i]))); ?>"
                                class="px-3 py-1 rounded-lg border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">
                                <?php echo e($i); ?>

                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if($pagination['current_page'] < $pagination['last_page']): ?>
                        <a href="<?php echo e(route('admin.users', array_merge(request()->except('page'), ['page' => $pagination['current_page'] + 1]))); ?>"
                            class="px-3 py-1 rounded-lg border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark">
                            <?php echo e(__('common.pagination.next')); ?>

                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\laravel\Te-Acerco-Salud\resources\views\admin\partials\users-content.blade.php ENDPATH**/ ?>