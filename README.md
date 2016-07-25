# ISD Core

[![Software License][ico-license]](LICENSE.md)

ISD Core Package add ISD-Group special functionality to your Laravel5 project

## Install

Via Composer

Add the following to you composer.json:

``` bash
    "repositories": [
        { "type": "package", "package": {"name": "isdgroup/isdcore", "version": "dev", "source": {"url": "git@github.com:andyjam/laravel-googlelang.git", "type": "git", "reference": "master" } } }
    ]
```

``` bash
$ composer require isdgroup/isdcore
```

## Usage

add to config/app.php
``` php
'Isdgroup\Isdcore\IsdcoreServiceProvider',
```

to update lang run
``` bash
php artisan isdgroup:update:lang <google_doc_id>
```

where <google_doc_id> is ID of the document from the url string (example: 1Pad-mK4oazehsacW6i5vnfShZCrhokhpv29_45Ikw5U)

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

(not available now)

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email assada@isd-group.com instead of using the issue tracker.

## Credits

- [assada][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/league/:package_name.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/thephpleague/:package_name/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/thephpleague/:package_name.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/thephpleague/:package_name.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/league/:package_name.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/league/:package_name
[link-travis]: https://travis-ci.org/thephpleague/:package_name
[link-scrutinizer]: https://scrutinizer-ci.com/g/thephpleague/:package_name/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/thephpleague/:package_name
[link-downloads]: https://packagist.org/packages/league/:package_name
[link-author]: https://github.com/assada
[link-contributors]: ../../contributors
