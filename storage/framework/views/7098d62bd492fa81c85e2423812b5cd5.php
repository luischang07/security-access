

<header
  class="flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 dark:border-gray-800 px-6 sm:px-10 py-3 bg-white dark:bg-background-dark sticky top-0 z-10">
  <a href="<?php echo e(route('landing')); ?>"
    class="flex items-center gap-3 text-[#111418] dark:text-white hover:opacity-80 transition-opacity">
    <div class="size-6 text-primary">
      <svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 4H17.3334V17.3334H30.6666V30.6666H44V44H4V4Z" fill="currentColor"></path>
      </svg>
    </div>
    <h2 class="text-gray-900 dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">Te Acerco Salud</h2>
  </a>
  <div class="flex flex-1 justify-end gap-4 sm:gap-6">
    <label class="hidden sm:flex flex-col min-w-40 !h-10 max-w-64">
      <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
        <div
          class="text-gray-500 dark:text-gray-400 flex border-none bg-gray-100 dark:bg-gray-800 items-center justify-center pl-4 rounded-l-lg border-r-0">
          <span class="material-symbols-outlined">search</span>
        </div>
        <input
          class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-gray-900 dark:text-white focus:outline-0 focus:ring-0 border-none bg-gray-100 dark:bg-gray-800 focus:border-none h-full placeholder:text-gray-500 dark:placeholder:text-gray-400 px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
          placeholder="<?php echo e(__('common.actions.search')); ?>..." value="" />
      </div>
    </label>
    <?php echo $__env->make('components.language-switcher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <button
      class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
      <span class="material-symbols-outlined">notifications</span>
    </button>
    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 cursor-pointer"
      style='background-image: url("https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->name ?? 'User')); ?>&background=137fec&color=fff");'>
    </div>
  </div>
</header><?php /**PATH /Users/jesusarturo/Desktop/mvc/Te-Acerco-Salud/resources/views/components/topbar.blade.php ENDPATH**/ ?>