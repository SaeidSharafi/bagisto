# Digipay Gateway for Bagisto

A clean, well-structured Digipay payment gateway package for Bagisto e-commerce platform.

## Features

- **IPG (Bank Payment Gateway)** support
- OAuth2 authentication with token caching
- Clean architecture with interface-driven design
- Proper error handling with typed exceptions
- Full logging with sensitive data masking
- Sandbox/Production environment support
- Persian and English translations
- Beautiful redirect page with loading animation

## Installation

### 1. Add package to composer.json

Add the repository to your `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "packages/DigipayGateway"
        }
    ],
    "require": {
        "kuro/digipay-gateway": "*"
    }
}
```

Then run:

```bash
composer update
```

### 2. Register Service Provider

Add to `config/app.php` providers array:

```php
'providers' => [
    // ...
    DigipayGateway\Providers\DigipayServiceProvider::class,
],
```

### 3. Publish Configuration (Optional)

```bash
php artisan vendor:publish --tag=digipay-config
php artisan vendor:publish --tag=digipay-views
php artisan vendor:publish --tag=digipay-lang
```

### 4. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

## Configuration

Go to **Admin Panel** → **Configuration** → **Sales** → **Payment Methods** → **Digipay Gateway**

Configure the following:

| Field | Description |
|-------|-------------|
| Title | Payment method title shown to customers |
| Description | Payment method description |
| Status | Enable/disable the payment method |
| Sandbox Mode | Enable for testing with Digipay sandbox |
| Client ID | OAuth Client ID from Digipay |
| Client Secret | OAuth Client Secret from Digipay |
| Username | Digipay account username |
| Password | Digipay account password |
| API Version | Digipay API version (default: 2022-02-02) |
| Preferred Gateway | IPG (Bank) or Wallet |

## Architecture

```
DigipayGateway/
├── src/
│   ├── Config/           # Configuration files
│   ├── Contracts/        # Interfaces
│   ├── DataTransferObjects/  # DTOs for type safety
│   ├── Enums/            # Status codes and types
│   ├── Exceptions/       # Typed exceptions
│   ├── Http/             # Controllers and routes
│   ├── Infrastructure/   # HTTP client, OAuth, config
│   ├── Payment/          # Bagisto payment class
│   ├── Providers/        # Service provider
│   ├── Resources/        # Views and translations
│   └── Services/         # Business logic
```

## Payment Flow

1. Customer selects Digipay at checkout
2. Order is created and ticket is requested from Digipay
3. Customer is redirected to Digipay payment page
4. After payment, Digipay calls the callback URL
5. Payment is verified and order status is updated
6. Customer is redirected to success/failure page

## Error Codes

| Code | Description |
|------|-------------|
| 0 | Success |
| 1054 | Invalid input parameters |
| 9000 | Transaction not found |
| 9001 | Invalid payment token |
| 9003 | Token expired |
| 9006 | PSP communication error |
| 9007 | Payment failed |
| 9009 | Verification timeout |
| 9010 | Verification failed |
| 9011 | Verification indeterminate |

## Logging

Logs are written to the configured channel (default: `stack`). Sensitive data like passwords and tokens are automatically masked.

Enable/disable logging via environment:

```env
DIGIPAY_LOGGING=true
DIGIPAY_LOG_CHANNEL=stack
```

## Testing

### Sandbox Credentials

Contact Digipay to obtain sandbox credentials for testing.

### Test Flow

1. Enable Sandbox Mode in admin
2. Configure sandbox credentials
3. Place a test order
4. Use test card numbers provided by Digipay

## License

MIT License
