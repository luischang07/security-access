<?php
    // Props: $type = 'landing'|'dashboard'
    $type = $type ?? 'landing';
    $user = $user ?? auth()->user();
    $transparent = $transparent ?? false;
    $containerBg = $transparent ? 'bg-transparent' : 'bg-background-light/80 dark:bg-background-dark/80';
?>


<?php if($type === 'landing'): ?>
    <header class="sticky top-0 z-50 w-full <?php echo e($containerBg); ?> backdrop-blur-sm will-change-transform"
        aria-label="Main landing header" style="transform: translateY(0); transition: transform 0.3s ease-in-out;">
        <div class="px-4 sm:px-10 lg:px-20 mx-auto">
            <div
                class="flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 dark:border-gray-700 py-4">
                <div class="flex items-center gap-3 text-[#111418] dark:text-white">
                    <div class="size-6 text-primary" aria-hidden="true">
                        <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 4H17.3334V17.3334H30.6666V30.6666H44V44H4V4Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold leading-tight tracking-[-0.015em]">Te Acerco Salud</h2>
                </div>

                <nav class="hidden md:flex items-center gap-8" aria-label="Landing navigation">
                    <a class="text-sm font-medium leading-normal text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors"
                        href="#how-it-works"><?php echo e(__('patient.landing.how_it_works.title')); ?></a>
                    <a class="text-sm font-medium leading-normal text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors"
                        href="#benefits"><?php echo e(__('patient.landing.benefits.title')); ?></a>
                    <a class="text-sm font-medium leading-normal text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors"
                        href="#faq">FAQs</a>
                </nav>

                <div class="flex items-center gap-2">
                    <?php if(auth()->guard()->guest()): ?>
                        <a href="<?php echo e(route('login')); ?>"
                            class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-gray-200/80 dark:bg-gray-800/80 text-[#111418] dark:text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-gray-300/90 dark:hover:bg-gray-700/90 transition-colors">
                            <span class="truncate"><?php echo e(__('common.navbar.login')); ?></span>
                        </a>
                        <a href="<?php echo e(route('register')); ?>"
                            class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:opacity-90 transition-opacity">
                            <span class="truncate"><?php echo e(__('common.navbar.register')); ?></span>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('patient.dashboard')); ?>"
                            class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] hover:opacity-90 transition-opacity">
                            <span class="truncate"><?php echo e(__('common.navbar.dashboard')); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
<?php else: ?>
    
    <header
        class="sticky top-0 z-50 w-full bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm will-change-transform"
        aria-label="Dashboard header" style="transform: translateY(0); transition: transform 0.3s ease-in-out;">
        <div class="px-4 sm:px-10 lg:px-20 mx-auto">
            <div
                class="flex items-center justify-between whitespace-nowrap border-b border-solid border-gray-200 dark:border-gray-800 py-3">
                <div class="flex items-center gap-4 text-gray-900 dark:text-white">
                    <div class="size-6 text-primary" aria-hidden="true">
                        <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 4H17.3334V17.3334H30.6666V30.6666H44V44H4V4Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">Te Acerco Salud</h2>
                </div>

                <div class="flex flex-1 justify-end gap-4 sm:gap-6 items-center">
                    
                    <label class="hidden sm:flex flex-col min-w-40 h-10 max-w-64">
                        <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                            <div
                                class="text-gray-500 dark:text-gray-400 flex items-center justify-center pl-4 rounded-l-lg border-r-0 bg-gray-100 dark:bg-gray-800">
                                <span class="material-symbols-outlined">search</span>
                            </div>
                            <input aria-label="Search"
                                class="form-input flex w-full min-w-0 flex-1 rounded-lg text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800 focus:outline-0 focus:ring-0 border-none placeholder:text-gray-500 dark:placeholder:text-gray-400 px-4 text-base"
                                placeholder="Search orders..." value="" />
                        </div>
                    </label>

                    
                    <button aria-label="Notifications"
                        class="flex items-center justify-center rounded-lg h-10 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white gap-2 text-sm font-bold px-2.5">
                        <span class="material-symbols-outlined">notifications</span>
                    </button>

                    
                    <div class="relative">
                        <?php if($user && ($user->avatar ?? false)): ?>
                            <a href="#" class="block bg-center bg-no-repeat bg-cover rounded-full size-10"
                                style="background-image: url('<?php echo e($user->avatar); ?>'); width:40px; height:40px;">
                                <span class="sr-only"><?php echo e($user->name ?? 'User'); ?></span>
                            </a>
                        <?php else: ?>
                            <a href="#"
                                class="flex items-center justify-center rounded-full bg-primary/10 text-primary w-10 h-10">
                                <span class="material-symbols-outlined">person</span>
                                <span class="sr-only"><?php echo e($user->name ?? 'User'); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/components/navbar.blade.php ENDPATH**/ ?>