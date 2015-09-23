PHP library for Stripe [![Packagist License](http://img.shields.io/packagist/l/arcanedev/sanitizer.svg?style=flat-square)](https://github.com/ARCANEDEV/Stripe/blob/master/LICENSE)
==============
[![Travis Status](https://img.shields.io/scrutinizer/build/g/ARCANEDEV/Stripe.svg?style=flat-square)](https://travis-ci.org/ARCANEDEV/Stripe)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/ARCANEDEV/Stripe.svg?style=flat-square)](https://scrutinizer-ci.com/g/ARCANEDEV/Stripe/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/ARCANEDEV/Stripe.svg?style=flat-square)](https://scrutinizer-ci.com/g/ARCANEDEV/Stripe/?branch=master)
[![Github Issues](http://img.shields.io/github/issues/ARCANEDEV/Stripe.svg?style=flat-square)](https://github.com/ARCANEDEV/Stripe/issues)
[![Github Release](http://img.shields.io/github/release/ARCANEDEV/Stripe.svg?style=flat-square)](https://github.com/ARCANEDEV/Stripe/releases)
[![Packagist Downloads](https://img.shields.io/packagist/dt/arcanedev/stripe.svg?style=flat-square)](https://packagist.org/packages/arcanedev/stripe)

*By [ARCANEDEV&copy;](http://www.arcanedev.net/)*

You can sign up for a Stripe account at https://stripe.com.

### Requirements
    
    - PHP >= 5.4.0
    - ext-curl: *
    - ext-json: *
    - ext-mbstring: *
    
## INSTALLATION

### Composer

You can install the bindings via [Composer](http://getcomposer.org/). Add this to your `composer.json` :

```json
{
    "require": {
        "arcanedev/stripe": "~3.0"
    }
}
```

Then install it via `composer install` or `composer update`.

## USAGE

Simple usage looks like :

```php
require_once('vendor/autoload.php');

use Arcanedev\Stripe\Stripe;
use Arcanedev\Stripe\Resources\Charge;
    
Stripe::setApiKey('your-stripe-api-key');
    
$myCard = [
    'number'    => '4242424242424242',
    'exp_month' => 8,
    'exp_year'  => 2018,
];
    
$charge = Charge::create([
    'card'      => $myCard,
    'amount'    => 2000,
    'currency'  => 'usd'
]);
    
var_dump($charge);
```

## Documentation

Please see [Stripe API Reference](https://stripe.com/docs/api) for up-to-date documentation.

### TODOS:

  - [ ] Documentation
  - [ ] Examples
  - [ ] More tests and code coverage
  - [x] Mock HTTP requests
