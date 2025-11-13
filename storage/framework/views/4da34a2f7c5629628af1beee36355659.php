

<?php $__env->startSection('title', __('auth.login.title')); ?>

<?php $__env->startPush('styles'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/login.css'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/lockout-countdown.js'); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/email-sync.js'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-page">
        <div class="container">
            <h1><?php echo e(__('auth.login.welcome_back')); ?></h1>
            <p><?php echo e(__('auth.login.subtitle')); ?></p>

            <?php if(session('status')): ?>
                <div class="status"><?php echo e(session('status')); ?></div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert" id="error-message">
                    <?php if($errors->has('session')): ?>
                        <?php echo e($errors->first('session')); ?>

                    <?php elseif($errors->has('session_reset')): ?>
                        <?php echo e($errors->first('session_reset')); ?>

                    <?php elseif($errors->has('nip') && session('lockout_seconds')): ?>
                        <span id="lockout-message">
                            <?php echo e(__('messages.login.lockout', ['time' => ''])); ?> <span id="countdown-timer"></span>.
                        </span>
                    <?php else: ?>
                        <?php echo e($errors->first()); ?>

                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if(session('show_session_reset')): ?>
                <div class="session-reset-section">
                    <h3>Active Session Detected</h3>
                    <p>You already have an active session on another device. Click below to delete it and log in from this
                        device.</p>

                    <form method="POST" action="<?php echo e(route('session.reset.send')); ?>" style="margin: 15px 0;"
                        id="session-reset-form">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="email" id="hidden-email" value="<?php echo e(old('correo')); ?>">
                        <button type="submit" class="reset-session-btn">
                            Delete Active Session
                        </button>
                    </form>

                    <p><small>We'll send a confirmation link to <span id="email-display"><?php echo e(old('correo')); ?></span></small>
                    </p>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login.attempt')); ?>">
                <?php echo csrf_field(); ?>
                <div>
                    <label for="correo"><?php echo e(__('auth.login.email')); ?></label>
                    <input id="correo" type="email" name="correo" value="<?php echo e(old('correo')); ?>" required
                        autocomplete="email" autofocus>
                </div>
                <div>
                    <label for="nip"><?php echo e(__('auth.login.password')); ?></label>
                    <input id="nip" type="password" name="nip" required autocomplete="current-password">
                </div>
                <div class="remember-me">
                    <input type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                    <label for="remember"><?php echo e(__('auth.login.remember_me')); ?></label>
                </div>
                <button type="submit"><?php echo e(__('auth.login.submit')); ?></button>
            </form>

            <p style="text-align: center; margin-top: 20px;">
                <?php echo e(__('auth.login.no_account')); ?>

                <a href="<?php echo e(route('register')); ?>"
                    style="color: #1d4ed8; text-decoration: none;"><?php echo e(__('auth.login.register_link')); ?></a>
            </p>

            <a class="back-link" href="<?php echo e(route('landing')); ?>"><?php echo e(__('auth.session_reset_success.back_to_home')); ?></a>
        </div>

        <?php if(session('lockout_seconds')): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const remainingSeconds = <?php echo e(session('lockout_seconds')); ?>;
                    const lockedEmail = '<?php echo e(old('correo')); ?>';
                    initLockoutCountdown(remainingSeconds, lockedEmail);
                });
            </script>
        <?php endif; ?>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/auth/login.blade.php ENDPATH**/ ?>