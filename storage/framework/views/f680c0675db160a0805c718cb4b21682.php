<!DOCTYPE html>
<html class="light" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Te Acerco Salud</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "secondary": "#2ECC71",
                        "accent": "#F39C12",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                        "success": "#28A745",
                        "warning": "#FFC107",
                        "danger": "#DC3545",
                        "neutral-text": "#617589",
                        "neutral-text-dark": "#90a4b8",
                        "body-text": "#111418",
                        "body-text-dark": "#f0f2f4",
                        "border-light": "#f0f2f4",
                        "border-dark": "#2a3b4c",
                        "card-light": "#ffffff",
                        "card-dark": "#1a2734",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24 }
    </style>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-body-text dark:text-body-text-dark">
    <div class="relative flex h-screen w-full flex-col overflow-hidden">
        <div class="layout-container flex h-full grow flex-col">

            <?php echo $__env->make('components.topbar', ['user' => auth()->user(), 'type' => 'pharmacy'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="flex flex-1 overflow-hidden">
                <?php echo $__env->make('components.sidebar', [
                    'user' => auth()->user(),
                    'type' => 'pharmacy',
                    'currentRoute' => 'pharmacy.profile',
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                
                <main class="flex-1 overflow-y-auto bg-background-light dark:bg-background-dark p-6 sm:p-8">
                    <div class="max-w-4xl mx-auto space-y-6">
                        
                        <div>
                            <h1 class="text-3xl font-bold text-body-text dark:text-body-text-dark">
                                Mi Perfil
                            </h1>
                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark mt-1">
                                Información de tu cuenta y datos personales
                            </p>
                        </div>

                        
                        <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                            <div class="flex items-center gap-6 mb-6">
                                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-20"
                                    style='background-image: url("https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->nombre ?? 'User')); ?>&background=137fec&color=fff&size=128");'>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-body-text dark:text-body-text-dark">
                                        <?php echo e($user->nombre); ?> <?php echo e($user->apellido); ?>

                                    </h2>
                                    <p class="text-neutral-text dark:text-neutral-text-dark">
                                        <?php echo e($user->correo); ?>

                                    </p>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-primary/10 text-primary mt-2">
                                        Empleado de Farmacia
                                    </span>
                                </div>
                            </div>

                            <div class="border-t border-border-light dark:border-border-dark pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark mb-4">
                                        Información Personal
                                    </h3>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Nombre</p>
                                            <p class="font-medium text-body-text dark:text-body-text-dark"><?php echo e($user->nombre); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Apellido</p>
                                            <p class="font-medium text-body-text dark:text-body-text-dark"><?php echo e($user->apellido); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Correo Electrónico</p>
                                            <p class="font-medium text-body-text dark:text-body-text-dark"><?php echo e($user->correo); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Rol</p>
                                            <p class="font-medium text-body-text dark:text-body-text-dark capitalize"><?php echo e($user->role); ?></p>
                                        </div>
                                    </div>
                                </div>

                                
                                <div>
                                    <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark mb-4">
                                        Información de Sucursal
                                    </h3>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Sucursal</p>
                                            <p class="font-medium text-body-text dark:text-body-text-dark"><?php echo e($empleado->sucursal->nombre ?? 'N/A'); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Cadena</p>
                                            <p class="font-medium text-body-text dark:text-body-text-dark"><?php echo e($empleado->sucursal->cadena->nombre ?? 'N/A'); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Dirección</p>
                                            <p class="font-medium text-body-text dark:text-body-text-dark">
                                                <?php echo e($empleado->sucursal->calle ?? ''); ?> <?php echo e($empleado->sucursal->numero_ext ?? ''); ?>

                                                <?php if($empleado->sucursal->numero_int ?? null): ?>, Int. <?php echo e($empleado->sucursal->numero_int); ?><?php endif; ?>
                                                <br>
                                                <?php echo e($empleado->sucursal->colonia ?? ''); ?>, <?php echo e($empleado->sucursal->ciudad ?? ''); ?>

                                            </p>
                                        </div>
                                        <?php if($empleado->sucursal->contacto ?? null): ?>
                                        <div>
                                            <p class="text-sm text-neutral-text dark:text-neutral-text-dark">Teléfono de Sucursal</p>
                                            <p class="font-medium text-body-text dark:text-body-text-dark"><?php echo e($empleado->sucursal->contacto); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <div class="rounded-xl border border-border-light dark:border-border-dark bg-card-light dark:bg-card-dark p-6">
                            <h3 class="text-lg font-bold text-body-text dark:text-body-text-dark mb-4">
                                Acciones de Cuenta
                            </h3>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button class="px-4 py-2 rounded-lg bg-primary text-white font-medium hover:bg-primary/90 transition">
                                    Cambiar Contraseña
                                </button>
                                <button class="px-4 py-2 rounded-lg border border-border-light dark:border-border-dark text-body-text dark:text-body-text-dark font-medium hover:bg-background-light dark:hover:bg-background-dark transition">
                                    Editar Perfil
                                </button>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/pharmacy/profile.blade.php ENDPATH**/ ?>