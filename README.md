# JSON Api

JSON Api Package for Laravel

[![Latest Stable Version](https://poser.pugx.org/ipunkt/laravel-json-api/v/stable.svg)](https://packagist.org/packages/ipunkt/laravel-json-api) [![Latest Unstable Version](https://poser.pugx.org/ipunkt/laravel-json-api/v/unstable.svg)](https://packagist.org/packages/ipunkt/laravel-json-api) [![License](https://poser.pugx.org/ipunkt/laravel-json-api/license.svg)](https://packagist.org/packages/ipunkt/laravel-json-api) [![Total Downloads](https://poser.pugx.org/ipunkt/laravel-json-api/downloads.svg)](https://packagist.org/packages/ipunkt/laravel-json-api)


## Installation

```shell
composer require ipunkt/laravel-json-api:dev-master
```

Add service provider to `config/app.php`:
```php
'providers' => [
	\Ipunkt\LaravelJsonApi\LaravelJsonApiServiceProvider::class,
]
```