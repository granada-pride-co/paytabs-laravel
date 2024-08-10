## Description

PayTabs Payment Gateway Integration with Laravel Framework

## Installation

You can install the package via composer:

```bash
composer require granada-pride/paytabs:^2.0
```

## Configuration

<p>The configuration file paytabs.php is located in the config folder. Following are its contents when published:</p>

``` php
return [
    'profile_id' => env('PAYTABS_PROFILE_ID'),
    'server_key' => env('PAYTABS_SERVER_KEY'),
    'currency' => env('PAYTABS_CURRENCY'),
    'region' => env('PAYTABS_REGION'),
];
```

You can publish config file using command

```bash
php artisan vendor:publish --tag=granada-pride-paytabs-config
```

## Usage

## Create PayPage

``` php
use GranadaPride\Paytabs\Paytabs;
use GranadaPride\Paytabs\DTO\CustomerDetails;
use GranadaPride\Paytabs\DTO\ShippingDetails;

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

// Set Shipping Information using the ShippingDetails DTO
$shippingDetails = new ShippingDetails(
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
