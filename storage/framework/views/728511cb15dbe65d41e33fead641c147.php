

<?php $__env->startSection('title', __('patient.dashboard.title')); ?>

<?php $__env->startSection('spa-content'); ?>
    <?php echo $__env->make('patient.partials.dashboard-content', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.patient-spa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/patient/dashboard.blade.php ENDPATH**/ ?>