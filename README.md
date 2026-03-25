# Omnipay: Easytransac
[![Build](https://github.com/easytransac/omnipay-easytransac/actions/workflows/build.yml/badge.svg)](https://github.com/easytransac/omnipay-easytransac/actions/workflows/build.yml)
[![PSR12](https://github.com/easytransac/omnipay-easytransac/actions/workflows/psr12.yml/badge.svg)](https://github.com/easytransac/omnipay-easytransac/actions/workflows/psr12.yml)
[![Test](https://github.com/easytransac/omnipay-easytransac/actions/workflows/test.yml/badge.svg)](https://github.com/easytransac/omnipay-easytransac/actions/workflows/test.yml)

**Easytransac driver for the Omnipay PHP payment processing library**

<!--[![Build Status](https://travis-ci.org/thephpleague/omnipay-easytransac.png?branch=master)](https://travis-ci.org/thephpleague/omnipay-easytransac)-->
<!--[![Latest Stable Version](https://poser.pugx.org/omnipay/easytransac/version.png)](https://packagist.org/packages/omnipay/easytransac)-->
<!--[![Total Downloads](https://poser.pugx.org/omnipay/easytransac/d/total.png)](https://packagist.org/packages/omnipay/easytransac)-->

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Easytransac support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "easytransac/omnipay-easytransac": "~1.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following methods are provided by this package:

+ purchase
+ completePurchase
+ refund

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.


## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you are having specifical issues with Easytransac, please contact us: [support@easytransac.com](support@easytransac.com)

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/easytransac/omnipay-easytransac/issues),
or better yet, fork the library and submit a pull request.
