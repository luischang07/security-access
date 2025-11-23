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
            <?php echo $__env->make('components.spa-loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Dynamic Content -->
            <?php echo $__env->yieldContent('spa-content'); ?>
        </main>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/jesusarturo/Desktop/mvc/Te-Acerco-Salud/resources/views/layouts/patient-spa.blade.php ENDPATH**/ ?>