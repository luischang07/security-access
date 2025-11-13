<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(__('auth.email_session_reset.title')); ?></title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        <?php echo file_get_contents(resource_path('css/email-session-reset-mailtrap.css')); ?>

    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1><?php echo e(__('auth.email_session_reset.header_short')); ?></h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                <?php echo e(__('auth.email_session_reset.greeting_simple', ['name' => $user->getName()])); ?>

            </div>

            <div class="message">
                <?php echo __('auth.email_session_reset.detected_strong'); ?>

            </div>

            <div class="message">
                <?php echo e(__('auth.email_session_reset.instruction')); ?>

            </div>

            <!-- Call to Action -->
            <div class="cta-container">
                <a href="<?php echo e($url); ?>" class="cta-button">
                    <?php echo e(__('auth.email_session_reset.button_emoji')); ?>

                </a>
            </div>

            <!-- Warning Box -->
            <div class="warning-box">
                <div class="warning-title">
                    <?php echo e(__('auth.email_session_reset.warning_title_info')); ?>

                </div>
                <ul class="warning-list">
                    <li><?php echo __('auth.email_session_reset.warning_items.all_devices'); ?></li>
                    <li><?php echo e(__('auth.email_session_reset.warning_items.relogin')); ?></li>
                    <li><?php echo __('auth.email_session_reset.warning_items.validity', ['minutes' => $expiresIn]); ?></li>
                    <li><?php echo e(__('auth.email_session_reset.warning_items.ignore')); ?></li>
                </ul>
            </div>

            <!-- Stats (only visible in development) -->
            <?php if(config('app.debug')): ?>
                <div class="stats">
                    <?php echo __('auth.email_session_reset.dev_info'); ?><br>
                    <?php echo e(__('auth.email_session_reset.dev_user_id', ['id' => $user->getId()])); ?><br>
                    <?php echo e(__('auth.email_session_reset.dev_timestamp', ['timestamp' => now()->format('Y-m-d H:i:s')])); ?><br>
                    <?php echo e(__('auth.email_session_reset.dev_environment', ['env' => config('app.env')])); ?>

                </div>
            <?php endif; ?>

            <div class="message">
                <?php echo e(__('auth.email_session_reset.url_instruction')); ?>

            </div>

            <div class="url-fallback">
                <?php echo e($url); ?>

            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                <strong><?php echo e(__('auth.email_session_reset.footer_title')); ?></strong>
            </div>
            <div class="footer-text">
                <?php echo e(__('auth.email_session_reset.footer_auto')); ?> <?php echo e(__('auth.email_session_reset.footer_secure')); ?>

            </div>
            <div class="footer-text">
                <?php echo e(__('auth.email_session_reset.copyright', ['year' => date('Y'), 'app_name' => config('app.name')])); ?>

            </div>
        </div>
    </div>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\laravel\securityAccess\security-access\resources\views/emails/session-reset-mailtrap.blade.php ENDPATH**/ ?>