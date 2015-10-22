# PHP library for Stripe [![Packagist License][badge_license]](LICENSE.md) [![For PHP][badge_php]](https://github.com/ARCANEDEV/Stripe)

[![Travis Status][badge_build]](https://travis-ci.org/ARCANEDEV/Stripe)
[![HHVM Status][badge_hhvm]](http://hhvm.h4cc.de/package/arcanedev/stripe)
[![Coverage Status][badge_coverage]](https://scrutinizer-ci.com/g/ARCANEDEV/Stripe/?branch=master)
[![Scrutinizer Code Quality][badge_quality]](https://scrutinizer-ci.com/g/ARCANEDEV/Stripe/?branch=master)
[![SensioLabs Insight][badge_insight]](https://insight.sensiolabs.com/projects/b9a40bba-bf68-4dc6-90f8-1978dcf6435a)
[![Github Issues][badge_issues]](https://github.com/ARCANEDEV/Stripe/issues)

[![Packagist][badge_package]](https://packagist.org/packages/arcanedev/stripe)
[![Packagist Release][badge_release]](https://packagist.org/packages/arcanedev/stripe)
[![Packagist Downloads][badge_downloads]](https://packagist.org/packages/arcanedev/stripe)

[badge_license]:   https://img.shields.io/packagist/l/arcanedev/stripe.svg?style=flat-square
[badge_php]:       https://img.shields.io/badge/PHP-Framework%20agnostic-4F5B93.svg?style=flat-square

[badge_build]:     https://img.shields.io/travis/ARCANEDEV/Stripe.svg?style=flat-square
[badge_hhvm]:      https://img.shields.io/hhvm/arcanedev/stripe.svg?style=flat-square
[badge_coverage]:  https://img.shields.io/scrutinizer/coverage/g/ARCANEDEV/Stripe.svg?style=flat-square
[badge_quality]:   https://img.shields.io/scrutinizer/g/ARCANEDEV/Stripe.svg?style=flat-square
[badge_insight]:   https://img.shields.io/sensiolabs/i/b9a40bba-bf68-4dc6-90f8-1978dcf6435a.svg?style=flat-square
[badge_issues]:    https://img.shields.io/github/issues/ARCANEDEV/Stripe.svg?style=flat-square

[badge_package]:   https://img.shields.io/badge/package-arcanedev/stripe-blue.svg?style=flat-square
[badge_release]:   https://img.shields.io/packagist/v/arcanedev/stripe.svg?style=flat-square
[badge_downloads]: https://img.shields.io/packagist/dt/arcanedev/stripe.svg?style=flat-square

*By [ARCANEDEV&copy;](http://www.arcanedev.net/)*

You can sign up for a Stripe account at https://stripe.com.

### Requirements

    - PHP >= 5.4.0
    - ext-curl: *
    - ext-json: *
    - ext-mbstring: *

## INSTALLATION

### Composer

You can install the bindings via [Composer](http://getcomposer.org/). By running `composer require arcanedev/stripe`.

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

### TODOS

  - [ ] More tests and code coverage

## Contribution

Any ideas are welcome. Feel free to submit any issues or pull requests, please check the [contribution guidelines](CONTRIBUTING.md).
