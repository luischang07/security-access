<?php $__env->startSection('title', __('patient.profile.title')); ?>

<?php $__env->startSection('spa-content'); ?>
  <?php echo $__env->make('patient.partials.profile-content', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.patient-spa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/jesusarturo/Desktop/mvc/Te-Acerco-Salud/resources/views/patient/profile.blade.php ENDPATH**/ ?>