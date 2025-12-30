<?php

return [
    // Payment method info
    'payment_title' => 'Digipay (Bank Gateway)',
    'payment_description' => 'Secure payment via Digipay bank gateway',

    // Redirect page
    'redirecting' => 'Redirecting to Payment Gateway',
    'redirect_message' => 'Please wait while we redirect you to the secure payment page...',
    'order_number' => 'Order Number',
    'javascript_disabled' => 'JavaScript is disabled. Please click the button below to continue.',
    'click_to_continue' => 'Continue to Payment',

    // Status messages
    'payment_successful' => 'Payment completed successfully.',
    'payment_failed' => 'Payment failed. Please try again.',
    'payment_cancelled' => 'Payment was cancelled.',
    'order_already_processed' => 'This order has already been processed.',
    'amount_mismatch' => 'Payment amount does not match the order total.',
    'unexpected_error' => 'An unexpected error occurred. Please try again.',

    // Admin panel
    'admin' => [
        'title' => 'Digipay Gateway',
        'sandbox' => 'Sandbox Mode',
        'sandbox_info' => 'Enable this for testing with Digipay sandbox environment',
        'client_id' => 'Client ID',
        'client_id_info' => 'OAuth Client ID provided by Digipay',
        'client_secret' => 'Client Secret',
        'username' => 'Username',
        'password' => 'Password',
        'api_version' => 'API Version',
        'api_version_info' => 'Digipay API version to use',
        'preferred_gateway' => 'Preferred Gateway',
        'preferred_gateway_info' => 'Select the payment method to use',
        'gateway_ipg' => 'Bank Gateway (IPG)',
        'gateway_wallet' => 'Digipay Wallet',
    ],

    // Validation messages
    'validation' => [
        'amount_required' => 'Amount is required',
        'provider_id_required' => 'Provider ID is required',
        'tracking_code_required' => 'Tracking code is required',
        'result_required' => 'Payment result is required',
    ],
];
