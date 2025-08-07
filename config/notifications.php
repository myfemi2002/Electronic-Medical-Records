<?php

return [
    'email' => [
        'deposit_success' => true,    // Notify users of successful deposits
        'deposit_pending' => true,    // Notify users of pending deposits
        'withdrawal_success' => true, // Notify users of successful withdrawals
        'withdrawal_pending' => true, // Notify users of pending withdrawals
        'admin_low_balance' => true,  // Notify admins of low TRX balance
    ],
    
    'sms' => [
        'enabled' => false,           // Master switch for SMS notifications
        'deposit_success' => false,   // Notify users of successful deposits via SMS
        'withdrawal_success' => false,// Notify users of successful withdrawals via SMS
        'provider' => env('SMS_PROVIDER', 'twilio'), // SMS provider (twilio, nexmo, etc.)
    ],
    
    'admin' => [
        'new_deposit' => true,        // Notify admins of new deposits
        'new_withdrawal' => true,     // Notify admins of new withdrawal requests
        'low_balance' => true,        // Notify admins of low TRX balance
        'system_error' => true,       // Notify admins of system errors
        'email' => env('ADMIN_NOTIFICATION_EMAIL', env('MAIL_FROM_ADDRESS')), // Admin notification email
    ],
    
    'channels' => [
        'mail',                       // Default notification channels
        'database',
    ],
];