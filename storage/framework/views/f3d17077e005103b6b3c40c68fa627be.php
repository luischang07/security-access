

<?php $__env->startSection('content'); ?>
    <?php
        $user = Auth::user();
    ?>

    <?php echo $__env->make('components.navbar', ['type' => 'dashboard', 'user' => $user], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-1 overflow-hidden">
        <?php echo $__env->make('components.sidebar', [
            'user' => $user,
            'type' => 'patient',
            'currentRoute' => Route::currentRouteName(),
            'useSpa' => true,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Main Content Container -->
        <main class="flex-1 overflow-y-auto p-6 sm:p-8" id="main-content">
            <!-- Loading Indicator -->
            <div id="spa-loader"
                style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.3); z-index: 9999; align-items: center; justify-content: center;">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
                    <div class="flex items-center gap-3">
                        <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300 font-medium">Loading...</span>
                    </div>
                </div>
            </div>

            <!-- Dynamic Content -->
            <?php echo $__env->yieldContent('spa-content'); ?>
        </main>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/layouts/patient-spa.blade.php ENDPATH**/ ?>