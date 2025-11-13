

<?php $__env->startSection('title', __('landing.title')); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('components.navbar', ['type' => 'landing', 'transparent' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="flex-1">
        <!-- Hero Section -->
        <section class="px-4 sm:px-10 lg:px-20 py-16 md:py-24">
            <div class="max-w-6xl mx-auto @container">
                <div class="flex flex-col gap-10 @[960px]:flex-row-reverse @[960px]:items-center">
                    <div class="w-full @[960px]:w-1/2 flex justify-center">
                        <img class="w-full max-w-lg aspect-square object-cover rounded-xl shadow-lg"
                            alt="Healthcare professional"
                            src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800" />
                    </div>
                    <div class="flex flex-col gap-8 @[960px]:w-1/2 @[960px]:justify-center">
                        <div class="flex flex-col gap-4 text-left">
                            <h1
                                class="text-[#111418] dark:text-white text-4xl font-black leading-tight tracking-[-0.033em] @[480px]:text-5xl @[864px]:text-6xl">
                                <?php echo e(__('patient.landing.hero.title')); ?></h1>
                            <h2
                                class="text-gray-600 dark:text-gray-400 text-base font-normal leading-normal @[480px]:text-lg">
                                <?php echo e(__('patient.landing.hero.subtitle')); ?></h2>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="<?php echo e(route('prescription.upload.step1')); ?>"
                                class="flex w-full sm:w-auto min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:opacity-90 transition-opacity">
                                <span class="truncate"><?php echo e(__('patient.landing.hero.cta_upload')); ?></span>
                            </a>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400 pt-2">
                            <span class="material-symbols-outlined text-secondary">verified_user</span>
                            <span><?php echo e(__('patient.landing.hero.hipaa_compliant')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- How It Works Section -->
        <section class="px-4 sm:px-10 lg:px-20 py-16 md:py-24 bg-white dark:bg-gray-900" id="how-it-works">
            <div class="max-w-6xl mx-auto">
                <!-- SectionHeader Component -->
                <div class="text-center mb-12">
                    <h2
                        class="text-[#111418] dark:text-white text-3xl font-bold leading-tight tracking-[-0.015em] sm:text-4xl">
                        <?php echo e(__('patient.landing.how_it_works.title')); ?></h2>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                        <?php echo e(__('patient.landing.how_it_works.subtitle')); ?></p>
                </div>
                <!-- TextGrid Component -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="flex flex-col gap-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-background-light dark:bg-background-dark p-6 text-center items-center">
                        <div class="flex items-center justify-center size-12 rounded-full bg-primary/20 text-primary">
                            <span class="material-symbols-outlined">upload_file</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <h3 class="text-[#111418] dark:text-white text-lg font-bold leading-tight">
                                <?php echo e(__('patient.landing.how_it_works.step1_title')); ?></h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm font-normal leading-normal">
                                <?php echo e(__('patient.landing.how_it_works.step1_desc')); ?></p>
                        </div>
                    </div>
                    <div
                        class="flex flex-col gap-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-background-light dark:bg-background-dark p-6 text-center items-center">
                        <div class="flex items-center justify-center size-12 rounded-full bg-primary/20 text-primary">
                            <span class="material-symbols-outlined">storefront</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <h3 class="text-[#111418] dark:text-white text-lg font-bold leading-tight">
                                <?php echo e(__('patient.landing.how_it_works.step2_title')); ?></h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm font-normal leading-normal">
                                <?php echo e(__('patient.landing.how_it_works.step2_desc')); ?></p>
                        </div>
                    </div>
                    <div
                        class="flex flex-col gap-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-background-light dark:bg-background-dark p-6 text-center items-center">
                        <div class="flex items-center justify-center size-12 rounded-full bg-primary/20 text-primary">
                            <span class="material-symbols-outlined">notifications</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <h3 class="text-[#111418] dark:text-white text-lg font-bold leading-tight">
                                <?php echo e(__('patient.landing.how_it_works.step3_title')); ?></h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm font-normal leading-normal">
                                <?php echo e(__('patient.landing.how_it_works.step3_desc')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- FeatureSection Component (Map Preview) -->
        <section class="px-4 sm:px-10 lg:px-20 py-16 md:py-24">
            <div class="max-w-6xl mx-auto @container">
                <div class="flex flex-col lg:flex-row items-center gap-12">
                    <div class="lg:w-1/2 flex flex-col gap-6">
                        <div class="flex flex-col gap-4">
                            <h2
                                class="text-[#111418] dark:text-white text-3xl font-bold leading-tight tracking-[-0.015em] @[480px]:text-4xl">
                                <?php echo e(__('patient.landing.pharmacy_network.title')); ?></h2>
                            <p class="text-gray-600 dark:text-gray-400 text-base font-normal leading-normal">
                                <?php echo e(__('patient.landing.pharmacy_network.description')); ?></p>
                        </div>
                        <a href="<?php echo e(route('prescription.pharmacy-map')); ?>"
                            class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] w-fit hover:opacity-90 transition-opacity">
                            <span class="truncate"><?php echo e(__('patient.landing.pharmacy_network.cta')); ?></span>
                        </a>
                    </div>
                    <div class="lg:w-1/2 w-full">
                        <img class="w-full aspect-video object-cover rounded-xl shadow-lg" alt="Pharmacy map"
                            src="https://images.unsplash.com/photo-1524661135-423995f22d0b?w=800" />
                    </div>
                </div>
            </div>
        </section>
        <!-- Benefits Section -->
        <section class="px-4 sm:px-10 lg:px-20 py-16 md:py-24 bg-white dark:bg-gray-900" id="benefits">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2
                        class="text-[#111418] dark:text-white text-3xl font-bold leading-tight tracking-[-0.015em] sm:text-4xl">
                        <?php echo e(__('patient.landing.benefits.title')); ?></h2>
                    <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                        <?php echo e(__('patient.landing.benefits.subtitle')); ?></p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="flex items-start gap-4">
                        <div class="text-secondary mt-1">
                            <span class="material-symbols-outlined">schedule</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-[#111418] dark:text-white">
                                <?php echo e(__('patient.landing.benefits.save_time_title')); ?></h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                <?php echo e(__('patient.landing.benefits.save_time_desc')); ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="text-secondary mt-1">
                            <span class="material-symbols-outlined">inventory</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-[#111418] dark:text-white">
                                <?php echo e(__('patient.landing.benefits.guaranteed_stock_title')); ?></h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                <?php echo e(__('patient.landing.benefits.guaranteed_stock_desc')); ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="text-secondary mt-1">
                            <span class="material-symbols-outlined">checklist</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-[#111418] dark:text-white">
                                <?php echo e(__('patient.landing.benefits.easy_process_title')); ?></h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                <?php echo e(__('patient.landing.benefits.easy_process_desc')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- CTA Block -->
        <section class="px-4 sm:px-10 lg:px-20 py-16 md:py-24">
            <div class="max-w-4xl mx-auto bg-primary/10 dark:bg-primary/20 p-8 sm:p-12 text-center rounded-xl">
                <h2 class="text-3xl sm:text-4xl font-bold text-[#111418] dark:text-white">
                    <?php echo e(__('patient.landing.cta_block.title')); ?></h2>
                <p class="text-gray-600 dark:text-gray-300 mt-4 text-lg">
                    <?php echo e(__('patient.landing.cta_block.subtitle')); ?></p>
                <a href="<?php echo e(route('register')); ?>"
                    class="mt-8 inline-flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-6 bg-primary text-white text-base font-bold leading-normal tracking-[0.015em] hover:opacity-90 transition-opacity">
                    <span class="truncate"><?php echo e(__('patient.landing.cta_block.button')); ?></span>
                </a>
            </div>
        </section>
    </main>

    <?php echo $__env->make('components.footer', ['type' => 'landing'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/landing.blade.php ENDPATH**/ ?>