<div class="relative" x-data="{ open: false }">
  <button @click="open = !open" @click.away="open = false"
    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-700 dark:text-gray-300">
    <span class="material-symbols-outlined text-xl">language</span>
    <span class="text-sm font-medium uppercase"><?php echo e(app()->getLocale()); ?></span>
    <span class="material-symbols-outlined text-sm transition-transform duration-200"
      :class="{ 'rotate-180': open }">expand_more</span>
  </button>

  <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
    class="absolute right-0 mt-2 w-32 bg-white dark:bg-card-dark rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50"
    style="display: none;">
    <a href="<?php echo e(route('lang.switch', 'es')); ?>"
      class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 <?php echo e(app()->getLocale() === 'es' ? 'bg-gray-50 dark:bg-gray-800/50 font-medium text-primary' : ''); ?>">
      <span>🇪🇸</span>
      <span>Español</span>
    </a>
    <a href="<?php echo e(route('lang.switch', 'en')); ?>"
      class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 <?php echo e(app()->getLocale() === 'en' ? 'bg-gray-50 dark:bg-gray-800/50 font-medium text-primary' : ''); ?>">
      <span>🇺🇸</span>
      <span>English</span>
    </a>
  </div>
</div><?php /**PATH C:\xampp\htdocs\laravel\Te-Acerco-Salud\resources\views\components\language-switcher.blade.php ENDPATH**/ ?>