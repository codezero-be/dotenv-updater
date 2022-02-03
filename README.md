# PHP `.env` Updater

[![GitHub release](https://img.shields.io/github/release/codezero-be/dotenv-updater.svg?style=flat-square)](https://github.com/codezero-be/dotenv-updater/releases)
[![License](https://img.shields.io/packagist/l/codezero/dotenv-updater.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/github/workflow/status/codezero-be/dotenv-updater/Tests/master?style=flat-square&logo=github&logoColor=white&label=tests)](https://github.com/codezero-be/dotenv-updater/actions)
[![Code Coverage](https://img.shields.io/codacy/coverage/6b90492697b14556998607da55510676/master?style=flat-square)](https://app.codacy.com/gh/codezero-be/dotenv-updater)
[![Code Quality](https://img.shields.io/codacy/grade/6b90492697b14556998607da55510676/master?style=flat-square)](https://app.codacy.com/gh/codezero-be/dotenv-updater)
[![Total Downloads](https://img.shields.io/packagist/dt/codezero/dotenv-updater.svg?style=flat-square)](https://packagist.org/packages/codezero/dotenv-updater)

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

A complete list of all notable changes to this package can be found on the
[releases page](https://github.com/codezero-be/dotenv-updater/releases).

## 📜 License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
