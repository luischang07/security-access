<!-- Orders Management -->
<div class="flex justify-between items-center mb-6">
  <div>
    <h1 class="text-3xl font-bold text-body-text dark:text-body-text-dark">
      <?php echo e(__('admin.orders.title')); ?>

    </h1>
    <p class="text-neutral-text dark:text-neutral-text-dark mt-1">
      <?php echo e(__('admin.orders.subtitle')); ?>

    </p>
  </div>
  <div class="flex gap-2">
    <button
      class="flex items-center gap-2 px-4 py-2 border border-border-light dark:border-border-dark rounded-lg hover:bg-background-light dark:hover:bg-background-dark transition-colors">
      <span class="material-symbols-outlined">file_download</span>
      <?php echo e(__('admin.orders.export')); ?>

    </button>
  </div>
</div>

<!-- Stats -->
<div id="spa-stats" x-data="adminStats(<?php echo e(json_encode($stats)); ?>)" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      <?php echo e(__('admin.orders.total_orders')); ?>

    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.total_orders"></p>
    <p class="text-sm font-medium text-success flex items-center gap-1">
      <span class="material-symbols-outlined text-base">arrow_upward</span> +8.2%
    </p>
  </div>
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      <?php echo e(__('admin.orders.pending_orders')); ?>

    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.pending_orders"></p>
    <p class="text-sm font-medium text-warning flex items-center gap-1">
      <span class="material-symbols-outlined text-base">schedule</span> <?php echo e(__('admin.orders.needs_attention')); ?>

    </p>
  </div>
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      <?php echo e(__('admin.orders.completed_today')); ?>

    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.completed_today"></p>
    <p class="text-sm font-medium text-success flex items-center gap-1">
      <span class="material-symbols-outlined text-base">check_circle</span> <?php echo e(__('admin.orders.on_track')); ?>

    </p>
  </div>
  <div
    class="flex flex-col gap-2 rounded-xl bg-card-light dark:bg-card-dark p-6 shadow-sm border border-border-light dark:border-border-dark">
    <p class="text-base font-medium text-neutral-text dark:text-neutral-text-dark">
      <?php echo e(__('admin.orders.avg_fulfillment')); ?>

    </p>
    <p class="text-4xl font-bold text-body-text dark:text-body-text-dark" x-text="stats.avg_fulfillment_hours + 'h'">
    </p>
    <p class="text-sm font-medium text-success flex items-center gap-1">
      <span class="material-symbols-outlined text-base">trending_down</span> -15min
    </p>
  </div>
</div>

<!-- Filters -->
<div x-data="adminFilters(<?php echo e(json_encode($filters)); ?>)" x-init="init()"
  class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm mb-6 p-4">
  <form x-ref="filterForm" action="<?php echo e(route('admin.orders')); ?>" method="GET">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
      <div>
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          <?php echo e(__('common.filters.search')); ?>

        </label>
        <input type="text" name="search" x-model="filters.search"
          placeholder="<?php echo e(__('admin.orders.search_placeholder')); ?>"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
      </div>
      <div>
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          <?php echo e(__('admin.orders.filter_status')); ?>

        </label>
        <select name="status" x-model="filters.status"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
          <option value=""><?php echo e(__('common.filters.all')); ?></option>
          <option value="pendiente"><?php echo e(__('admin.orders.status.pending')); ?></option>
          <option value="en_proceso"><?php echo e(__('admin.orders.status.in_progress')); ?></option>
          <option value="listo"><?php echo e(__('admin.orders.status.ready')); ?></option>
          <option value="completado"><?php echo e(__('admin.orders.status.completed')); ?></option>
          <option value="cancelado"><?php echo e(__('admin.orders.status.cancelled')); ?></option>
        </select>
      </div>
      <div>
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          <?php echo e(__('admin.orders.filter_pharmacy')); ?>

        </label>
        <select name="pharmacy" x-model="filters.pharmacy"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
          <option value=""><?php echo e(__('common.filters.all')); ?></option>
          <?php $__currentLoopData = $chains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chainId => $chainName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($chainId); ?>"><?php echo e($chainName); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div>
        <label class="text-sm font-medium text-neutral-text dark:text-neutral-text-dark mb-2 block">
          <?php echo e(__('admin.orders.filter_date')); ?>

        </label>
        <select name="date" x-model="filters.date"
          class="w-full px-4 py-2 rounded-lg bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
          <option value=""><?php echo e(__('common.filters.all')); ?></option>
          <option value="today"><?php echo e(__('admin.orders.date_today')); ?></option>
          <option value="week"><?php echo e(__('admin.orders.date_week')); ?></option>
          <option value="month"><?php echo e(__('admin.orders.date_month')); ?></option>
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

