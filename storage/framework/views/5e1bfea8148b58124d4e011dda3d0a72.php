

<?php
  $showMapLinks = $showMapLinks ?? false;
?>

<header
  class="flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 dark:border-gray-800 px-6 sm:px-10 py-3 bg-white dark:bg-background-dark"
  x-data="{ profileOpen: false }" @click.away="profileOpen = false">

  
  <a href="/" class="flex items-center gap-3 text-[#111418] dark:text-white hover:opacity-80 transition-opacity">
    <div class="size-6 text-primary">
      <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 4H17.3334V17.3334H30.6666V30.6666H44V44H4V4Z" fill="currentColor"></path>
      </svg>
    </div>
    <h2 class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">
      <?php echo e(__('common.brand')); ?>

    </h2>
  </a>

  
  <?php if($showMapLinks): ?>
    <nav class="hidden md:flex flex-1 items-center justify-center gap-8" aria-label="Map navigation">
      <?php if(auth()->guard()->check()): ?>
        <a class="text-sm font-medium leading-normal text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors"
          href="<?php echo e(route('patient.dashboard')); ?>"><?php echo e(__('patient.dashboard.sidebar.dashboard')); ?></a>
        <a class="text-sm font-bold leading-normal text-primary" href="<?php echo e(route('prescription.pharmacy-map')); ?>">Mapa de
          Farmacias</a>
        <a class="text-sm font-medium leading-normal text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors"
          href="<?php echo e(route('patient.orders')); ?>"><?php echo e(__('patient.dashboard.sidebar.my_orders')); ?></a>
      <?php endif; ?>
    </nav>
  <?php endif; ?>

  
  <div class="flex flex-1 justify-end gap-4 sm:gap-6 items-center">
    
    <?php echo $__env->make('components.language-switcher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <button aria-label="Notifications"
      class="flex items-center justify-center rounded-lg h-10 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white gap-2 text-sm font-bold px-2.5 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
      <span class="material-symbols-outlined">notifications</span>
    </button>

    
    <div class="relative">
      <button @click="profileOpen = !profileOpen"
        class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 cursor-pointer hover:ring-2 hover:ring-primary transition-all"
        style='background-image: url("https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->nombre ?? 'User')); ?>&background=137fec&color=fff");'
        aria-label="User menu" aria-haspopup="true" :aria-expanded="profileOpen">
      </button>

      
      <div x-show="profileOpen" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white dark:bg-card-dark border border-border-light dark:border-border-dark z-50"
        style="display: none;">

        <div class="py-1">
          
          <div class="px-4 py-2 border-b border-border-light dark:border-border-dark">
            <p class="text-sm font-medium text-body-text dark:text-body-text-dark"><?php echo e($user->nombre ?? 'Usuario'); ?></p>
            <p class="text-xs text-neutral-text dark:text-neutral-text-dark truncate"><?php echo e($user->correo ?? ''); ?></p>
          </div>

          
          <a href="<?php echo e(route($type . '.profile')); ?>"
            class="flex items-center gap-2 px-4 py-2 text-sm text-body-text dark:text-body-text-dark hover:bg-background-light dark:hover:bg-background-dark transition-colors">
            <span class="material-symbols-outlined text-base">person</span>
            <span><?php echo e(__('patient.dashboard.sidebar.profile')); ?></span>
          </a>

          
          <a href="<?php echo e(route($type . '.profile')); ?>"
            class="flex items-center gap-2 px-4 py-2 text-sm text-body-text dark:text-body-text-dark hover:bg-background-light dark:hover:bg-background-dark transition-colors">
            <span class="material-symbols-outlined text-base">settings</span>
            <span><?php echo e(__('common.navbar.settings')); ?></span>
          </a>

          
          <form method="POST" action="<?php echo e(route('logout')); ?>"
            class="border-t border-border-light dark:border-border-dark mt-1 pt-1">
            <?php echo csrf_field(); ?>
            <button type="submit"
              class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 dark:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
              <span class="material-symbols-outlined text-base">logout</span>
              <span><?php echo e(__('patient.dashboard.sidebar.logout')); ?></span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>

<script>
  // Ensure Alpine.js is loaded inline for immediate availability
  if (typeof Alpine === 'undefined') {
    document.write('<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"><\/script>');
  }
</script><?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/components/topbar.blade.php ENDPATH**/ ?>