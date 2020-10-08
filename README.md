# Clubplanner PHP SDK

This is a PHP SDK to connect with the Clubplanner API.
This is a Work In Progress, heavily inspired by Picqer/Moneybird.

To run tests or use this API, get your url & tokens from clubplanner support.

## Install

Via Composer

``` bash
$ composer require proclame/clubplanner
```

## Usage

``` php
$clubplanner = new Proclame\Clubplanner();
$member = $clubplanner->member()->find(123);
```

## Testing

* copy phpunit.xml.dist to phpunit.xml
enter the CLUBPLANNER_URL and CLUBPLANNER_TOKEN in phpunit.xml

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email nick@proclame.be instead of using the issue tracker.

## Credits

- [Nick Mispoulier][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-packagist]: https://packagist.org/packages/proclame/clubplanner
[link-author]: https://github.com/proclame
