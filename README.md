## Description

PayTabs Payment Gateway Integration with Laravel Framework

## Installation

You can install the package via composer:

```bash
composer require granada-pride/paytabs
```

## Configuration

<p>The configuration file paytabs.php is located in the config folder. Following are its contents when published:</p>

``` php
    'profile_id' => env('PAYTABS_PROFILE_ID'),
    'server_key' => env('PAYTABS_SERVER_KEY'),
    'currency' => env('PAYTABS_CURRENCY'),
    'region' => env('PAYTABS_REGION'),
```

## Usage

## Create PayPage

``` php
    Paytabs::make()
        ->setCart('123', 1000, 'Cart description')
        ->setCustomer('Ahmad Mohamed', '0501234567', 'example@mail.com', 'Street', 'City', 'State',
            'Country', 'zip')
        ->setShipping('Ahmad Mohamed', '0501234567', 'example@mail.com', 'Street', 'City', 'State',
            'Country', 'zip')
        ->setCallbackUrl('https://my-site.test/callback')
        ->setReturnUrl('https://my-site.test/return')
        ->setPaypageLang('ar')
        ->paypage();
```
