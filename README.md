# PayTabs Payment Gateway Integration for Laravel

[![Latest Version](https://img.shields.io/github/v/release/granada-pride/paytabs.svg?style=flat-square)](https://github.com/granada-pride/paytabs/releases)
[![License](https://img.shields.io/github/license/granada-pride/paytabs.svg?style=flat-square)](https://github.com/granada-pride/paytabs/blob/main/LICENSE)

## Table of Contents

1. [Description](#description)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Usage](#usage)
    - [Creating a PayPage](#create-paypage)
    - [Querying Transactions](#query-transaction)
5. [Troubleshooting](#troubleshooting)
6. [Testing](#testing)
7. [Contribution](#contribution)
8. [License](#license)

## Description

PayTabs Payment Gateway Integration with Laravel Framework

## Installation

You can install the package via composer:

```bash
composer require granada-pride/paytabs:^2.0
```

> **Note:** This package is compatible with Laravel 9 and higher. Ensure your application meets this requirement before
> installing.

## Configuration

After installation, publish the configuration file using the following command:

```bash
php artisan vendor:publish --tag=granada-pride-paytabs-config
```

The configuration file paytabs.php will be added to your config directory. Here is a sample configuration:

``` php
return [
    'profile_id' => env('PAYTABS_PROFILE_ID'),
    'server_key' => env('PAYTABS_SERVER_KEY'),
    'currency' => env('PAYTABS_CURRENCY'),
    'region' => env('PAYTABS_REGION'),
];
```

## Configuration Options

- **profile_id:** Your PayTabs profile ID, required for API requests.
- **server_key:** Your PayTabs server key, used for authentication.
- **currency:** The currency code in which payments will be processed (e.g., USD).
- **region:** The region for your PayTabs account (e.g., ARE, SAU).

## Usage

### Create PayPage

Here’s how to create a payment page using this package:

``` php
use GranadaPride\Paytabs\Paytabs;
use GranadaPride\Paytabs\DTO\CustomerDetails;

$paytabs = Paytabs::make();

// Set Cart Information
$paytabs->setCart('CART123', 150.00, 'Sample Cart Description');

// Set Customer Information using the CustomerDetails DTO
$customerDetails = new CustomerDetails(
    name: 'John Doe',
    phone: '+123456789',
    email: 'johndoe@example.com',
    street: '123 Main St',
    city: 'Cityville',
    state: 'Stateland',
    country: 'US',
    zipCode: '12345'
);

$paytabs->setCustomer($customerDetails);

// Option 1: Use Customer Information for Shipping if it's the same
$paytabs->useCustomerForShipping();

// Option 2: Set Shipping Information separately if it's different
$shippingDetails = new CustomerDetails(
    name: 'Jane Doe',
    phone: '+987654321',
    email: 'janedoe@example.com',
    street: '456 Market St',
    city: 'Townsville',
    state: 'Regionland',
    country: 'US',
    zipCode: '54321'
);

$paytabs->setShipping($shippingDetails);

// Set URLs and Language
$paytabs->setCallbackUrl('https://yourdomain.com/callback')
        ->setReturnUrl('https://yourdomain.com/return')
        ->setPaypageLang('en');

// Generate Payment Page
$response = $paytabs->paypage();

// Handle the response
dd($response);
```

### Query Transaction

You can also query a transaction using its reference:

```php
use GranadaPride\Paytabs\Paytabs;

$response = Paytabs::make()
    ->queryTransaction('TST2422201903602');

dd($response);
```

## Troubleshooting

### Common Issues

- **Invalid Credentials:** Ensure that your profile_id and server_key in the configuration file are correct.
- **Unsupported Region:** Double-check that the region in your configuration file is valid and supported by PayTabs.
- **Transaction Failure:** Verify the transaction data (e.g., cart amount, customer details) to ensure it meets PayTabs'
  requirements.

If you encounter other issues, refer to the PayTabs API Documentation for more details.

## Testing

Soon...

## Contribution

Contributions are welcome! If you’d like to contribute to this package, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Write your code and ensure it is well-documented.
4. Submit a pull request with a clear description of your changes.

## License

This package is open-sourced software licensed under the MIT license.



