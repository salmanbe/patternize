Patternize
====
Automatic trim, single space, lower, upper, slugify, replace, strip, alpha num value before saving to database. This library works with Observers.

[![License](https://img.shields.io/github/license/salmanbe/Patternize)](https://github.com/salmanbe/resize/blob/master/LICENSE)

Laravel Installation
-------
Install using composer:
```bash
composer require salmanbe/patternize
```

There is a service provider included for integration with the Laravel framework. This service should automatically be registered else to register the service provider, add the following to the providers array in `config/app.php`:

```php
Salmanbe\Patternize\PatternizeServiceProvider::class,
```
You can also add it as a Facade in `config/app.php`:
```php
'Filename' => Salmanbe\Patternize\Patternize::class,
```
Global Configuration
-----
Run `php artisan vendor:publish --provider="Salmanbe\Patternize\PatternizeServiceProvider"` to publish configuration file.

Basic Usage
-----

Add `use Salmanbe\Patternize\Patternize;` at top of the class where you want to use it. Then

```php
$this->patternize($model, __FUNCTION__);
```

Full Documentation
-----

[Follow the link for installation, configuration, options and code examples.](https://www.salman.be/api/patternize)

Uninstall
-----
First remove `Salmanbe\Patternize\PatternizeServiceProvider::class,` and 
`'Filename' => Salmanbe\Patternize\Patternize::class,` from `config/app.php` if it was added.
Then Run `composer remove salmanbe/patternize` 

## License

This library is licensed under THE MIT License. Please see [License File](https://github.com/salmanbe/patternize/blob/master/LICENSE) for more information.

## Security contact information

To report a security vulnerability, follow [these steps](https://tidelift.com/security).