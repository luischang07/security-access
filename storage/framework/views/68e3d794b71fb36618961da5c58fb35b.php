<div class="max-w-7xl mx-auto">
  <!-- Error/Warning Alert -->
  <?php if(session('error')): ?>
    <div class="mb-6 rounded-lg border border-warning/40 bg-warning/10 px-4 py-3 text-sm text-warning dark:border-warning/30 dark:bg-warning/15">
      <div class="flex gap-3">
        <span class="material-symbols-outlined text-xl mt-0.5 flex-shrink-0">warning</span>
        <div>
          <p class="font-semibold"><?php echo e(session('error')); ?></p>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Header -->
  <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
    <div class="flex min-w-72 flex-col gap-2">
      <p class="text-gray-900 dark:text-white text-3xl sm:text-4xl font-black leading-tight tracking-[-0.033em]">
        <?php echo e(__('patient.dashboard.welcome', ['name' => Auth::user()->nombre])); ?>

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
      <div class="bg-white dark:bg-gray-900/50 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
        <?php $__empty_1 = true; $__currentLoopData = $pedidosActivosConProgreso; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-4 <?php echo e(!$loop->last ? 'border-b border-gray-200 dark:border-gray-800' : ''); ?>">
            <div class="flex-1">
              <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">
                Order #<?php echo e(str_pad($item['pedido']->pedido_id, 5, '0', STR_PAD_LEFT)); ?>

              </p>
              <p class="text-gray-900 dark:text-white font-semibold mt-1">
                <?php echo e($item['progress']['label']); ?>

              </p>
              <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                <?php echo e($item['pedido']->fecha_pedido->format('d/m/Y')); ?>

              </p>
            </div>
            <div class="flex-1 w-full">
              <div class="relative w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                <div class="absolute top-0 left-0 h-2 <?php echo e($item['progress']['color']); ?> rounded-full"
                  style="width: <?php echo e($item['progress']['width']); ?>;"></div>
              </div>
            </div>
            <a class="text-primary font-bold text-sm whitespace-nowrap"
              href="<?php echo e(route('patient.orders')); ?>"><?php echo e(__('patient.dashboard.active_orders.view_details')); ?></a>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <div class="text-center py-8">
            <span class="material-symbols-outlined text-gray-400 text-5xl mb-2">receipt_long</span>
            <p class="text-gray-500 dark:text-gray-400">
              <?php echo e(__('patient.dashboard.active_orders.no_orders')); ?>

            </p>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Recent History & Account Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Recent History -->
      <section class="lg:col-span-2">
        <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-1 pb-4">
          <?php echo e(__('patient.dashboard.recent_history.title')); ?>

        </h2>
        <div
          class="bg-white dark:bg-gray-900/50 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
          <!-- History Items -->
          <?php $__empty_1 = true; $__currentLoopData = $historialReciente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center gap-4 p-4 border-b border-gray-200 dark:border-gray-800 last:border-b-0">
              <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">
                  <?php echo e($pedido->estatus === 'entregado' ? 'check_circle' : 'cancel'); ?>

                </span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-gray-900 dark:text-white text-sm font-bold truncate">
                  Order #<?php echo e(str_pad($pedido->pedido_id, 5, '0', STR_PAD_LEFT)); ?>

                </p>
                <p class="text-gray-500 dark:text-gray-400 text-xs">
                  <?php echo e($pedido->estatus === 'entregado' ? __('patient.dashboard.recent_history.completed') : __('patient.dashboard.recent_history.cancelled')); ?>

                  - <?php echo e($pedido->fecha_pedido->format('d/m/Y')); ?>

                </p>
              </div>
              <p class="text-gray-900 dark:text-white text-sm font-bold whitespace-nowrap">
                $<?php echo e(number_format($pedido->costo_total, 2)); ?>

              </p>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-8">
              <p class="text-gray-500 dark:text-gray-400">
                <?php echo e(__('patient.dashboard.recent_history.no_history')); ?>

              </p>
            </div>
          <?php endif; ?>

          <?php if($historialReciente->count() > 0): ?>
            <div class="p-4 text-center">
              <a href="<?php echo e(route('patient.orders.history')); ?>" class="text-primary font-bold text-sm">
                <?php echo e(__('patient.dashboard.recent_history.view_all')); ?>

              </a>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <!-- Account Summary -->
      <section>
        <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-1 pb-4">
          <?php echo e(__('patient.dashboard.account_summary.title')); ?>

        </h2>
        <div
          class="bg-white dark:bg-gray-900/50 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 space-y-4">
          <div class="flex justify-between items-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">
              <?php echo e(__('patient.dashboard.account_summary.active_orders')); ?>

            </p>
            <p class="text-gray-900 dark:text-white font-bold"><?php echo e($pedidosActivosConProgreso->count()); ?></p>
          </div>
          <div class="flex justify-between items-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">
              <?php echo e(__('patient.dashboard.account_summary.completed')); ?>

            </p>
            <p class="text-gray-900 dark:text-white font-bold"><?php echo e($pedidosCompletados); ?></p>
          </div>
          <div class="flex justify-between items-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">
              <?php echo e(__('patient.dashboard.account_summary.penalties')); ?>

            </p>
            <p
              class="text-<?php echo e($paciente->penalizacion > 0 ? 'red' : 'gray'); ?>-900 dark:text-<?php echo e($paciente->penalizacion > 0 ? 'red' : 'white'); ?>-500 font-bold">
              $<?php echo e(number_format($paciente->penalizacion, 2)); ?>

            </p>
          </div>
          <a href="<?php echo e(route('patient.profile')); ?>"
            class="flex items-center justify-center h-10 w-full rounded-lg border border-gray-300 dark:border-gray-600 text-sm font-bold text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition mt-4">
            <?php echo e(__('patient.dashboard.account_summary.view_profile')); ?>

          </a>
        </div>
      </section>
    </div>
  </div>
</div><?php /**PATH /Users/franciscomejia/Documents/PROYECTOS TEC/Te-Acerco-Salud/resources/views/patient/partials/dashboard-content.blade.php ENDPATH**/ ?>