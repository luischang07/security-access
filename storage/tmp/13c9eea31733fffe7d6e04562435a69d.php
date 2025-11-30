<?php $__env->startSection('title', __('admin.reports.title')); ?>

<?php $__env->startSection('spa-content'); ?>
    <?php echo $__env->make('admin.partials.reports-content', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin-spa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\Te-Acerco-Salud\resources\views\admin\reports.blade.php ENDPATH**/ ?>