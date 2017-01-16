# FastFrame Kernel

An unassuming interface to running a http or console kernel. This defines
the basic Kernel interace, and the implementation of an Environment and 
ProviderList for Container Interop configuration.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fastframe/kernel.svg?style=flat-square)](https://packagist.org/packages/fastframe/kernel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/fastframe/kernel/master.svg?style=flat-square)](https://travis-ci.org/fastframe/kernel)
[![Code Climate](https://codeclimate.com/github/fastframe/kernel/badges/gpa.svg)](https://codeclimate.com/github/fastframe/kernel)
[![Test Coverage](https://codeclimate.com/github/fastframe/kernel/badges/coverage.svg)](https://codeclimate.com/github/fastframe/kernel/coverage)

## Install

Via Composer
```sh
$ composer require fastframe/kernel
```

## Usage

The following classes are provided by this library:

  * `FastFrame\Kernel\Environment` Contains kernel runtime environment variables
  * `FastFrame\Kernel\ProviderList` Maintains a list of providers with the ability to run define/modify against all of the providers

The following trait is provided by this library:
  * `FastFrame\Kernel\HasSubProviders` Allows to more easily run the define/modify process on the providerlist.

For general usage instructions, please read the documentation [here](./docs/index.md).

## Quality

This package attempts to comply with [PSR-1][] and [PSR-4][]. If
you notice compliance oversights, please send a patch via pull request.

## Support

If you believe you have found a bug, please report it using the [Github issue tracker](https://github.com/fastframe/utility/issues),
or better yet, fork the library and submit a pull request.

## Testing

```sh
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [David Lundgren](https://github.com/dlundgren)
- [All Contributors](../../contributors)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md