<!-- Orders Table -->
<div id="spa-results"
  class="bg-card-light dark:bg-card-dark rounded-xl border border-border-light dark:border-border-dark shadow-sm overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-background-light dark:bg-background-dark">
        <tr>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            <?php echo e(__('admin.orders.table.order_id')); ?>

          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            <?php echo e(__('admin.orders.table.patient')); ?>

          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            <?php echo e(__('admin.orders.table.pharmacy')); ?>

          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            <?php echo e(__('admin.orders.table.items')); ?>

          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            <?php echo e(__('admin.orders.table.total')); ?>

          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            <?php echo e(__('admin.orders.table.status')); ?>

          </th>
          <th
            class="px-6 py-3 text-left text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            <?php echo e(__('admin.orders.table.date')); ?>

          </th>
          <th
            class="px-6 py-3 text-right text-xs font-medium text-neutral-text dark:text-neutral-text-dark uppercase tracking-wider">
            <?php echo e(__('common.actions.title')); ?>

          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-border-light dark:divide-border-dark">
        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <?php
            $statusColors = [
              'pendiente' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
              'en_proceso' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
              'listo' => 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200',
              'completado' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
              'cancelado' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
            ];
            $statusKey = strtolower($order->estado);
            $statusColor = $statusColors[$statusKey] ?? 'bg-gray-100 text-gray-800';
          ?>
          <tr class="hover:bg-background-light dark:hover:bg-background-dark transition-colors">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-primary">
                <?php echo e($order->pedido_id); ?>

              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                  <span class="text-primary text-xs font-medium">
                    <?php echo e(substr($order->paciente->nombre ?? 'U', 0, 1)); ?>

                  </span>
                </div>
                <div class="ml-3">
                  <div class="text-sm font-medium text-body-text dark:text-body-text-dark">
                    <?php echo e($order->paciente->nombre ?? 'N/A'); ?> <?php echo e($order->paciente->apellido ?? 'N/A'); ?>

                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-body-text dark:text-body-text-dark">
                <?php echo e($order->sucursal->nombre ?? 'N/A'); ?>

              </div>
              <div class="text-xs text-neutral-text dark:text-neutral-text-dark">
                <?php echo e($order->sucursal->cadena->name ?? 'Cadena N/A'); ?>

              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-body-text dark:text-body-text-dark">
                <?php echo e($order->lineasPedidos->count()); ?> <?php echo e(__('admin.orders.items')); ?>

              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-body-text dark:text-body-text-dark">
                $<?php echo e(number_format($order->costo_total, 2)); ?>

              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($statusColor); ?>">
                <?php echo e(ucfirst($order->estado)); ?>

              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-body-text dark:text-body-text-dark">
                <?php echo e($order->fecha_pedido ? $order->fecha_pedido->format('M d, Y') : 'N/A'); ?>

              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button class="text-primary hover:text-primary/80"><?php echo e(__('common.actions.view')); ?></button>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="8" class="px-6 py-12 text-center">
              <div class="text-neutral-text dark:text-neutral-text-dark">
                <?php echo e(__('admin.orders.no_results')); ?>

              </div>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <div x-data="adminPagination(<?php echo e(json_encode($orders)); ?>)"
    class="px-6 py-4 border-t border-border-light dark:border-border-dark">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="text-sm text-neutral-text dark:text-neutral-text-dark">
        <?php echo e(__('common.pagination.showing')); ?> <span class="font-medium" x-text="pagination.from"></span>
        <?php echo e(__('common.pagination.to')); ?> <span class="font-medium" x-text="pagination.to"></span>
        <?php echo e(__('common.pagination.of')); ?> <span class="font-medium" x-text="pagination.total"></span>
        <?php echo e(__('common.pagination.results')); ?>

      </div>
      <div class="flex items-center gap-2">
        <label class="text-sm text-neutral-text dark:text-neutral-text-dark">
          <?php echo e(__('common.filters.per_page')); ?>:
        </label>
        <select x-model="perPage" @change="changePerPage()"
          class="px-3 py-1 rounded-lg border border-border-light dark:border-border-dark bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark focus:outline-none focus:ring-2 focus:ring-primary">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
        </select>
      </div>
      <div class="flex gap-2">
        <template x-for="link in pagination.links" :key="link.label">
          <button @click="changePage(link.url)" :disabled="!link.url" :class="{
                            'bg-primary text-white': link.active,
                            'border border-border-light dark:border-border-dark text-neutral-text dark:text-neutral-text-dark hover:bg-background-light dark:hover:bg-background-dark':
                                !link.active,
                            'opacity-50 cursor-not-allowed': !link.url
                        }" class="px-3 py-1 rounded-lg transition-colors" x-html="link.label">
          </button>
        </template>
      </div>
    </div>
  </div>
</div><?php /**PATH C:\xampp\htdocs\laravel\Te-Acerco-Salud\resources\views\admin\partials\orders-content.blade.php ENDPATH**/ ?>