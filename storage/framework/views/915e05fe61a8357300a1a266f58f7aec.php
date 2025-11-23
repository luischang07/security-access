<?php $__env->startSection('title', '403 - Acceso Prohibido'); ?>

<?php $__env->startSection('content'); ?>
    <div
        style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div style="text-align: center; color: white; padding: 40px;">
            <div style="font-size: 120px; font-weight: bold; margin-bottom: 20px; text-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                403
            </div>

            <h1 style="font-size: 32px; margin-bottom: 16px; font-weight: 600;">
                Acceso Prohibido
            </h1>

            <p style="font-size: 18px; margin-bottom: 32px; opacity: 0.9;">
                <?php echo e($exception->getMessage() ?: 'No tienes permisos para acceder a esta sección.'); ?>

            </p>

            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->isPatient()): ?>
                        <a href="<?php echo e(route('patient.dashboard')); ?>"
                            style="background: white; color: #667eea; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
                            Ir a Mi Dashboard
                        </a>
                    <?php elseif(auth()->user()->isPharmacyEmployee()): ?>
                        <a href="<?php echo e(route('pharmacy.dashboard')); ?>"
                            style="background: white; color: #667eea; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
                            Ir a Mi Dashboard
                        </a>
                    <?php elseif(auth()->user()->isAdmin()): ?>
                        <a href="<?php echo e(route('admin.dashboard')); ?>"
                            style="background: white; color: #667eea; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
                            Ir a Mi Dashboard
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>"
                        style="background: white; color: #667eea; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; box-shadow: 0 4px 6px rgba(0,0,0,0.2);">
                        Iniciar Sesión
                    </a>
                <?php endif; ?>

                <a href="<?php echo e(route('landing')); ?>"
                    style="background: rgba(255,255,255,0.2); color: white; padding: 12px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; border: 2px solid white; backdrop-filter: blur(10px);">
                    Volver al Inicio
                </a>
            </div>

            <div style="margin-top: 40px; opacity: 0.8; font-size: 14px;">
                <p>Si crees que esto es un error, contacta al administrador del sistema.</p>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/jesusarturo/Desktop/mvc/Te-Acerco-Salud/resources/views/errors/403.blade.php ENDPATH**/ ?>