<?php $__env->startSection('title', __('auth.session_reset_success.title')); ?>

<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/login.css'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">✅</div>
            <h1><?php echo e(__('auth.session_reset_success.header')); ?></h1>
        </div>

        <?php if(session('status')): ?>
            <div class="status"><?php echo e(session('status')); ?></div>
        <?php endif; ?>

        <?php if(session('reset_email')): ?>
            <div
                style="background-color: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0;">
                <p><strong><?php echo e(__('auth.session_reset_success.account')); ?></strong> <?php echo e(session('reset_email')); ?></p>
                <p><?php echo e(__('auth.session_reset_success.message')); ?></p>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="<?php echo e(route('login')); ?>"
                style="display: inline-block; background-color: #1d4ed8; color: white; padding: 1rem 2rem; border-radius: 0.75rem; text-decoration: none; font-weight: 600;">
                <?php echo e(__('auth.session_reset_success.login_button')); ?>

            </a>
        </div>

        <a class="back-link" href="<?php echo e(route('landing')); ?>"><?php echo e(__('auth.session_reset_success.back_to_home')); ?></a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\Te-Acerco-Salud\resources\views\auth\session-reset-success.blade.php ENDPATH**/ ?>