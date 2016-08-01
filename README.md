# PHP library for Stripe [![Packagist License][badge_license]](LICENSE.md) [![For PHP][badge_php]][link-github-repo]

[![Travis Status][badge_build]][link-travis]
[![HHVM Status][badge_hhvm]][link-hhvm]
[![Coverage Status][badge_coverage]][link-scrutinizer]
[![Scrutinizer Code Quality][badge_quality]][link-scrutinizer]
[![SensioLabs Insight][badge_insight]][link-insight]
[![Github Issues][badge_issues]][link-github-issues]

[![Packagist][badge_package]][link-packagist]
[![Packagist Release][badge_release]][link-packagist]
[![Packagist Downloads][badge_downloads]][link-packagist]

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

$charge = Charge::create([
    'card'     => [
        'number'    => '4242424242424242',
        'exp_month' => 8,
        'exp_year'  => 2018,
    ],
    'amount'   => 2000,
    'currency' => 'usd'
]);

var_dump($charge);
```

## Documentation

Please see [Stripe API Reference](https://stripe.com/docs/api) for up-to-date documentation.

### TODOS

  - [ ] More tests and code coverage

## Contribution

Any ideas are welcome. Feel free to submit any issues or pull requests, please check the [contribution guidelines](CONTRIBUTING.md).

## Security

If you discover any security related issues, please email arcanedev.maroc@gmail.com instead of using the issue tracker.

## Credits

- Thanks to [stripe/stripe-php](https://github.com/stripe/stripe-php) team and their contributors.
- [ARCANEDEV][link-author]
- [All Contributors][link-contributors]

[badge_license]:      https://img.shields.io/packagist/l/arcanedev/stripe.svg?style=flat-square
[badge_php]:          https://img.shields.io/badge/PHP-Framework%20agnostic-4F5B93.svg?style=flat-square
[badge_build]:        https://img.shields.io/travis/ARCANEDEV/Stripe.svg?style=flat-square
[badge_hhvm]:         https://img.shields.io/hhvm/arcanedev/stripe.svg?style=flat-square
[badge_coverage]:     https://img.shields.io/scrutinizer/coverage/g/ARCANEDEV/Stripe.svg?style=flat-square
[badge_quality]:      https://img.shields.io/scrutinizer/g/ARCANEDEV/Stripe.svg?style=flat-square
[badge_insight]:      https://img.shields.io/sensiolabs/i/b9a40bba-bf68-4dc6-90f8-1978dcf6435a.svg?style=flat-square
[badge_issues]:       https://img.shields.io/github/issues/ARCANEDEV/Stripe.svg?style=flat-square
[badge_package]:      https://img.shields.io/badge/package-arcanedev/stripe-blue.svg?style=flat-square
[badge_release]:      https://img.shields.io/packagist/v/arcanedev/stripe.svg?style=flat-square
[badge_downloads]:    https://img.shields.io/packagist/dt/arcanedev/stripe.svg?style=flat-square

[link-author]:        https://github.com/arcanedev-maroc
[link-github-repo]:   https://github.com/ARCANEDEV/Stripe
[link-github-issues]: https://github.com/ARCANEDEV/Stripe/issues
[link-contributors]:  https://github.com/ARCANEDEV/Stripe/graphs/contributors
[link-packagist]:     https://packagist.org/packages/arcanedev/stripe
[link-travis]:        https://travis-ci.org/ARCANEDEV/Stripe
[link-hhvm]:          http://hhvm.h4cc.de/package/arcanedev/stripe
[link-scrutinizer]:   https://scrutinizer-ci.com/g/ARCANEDEV/Stripe/?branch=master
[link-insight]:       https://insight.sensiolabs.com/projects/b9a40bba-bf68-4dc6-90f8-1978dcf6435a
