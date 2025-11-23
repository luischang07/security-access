<?php $__env->startSection('title', __('auth.register.title')); ?>

<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/register.css'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-page">
        <div class="container">
            <h1><?php echo e(__('auth.register.create_account')); ?></h1>
            <p><?php echo e(__('auth.register.subtitle')); ?></p>

            <?php if(session('status')): ?>
                <div class="status"><?php echo e(session('status')); ?></div>
            <?php endif; ?>

            <?php if($errors->has('general')): ?>
                <div class="alert">
                    <?php echo e($errors->first('general')); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('register.attempt')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="name"><?php echo e(__('auth.register.name')); ?></label>
                    <input id="name" type="text" name="name" value="<?php echo e(old('name')); ?>" required
                        autocomplete="name" autofocus class="<?php echo e($errors->has('name') ? 'error' : ''); ?>">
                    <?php if($errors->has('name')): ?>
                        <div class="error-message"><?php echo e($errors->first('name')); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email"><?php echo e(__('auth.register.email')); ?></label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required
                        autocomplete="email" class="<?php echo e($errors->has('email') ? 'error' : ''); ?>">
                    <?php if($errors->has('email')): ?>
                        <div class="error-message"><?php echo e($errors->first('email')); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="nip"><?php echo e(__('auth.register.password')); ?></label>
                    <input id="nip" type="password" name="nip" required autocomplete="new-password"
                        class="<?php echo e($errors->has('nip') ? 'error' : ''); ?>">
                    <p>Must be at least 6 characters long</p>
                    <?php if($errors->has('nip')): ?>
                        <div class="error-message"><?php echo e($errors->first('nip')); ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="nip_confirmation"><?php echo e(__('auth.register.password_confirmation')); ?></label>
                    <input id="nip_confirmation" type="password" name="nip_confirmation" required
                        autocomplete="new-password">
                </div>

                <button type="submit"><?php echo e(__('auth.register.submit')); ?></button>
            </form>

            <div class="links">
                <p style="text-align: center; margin-top: 20px;">
                    <?php echo e(__('auth.register.already_have_account')); ?>

                    <a href="<?php echo e(route('login')); ?>"
                        style="color: #1d4ed8; text-decoration: none;"><?php echo e(__('auth.register.login_link')); ?></a>
                </p>
                <a class="back-link" href="<?php echo e(route('landing')); ?>"><?php echo e(__('auth.session_reset_success.back_to_home')); ?></a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/jesusarturo/Desktop/mvc/Te-Acerco-Salud/resources/views/auth/register.blade.php ENDPATH**/ ?>