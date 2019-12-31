# PHP `.env` Updater

[![GitHub release](https://img.shields.io/github/release/codezero-be/dotenv-updater.svg)]()
[![License](https://img.shields.io/packagist/l/codezero/dotenv-updater.svg)]()
[![Build Status](https://scrutinizer-ci.com/g/codezero-be/dotenv-updater/badges/build.png?b=master)](https://scrutinizer-ci.com/g/codezero-be/dotenv-updater/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/codezero-be/dotenv-updater/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/codezero-be/dotenv-updater/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/codezero-be/dotenv-updater/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/codezero-be/dotenv-updater/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/codezero/dotenv-updater.svg)](https://packagist.org/packages/codezero/dotenv-updater)

[![ko-fi](https://www.ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/R6R3UQ8V)

#### Update key/value pairs in a `.env` file.

## ✅ Requirements

- PHP >= 7.1

## 📦 Install

```bash
composer require codezero/dotenv-updater
```

## 🛠 Usage

Create an instance of the `DotEnvUpdater` and pass it the path to the `.env` file:

```php
$updater = new \CodeZero\DotEnvUpdater\DotEnvUpdater('/path/to/.env');
```

Add any new, or overwrite any existing key/value pairs:

```php
$updater->set('MY_ENV_KEY', 'Some Value'); // Strings
$updater->set('MY_ENV_KEY', 25); // Integers
$updater->set('MY_ENV_KEY', true); // Booleans
$updater->set('MY_ENV_KEY', null); // NULL values
$updater->set('MY_ENV_KEY', ''); // Empty values
```

Retrieve the value of a key:

```php
$value = $updater->get('MY_ENV_KEY');
```

## 🚧 Testing

```bash
composer test
```

## ☕️ Credits

- [Ivan Vermeyen](https://byterider.io)
- [All contributors](../../contributors)

## 🔓 Security

If you discover any security related issues, please [e-mail me](mailto:ivan@codezero.be) instead of using the issue tracker.

## 📑 Changelog

See a list of important changes in the [changelog](CHANGELOG.md).

## 📜 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
