<?php

return [
  // Login page
  'login' => [
    'title' => 'Login',
    'welcome_back' => 'Welcome Back',
    'subtitle' => 'Sign in to your account to continue',
    'email' => 'Email Address',
    'password' => 'Password',
    'remember_me' => 'Remember me',
    'submit' => 'Sign In',
    'no_account' => "Don't have an account?",
    'register_link' => 'Register here',
  ],

  // Register page
  'register' => [
    'title' => 'Register',
    'create_account' => 'Create Your Account',
    'subtitle' => 'Join us to start managing your health',
    'name' => 'Full Name',
    'email' => 'Email Address',
    'password' => 'Password',
    'password_confirmation' => 'Confirm Password',
    'submit' => 'Create Account',
    'already_have_account' => 'Already have an account?',
    'login_link' => 'Sign in here',
  ],

  // Session reset success
  'session_reset_success' => [
    'title' => 'Session Deleted',
    'header' => 'Session Deleted Successfully',
    'account' => 'Account:',
    'message' => 'Your active session has been deleted. You can now sign in from this device.',
    'login_button' => 'Sign In',
    'back_to_home' => '← Back to home',
  ],

  // Email - Session Reset
  'email_session_reset' => [
    'title' => 'Active Session Deletion',
    'header' => '🔐 Session Deletion Request',
    'header_short' => '🔐 Session Deletion',
    'greeting' => 'Hello <strong>:name</strong>,',
    'greeting_simple' => 'Hello :name,',
    'detected' => 'We detected that you tried to sign in from a new device, but you already have an active session on another device.',
    'detected_strong' => 'We detected that you tried to sign in from a <strong>new device</strong>, but you already have an active session on another device.',
    'instruction' => 'If you want to close your current session to be able to sign in from the new device, click the following button:',
    'button' => 'Delete Active Session',
    'button_emoji' => '🗑️ Delete Active Session',
    'warning_title' => '⚠️ Important:',
    'warning_title_info' => '⚠️ Important Information',
    'warning_items' => [
      'all_devices' => 'By clicking, your session will be closed on <strong>all devices</strong>',
      'relogin' => 'You will need to sign in again',
      'validity' => 'This link is valid for <strong>:minutes minutes</strong>',
      'ignore' => 'If you did not request this, you can ignore this email',
    ],
    'url_instruction' => 'If you have problems with the button, copy and paste this URL into your browser:',
    'footer_title' => 'Security System',
    'footer_auto' => 'This email was automatically sent from the security system.',
    'footer_secure' => 'If you did not request this action, your account remains secure.',
    'copyright' => '© :year :app_name. All rights reserved.',
    'dev_info' => '📊 <strong>Development information:</strong>',
    'dev_user_id' => 'User ID: :id',
    'dev_timestamp' => 'Timestamp: :timestamp',
    'dev_environment' => 'Environment: :env',
  ],

  // Common authentication messages
  'failed' => 'These credentials do not match our records.',
  'password' => 'The provided password is incorrect.',
  'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
];
