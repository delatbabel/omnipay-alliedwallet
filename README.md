# Omnipay: Allied Wallet

**Allied Wallet driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/delatbabel/omnipay-alliedwallet.png?branch=master)](https://travis-ci.org/delatbabel/omnipay-alliedwallet)
[![Latest Stable Version](https://poser.pugx.org/delatbabel/omnipay-alliedwallet/version.png)](https://packagist.org/packages/delatbabel/omnipay-alliedwallet)
[![Total Downloads](https://poser.pugx.org/delatbabel/omnipay-alliedwallet/d/total.png)](https://packagist.org/packages/delatbabel/omnipay-alliedwallet)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements [Allied Wallet](https://www.alliedwallet.com/) support for Omnipay.

[Allied Wallet](https://www.alliedwallet.com/) offers customized payment solutions to businesses of any size. Allied Wallet provide payment processing services in 164 currencies, 196 countries, and nearly every payment method globally.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "delatbabel/omnipay-alliedwallet": "~2.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* AlliedWallet

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

### Quirks

* Token payments are a new addition and not mentioned in the current API documentation. They have been added to this version of the plugin but not tested yet.
* The parameters passed to the gateway are not case sensitive.
* For card payments, there are a lot of mandatory fields for cardholder information. First and last names, phone number, address, city, state, postal code, country, are all listed as mandatory by the gateway documentation.
* A unique transaction Id (sent to the gateway as trackingId) is required for every transaction. This is alphanumeric with a limit of 100 characters.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/thephpleague/omnipay-alliedwallet/issues),
or better yet, fork the library and submit a pull request.
