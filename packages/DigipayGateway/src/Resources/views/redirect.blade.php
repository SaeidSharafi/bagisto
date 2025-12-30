<!DOCTYPE html>
<html lang="fa" dir="rtl">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ __('digipay::messages.redirecting') }}</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: Tahoma, Arial, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                direction: rtl;
            }

            .container {
                background: white;
                border-radius: 16px;
                padding: 48px;
                text-align: center;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                max-width: 400px;
                width: 90%;
            }

            .logo {
                width: 80px;
                height: 80px;
                margin: 0 auto 24px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .logo svg {
                width: 40px;
                height: 40px;
                fill: white;
            }

            h1 {
                color: #333;
                font-size: 1.5rem;
                margin-bottom: 16px;
            }

            p {
                color: #666;
                font-size: 1rem;
                margin-bottom: 24px;
                line-height: 1.6;
            }

            .spinner {
                width: 48px;
                height: 48px;
                border: 4px solid #f3f3f3;
                border-top: 4px solid #667eea;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 24px;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            }

            .order-info {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 16px;
                margin-top: 16px;
            }

            .order-info span {
                color: #888;
                font-size: 0.875rem;
            }

            .order-info strong {
                color: #333;
                font-size: 1rem;
            }

            noscript {
                display: block;
                margin-top: 24px;
            }

            .manual-link {
                display: inline-block;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                text-decoration: none;
                padding: 12px 32px;
                border-radius: 8px;
                font-weight: bold;
                transition: transform 0.2s, box-shadow 0.2s;
            }

            .manual-link:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"
                    />
                </svg>
            </div>

            <h1>{{ __('digipay::messages.redirecting') }}</h1>

            <div class="spinner"></div>

            <p>{{ __('digipay::messages.redirect_message') }}</p>

            @isset($order_id)
            <div class="order-info">
                <span>{{ __('digipay::messages.order_number') }}:</span>
                <strong>#{{ $order_id }}</strong>
            </div>
            @endisset

            <noscript>
                <p>{{ __('digipay::messages.javascript_disabled') }}</p>
                <a href="{{ $redirect_url }}" class="manual-link">
                    {{ __('digipay::messages.click_to_continue') }}
                </a>
            </noscript>
        </div>

        <script>
            // Redirect to payment gateway after a short delay
            setTimeout(function () {
                window.location.href = '{{ $redirect_url }}';
            }, 1500);
        </script>
    </body>
</html>
