

<?php
    $type = $type ?? 'landing';
?>

<?php if($type === 'landing'): ?>
    
    <footer class="bg-gray-100 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-10 lg:px-20 py-8">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="size-8 text-primary">
                        <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 4H17.3334V17.3334H30.6666V30.6666H44V44H4V4Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <h3 class="text-[#111418] dark:text-white text-lg font-bold">Te Acerco Salud</h3>
                </div>
                <nav class="flex flex-wrap justify-center gap-6 text-sm">
                    <a href="<?php echo e(route('landing')); ?>"
                        class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">
                        <?php echo e(__('common.footer.home')); ?>

                    </a>
                    <a href="<?php echo e(route('landing')); ?>#how-it-works"
                        class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">
                        <?php echo e(__('common.navbar.how_it_works')); ?>

                    </a>
                    <a href="<?php echo e(route('landing')); ?>#benefits"
                        class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">
                        <?php echo e(__('common.navbar.benefits')); ?>

                    </a>
                    <a href="#"
                        class="text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition">
                        <?php echo e(__('common.footer.contact')); ?>

                    </a>
                </nav>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    <?php echo e(__('common.footer.copyright', ['year' => date('Y')])); ?>

                </p>
            </div>
        </div>
    </footer>
<?php else: ?>
    
    <footer class="bg-white dark:bg-background-dark border-t border-gray-200 dark:border-gray-800 py-4">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-sm">
                <p class="text-gray-500 dark:text-gray-400">
                    <?php echo e(__('common.footer.copyright', ['year' => date('Y')])); ?> Te Acerco Salud
                </p>
                <nav class="flex gap-6">
                    <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-primary transition">
                        <?php echo e(__('common.footer.privacy')); ?>

                    </a>
                    <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-primary transition">
                        <?php echo e(__('common.footer.terms')); ?>

                    </a>
                </nav>
            </div>
        </div>
    </footer>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/components/footer.blade.php ENDPATH**/ ?>