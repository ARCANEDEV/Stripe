Stripe PHP Library
==============
[![Travis Status](http://img.shields.io/travis/ARCANEDEV/Stripe.svg?style=flat-square)](https://travis-ci.org/ARCANEDEV/Stripe)
[![Github Release](http://img.shields.io/github/release/ARCANEDEV/Stripe.svg?style=flat-square)](https://github.com/ARCANEDEV/Stripe/releases)
[![Coverage Status](http://img.shields.io/coveralls/ARCANEDEV/Stripe.svg?style=flat-square)](https://coveralls.io/r/ARCANEDEV/Stripe?branch=master)
[![Packagist License](http://img.shields.io/packagist/l/arcanedev/sanitizer.svg?style=flat-square)](https://github.com/ARCANEDEV/Stripe/blob/master/LICENSE)
[![Packagist Downloads](https://img.shields.io/packagist/dt/arcanedev/stripe.svg?style=flat-square)](https://packagist.org/packages/arcanedev/stripe)
[![Github Issues](http://img.shields.io/github/issues/ARCANEDEV/Stripe.svg?style=flat-square)](https://github.com/ARCANEDEV/Stripe/issues)

*By [ARCANEDEV&copy;](http://www.arcanedev.net/)*

This package is "inspired" by [Stripe PHP library](https://github.com/stripe/stripe-php)

You can sign up for a Stripe account at https://stripe.com.

### Requirements
    
    - PHP >= 5.4.0
    - ext-curl: *
    - ext-json: *
    - ext-mbstring: *
    
### Composer

You can install the bindings via [Composer](http://getcomposer.org/). Add this to your `composer.json`:

    {
      "require": {
        ...
        "arcanedev/stripe": "~2.0"
        ...
      }
    }
    
Then install via:

    composer.phar install

To use the bindings, either user Composer's [autoload](https://getcomposer.org/doc/00-intro.md#autoloading):

    require_once('vendor/autoload.php');

### Getting Started

Simple usage looks like:

    use Arcanedev\Stripe\Stripe;
    use Arcanedev\Stripe\Resources\Charge;
    
    Stripe::setApiKey('your-stripe-api-key');
    
    $myCard = [
        'number'    => '4242424242424242',
        'exp_month' => 5,
        'exp_year'  => 2015
    ];
    
    $charge = Charge::create([
        'card'      => $myCard,
        'amount'    => 2000,
        'currency'  => 'usd'
    ]);
    
    var_dump($charge);

## Documentation

Please see https://stripe.com/docs/api for up-to-date documentation.

### TODOS:

  - [ ] Documentation
  - [ ] Examples
  - [ ] Extract the curl function from Requestor and using a dedicated class instead (Utilities => CurlRequest Class).
  - [ ] More tests and code coverage.
  - [ ] Mockery for curl request.
  - [ ] Stripe OAuth Class 
  - [ ] Refactoring.
