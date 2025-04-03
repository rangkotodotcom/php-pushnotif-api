# PUSHNOTIF API

This package is used to interact with the PUSHNOTIF API belonging to School.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rangkotodotcom/pushnotif.svg?style=flat-square)](https://packagist.org/packages/rangkotodotcom/pushnotif)
[![Total Downloads](https://img.shields.io/packagist/dt/rangkotodotcom/pushnotif.svg?style=flat-square)](https://packagist.org/packages/rangkotodotcom/pushnotif)

## Installation

You can install the package via composer:

```bash
composer require rangkotodotcom/pushnotif
```

#### Setup

You must register the service provider :

```php
// config/app.php

'Providers' => [
   // ...
   Rangkotodotcom\Pushnotif\PushnotifServiceProvider::class,
]
```

If you want to make use of the facade you must install it as well :

```php
// config/app.php

'aliases' => [
    // ...
    'Pushnotif' => Rangkotodotcom\Pushnotif\Pushnotif::class,
];
```

Next, You must publish the config file to define your Pushnotif CREDENTIAL :

```bash
php artisan vendor:publish --provider="Rangkotodotcom\Pushnotif\PushnotifServiceProvider"
```

This is the contents of the published file :

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Pushnotif Mode
    |--------------------------------------------------------------------------
    |
    | By default, use development. Supported Mode: "development", "production"
    |
    */

    'pushnotif_mode' => env('PUSHNOTIF_MODE', 'development'),

    /*
    |--------------------------------------------------------------------------
    | Pushnotif Client ID
    |--------------------------------------------------------------------------
    |
    | Client ID from PUSHNOTIF API
    |
    */

    'pushnotif_client_id' => env('PUSHNOTIF_CLIENT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Pushnotif Client Secret
    |--------------------------------------------------------------------------
    |
    | Client Secret from PUSHNOTIF API
    |
    */

    'pushnotif_client_secret' => env('PUSHNOTIF_CLIENT_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Pushnotif Main Domain
    |--------------------------------------------------------------------------
    |
    | Main Domain from PUSHNOTIF API
    |
    */

    'pushnotif_main_domain' => env('PUSHNOTIF_MAIN_DOMAIN', 'school.sch.id'),
];
```

Set your PUSHNOTIF CREDENTIAL in `.env` file :

```
APP_NAME="Laravel"
# ...
PUSHNOTIF_MODE=developmentOrProduction
PUSHNOTIF_CLIENT_ID=putYourClientIdHere
PUSHNOTIF_CLIENT_SECRET=putYourClientSecretHere
PUSHNOTIF_MAIN_DOMAIN=domain.loc
```

### Methods Ref

-   `::registerToken()`

-   `::getMasterNotification()`

-   `::postMasterNotification()`

-   `::putMasterNotification()`

-   `::deleteMasterNotification()`

-   `::getNews()`

-   `::postNews()`

-   `::putNews()`

-   `::deleteNews()`

-   `::getInformation()`

-   `::postInformation()`

-   `::putInformation()`

-   `::deleteInformation()`

-   `::countNotification()`

-   `::getNotification()`

-   `::getNotificationById()`

-   `::postNotification()`

-   `::readNotification()`

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

-   [jamilur rusydi](https://github.com/rangkotodotcom)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
