<!-- Penalties Management -->
<div class="flex justify-between items-center mb-6">
  <div>
    <h1 class="text-3xl font-bold text-body-text dark:text-body-text-dark">
      <?php echo e(__('admin.penalties.title')); ?>

    </h1>
    <p class="text-neutral-text dark:text-neutral-text-dark mt-1">
      <?php echo e(__('admin.penalties.subtitle')); ?>

    </p>
  </div>
  <button
    class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity">
    <span class="material-symbols-outlined">add</span>
    <?php echo e(__('admin.penalties.issue_penalty')); ?>

  </button>
</div>

<!-- Stats -->
<div id="spa-stats" x-data="adminStats(<?php echo e(json_encode($stats)); ?>)" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      <?php echo e(__('admin.penalties.total_penalties')); ?>

    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.total_penalties"></p>
    <p class="text-sm font-medium text-danger flex items-center gap-1">
      <!-- #TODO: Add percentage -->
      <span class="material-symbols-outlined text-base">arrow_upward</span> +12%
    </p>
  </div>
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      <?php echo e(__('admin.penalties.active_penalties')); ?>

    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.active_penalties"></p>
  </div>
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      <?php echo e(__('admin.penalties.resolved_penalties')); ?>

    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.resolved_penalties"></p>
    <p class="text-sm font-medium text-success flex items-center gap-1">
      <!-- #TODO: Add percentage -->
      <span class="material-symbols-outlined text-base">check_circle</span> 69%
    </p>
  </div>
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      <?php echo e(__('admin.penalties.avg_resolution_time')); ?>

    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.avg_resolution_days + 'd'"></p>
  </div>
</div>

<!-- Filters -->
<div x-data="adminFilters(<?php echo e(json_encode($filters)); ?>)" x-init="init()"
  class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm mb-6 p-4">
  <form x-ref="filterForm" action="<?php echo e(route('admin.penalties')); ?>" method="GET">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="md:col-span-2">
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          <?php echo e(__('common.filters.search')); ?>

        </label>
        <input type="text" name="search" x-model="filters.search"
          placeholder="<?php echo e(__('admin.penalties.search_placeholder')); ?>"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
      </div>
      <div>
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          <?php echo e(__('admin.penalties.filter_status')); ?>

        </label>
        <select name="status" x-model="filters.status"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
          <option value=""><?php echo e(__('common.filters.all')); ?></option>
          <option value="activa"><?php echo e(__('admin.penalties.status.active')); ?></option>
          <option value="pagada"><?php echo e(__('admin.penalties.status.paid')); ?></option>
          <option value="liberada"><?php echo e(__('admin.penalties.status.waived')); ?></option>
        </select>
      </div>
      <div class="flex items-end">
        <button type="button" @click="clearFilters()"
          class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-opacity">
          <?php echo e(__('common.actions.clear')); ?>

        </button>
      </div>
    </div>
    <input type="hidden" name="per_page" x-model="filters.per_page">
  </form>
</div>

<!-- Penalties Table -->
<div id="spa-results"
  class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-border-light dark:divide-border-dark">
      <thead class="bg-background-light dark:bg-background-dark">
        <tr>
          <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            <?php echo e(__('admin.penalties.table.id')); ?>

          </th>
          <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            <?php echo e(__('admin.penalties.table.patient')); ?>

          </th>
          <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            <?php echo e(__('admin.penalties.table.reason')); ?>

          </th>
          <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            <?php echo e(__('admin.penalties.table.amount')); ?>

          </th>
          <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            <?php echo e(__('admin.penalties.table.status')); ?>

          </th>
          <th scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            <?php echo e(__('admin.penalties.table.date')); ?>

          </th>
          <th scope="col" class="relative px-6 py-3">
            <span class="sr-only"><?php echo e(__('common.actions.actions')); ?></span>
          </th>
        </tr>
      </thead>
      <tbody class="bg-card-light dark:bg-card-dark divide-y divide-border-light dark:divide-border-dark">
        <?php $__empty_1 = true; $__currentLoopData = $penalties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $penalty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <?php
            $statusColors = [
              'active' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
              'paid' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
              'waived' => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200',
            ];
          ?>
          <tr class="hover:bg-background-light dark:hover:bg-background-dark transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-primary">
                #PEN-<?php echo e(str_pad($penalty->id, 4, '0', STR_PAD_LEFT)); ?>

              </div>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center">
                <div class="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center">
                  <span class="material-symbols-outlined text-primary">person</span>
                </div>
                <div class="ml-3">
                  <div class="text-sm font-medium text-body-text dark:text-body-text-dark">
                    <?php echo e($penalty->patient->nombre ?? 'Unknown'); ?> <?php echo e($penalty->patient->apellido ?? ''); ?>

                  </div>
                  <div class="text-xs text-neutral-text dark:text-neutral-text-dark">
                    <?php echo e($penalty->patient->user->email ?? ''); ?>

                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-body-text dark:text-body-text-dark max-w-xs">
                <?php echo e($penalty->reason); ?>

              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-body-text dark:text-body-text-dark">
                $<?php echo e(number_format($penalty->amount, 2)); ?>

              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($statusColors[$penalty->status] ?? 'bg-gray-100 text-gray-800'); ?>">
                <?php echo e(ucfirst($penalty->status)); ?>

              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-body-text dark:text-body-text-dark">
                <?php echo e($penalty->created_at->format('M d, Y')); ?>

              </div>
              <div class="text-xs text-neutral-text dark:text-neutral-text-dark">
                <?php echo e($penalty->created_at->format('H:i')); ?>

              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button class="text-primary hover:text-primary/80 mr-3"><?php echo e(__('common.actions.view')); ?></button>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="7" class="px-6 py-12 text-center">
              <div class="text-neutral-text dark:text-neutral-text-dark">
                <?php echo e(__('admin.penalties.no_results')); ?>

              </div>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if($penalties->hasPages()): ?>
    <div
      class="px-6 py-4 border-t border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark">
      <?php echo e($penalties->links('vendor.pagination.tailwind')); ?>

    </div>
  <?php endif; ?>
</div><?php /**PATH C:\xampp\htdocs\laravel\Te-Acerco-Salud\resources\views\admin\partials\penalties-content.blade.php ENDPATH**/ ?>