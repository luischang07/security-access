<?php $__env->startSection('title', __('prescription.pharmacy_map.title')); ?>

<?php $__env->startPush('styles'); ?>
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <?php echo app('Illuminate\Foundation\Vite')(['resources/css/pharmacy-map.css']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <?php echo app('Illuminate\Foundation\Vite')(['resources/js/pharmacy-map.js']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
  <!-- Top Navigation Bar -->
  <header
    class="flex items-center justify-between whitespace-nowrap border-b border-solid border-border-light dark:border-border-dark px-6 md:px-10 py-3 bg-card-light dark:bg-card-dark backdrop-blur-sm sticky top-0 z-50">
    <div class="flex items-center gap-4 text-body-text dark:text-body-text-dark">
      <div class="size-6 text-primary">
        <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
          <path d="M4 4H17.3334V17.3334H30.6666V30.6666H44V44H4V4Z" fill="currentColor"></path>
        </svg>
      </div>
      <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Te Acerco Salud</h2>
    </div>
    <nav class="hidden lg:flex flex-1 justify-center gap-8">
      <a class="text-body-text dark:text-body-text-dark text-sm font-medium leading-normal hover:text-primary transition"
        href="<?php echo e(route('patient.dashboard')); ?>"><?php echo e(__('common.navbar.home')); ?></a>
      <a class="text-body-text dark:text-body-text-dark text-sm font-medium leading-normal hover:text-primary transition"
        href="<?php echo e(route('patient.orders')); ?>"><?php echo e(__('patient.orders.title')); ?></a>
      <a class="text-primary text-sm font-bold leading-normal" href="#"><?php echo e(__('prescription.pharmacy_map.title')); ?></a>
      <a class="text-body-text dark:text-body-text-dark text-sm font-medium leading-normal hover:text-primary transition"
        href="<?php echo e(route('patient.profile')); ?>"><?php echo e(__('patient.dashboard.sidebar.profile')); ?></a>
    </nav>
    <div class="flex items-center gap-4">
      <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
        style='background-image: url("https://ui-avatars.com/api/?name=<?php echo e(urlencode(auth()->user()->name ?? 'User')); ?>&background=137fec&color=fff");'>
      </div>
      <button class="lg:hidden text-body-text dark:text-body-text-dark">
        <span class="material-symbols-outlined">menu</span>
      </button>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex flex-1 flex-col lg:flex-row overflow-hidden h-[calc(100vh-64px)]">

    <!-- Left Panel: Search, Filters, and List -->
    <aside
      class="w-full lg:w-[420px] lg:min-w-[420px] flex flex-col bg-card-light dark:bg-card-dark lg:border-r border-border-light dark:border-border-dark p-4 sm:p-6 space-y-4 z-20 shadow-lg lg:shadow-none">

      <!-- Page Heading -->
      <div>
        <h1 class="text-body-text dark:text-body-text-dark text-3xl font-black leading-tight tracking-[-0.033em]">
          <?php echo e(__('prescription.pharmacy_map.heading')); ?>

        </h1>
        <p class="text-neutral-text dark:text-neutral-text-dark text-base font-normal leading-normal mt-1">
          <?php echo e(__('prescription.pharmacy_map.subtitle')); ?>

        </p>
      </div>

      <!-- Search Bar -->
      <div class="relative flex items-center group">
        <div
          class="flex w-full items-stretch rounded-lg h-12 border border-border-light dark:border-border-dark focus-within:border-primary focus-within:ring-1 focus-within:ring-primary transition-all bg-background-light dark:bg-background-dark">
          <div class="flex items-center justify-center pl-4 text-neutral-text dark:text-neutral-text-dark">
            <span class="material-symbols-outlined">search</span>
          </div>
          <input id="searchInput"
            class="flex-1 bg-transparent border-none px-4 text-base focus:ring-0 placeholder:text-neutral-text dark:placeholder:text-neutral-text-dark text-body-text dark:text-body-text-dark"
            placeholder="<?php echo e(__('prescription.pharmacy_map.search_placeholder')); ?>" />
        </div>
      </div>

      <!-- Filter Chips -->
      <div class="flex gap-2 overflow-x-auto no-scrollbar pb-2">
        <button
          class="flex h-9 shrink-0 items-center gap-2 rounded-lg bg-background-light dark:bg-background-dark px-3 hover:bg-border-light dark:hover:bg-border-dark transition-colors text-body-text dark:text-body-text-dark">
          <span class="material-symbols-outlined text-lg">swap_vert</span>
          <span class="text-sm font-medium">Sort by distance</span>
        </button>
        <button id="filterOpen"
          class="flex h-9 shrink-0 items-center gap-2 rounded-lg bg-background-light dark:bg-background-dark px-3 hover:bg-border-light dark:hover:bg-border-dark transition-colors text-body-text dark:text-body-text-dark">
          <span class="material-symbols-outlined text-lg">schedule</span>
          <span class="text-sm font-medium"><?php echo e(__('prescription.pharmacy_map.filters.open_now')); ?></span>
        </button>
        <button id="filter24h"
          class="flex h-9 shrink-0 items-center gap-2 rounded-lg bg-background-light dark:bg-background-dark px-3 hover:bg-border-light dark:hover:bg-border-dark transition-colors text-body-text dark:text-body-text-dark">
          <span class="material-symbols-outlined text-lg">history</span>
          <span class="text-sm font-medium"><?php echo e(__('prescription.pharmacy_map.filters.24_hours')); ?></span>
        </button>
      </div>

      <!-- Pharmacy List -->
      <div id="pharmacyList" class="flex-1 overflow-y-auto space-y-3 pr-1 custom-scrollbar">
        <!-- List items will be rendered by JS -->
      </div>
    </aside>

    <!-- Right Panel: Map and Detail Modal -->
    <div class="flex-1 relative h-[50vh] lg:h-auto">
      <div id="map"></div>

      <!-- Pharmacy Detail Modal (floating on map) -->
      <div id="pharmacyDetailCard" class="hidden absolute top-0 lg:left-6 lg:top-6 lg:w-96 p-4 lg:p-0 z-[1000] w-full">
        <div
          class="bg-card-light dark:bg-card-dark rounded-xl shadow-2xl p-6 space-y-4 border border-border-light dark:border-border-dark animate-fade-in-up relative">
          <button
            class="absolute top-4 right-4 text-neutral-text dark:text-neutral-text-dark hover:text-body-text dark:hover:text-body-text-dark">
            <span class="material-symbols-outlined">close</span>
          </button>

          <!-- Pharmacy Header -->
          <div class="flex items-center gap-4">
            <div class="h-12 w-12 bg-primary/10 rounded-lg flex items-center justify-center shrink-0">
              <span class="material-symbols-outlined text-2xl text-primary">local_pharmacy</span>
            </div>
            <div>
              <h2 id="detailName" class="text-xl font-bold text-body-text dark:text-body-text-dark"></h2>
              <p id="detailAddress" class="text-sm text-neutral-text dark:text-neutral-text-dark"></p>
            </div>
          </div>

          <!-- Operating Hours -->
          <div class="border-t border-border-light dark:border-border-dark pt-4 space-y-2">
            <h4 class="font-semibold text-sm text-body-text dark:text-body-text-dark">Operating Hours</h4>
            <div class="text-sm text-neutral-text dark:text-neutral-text-dark space-y-1">
              <div class="flex justify-between">
                <span class="font-medium text-body-text dark:text-body-text-dark">Today</span>
                <span id="detailHoursToday"></span>
              </div>
              <div class="flex justify-between">
                <span>Mon - Fri</span>
                <span id="detailHoursWeekday"></span>
              </div>
              <div class="flex justify-between">
                <span>Sat - Sun</span>
                <span id="detailHoursWeekend"></span>
              </div>
            </div>
          </div>

          <!-- Contact -->
          <div class="border-t border-border-light dark:border-border-dark pt-4">
            <h4 class="font-semibold text-sm mb-1 text-body-text dark:text-body-text-dark">Contact</h4>
            <p id="detailPhone" class="text-sm text-neutral-text dark:text-neutral-text-dark"></p>
          </div>

          <!-- Stock Information -->
          <div class="border-t border-border-light dark:border-border-dark pt-4 flex items-center gap-2">
            <span id="stockIcon" class="material-symbols-outlined text-lg"></span>
            <span id="stockText" class="text-sm font-medium"></span>
          </div>

          <!-- Select Button -->
          <div class="pt-2">
            <button
              class="w-full h-12 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 transition shadow-lg shadow-primary/20">
              <?php echo e(__('prescription.pharmacy_map.pharmacy_card.select')); ?>

            </button>
          </div>
        </div>
      </div>
    </div>
  </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/franciscomejia/Documents/PROYECTOS TEC/Te-Acerco-Salud/resources/views/prescription/pharmacy-map.blade.php ENDPATH**/ ?>