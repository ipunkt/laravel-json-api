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

Publish Configuration (optional step, but suggested):
```shell
php artisan vendor:publish --provider="Ipunkt\LaravelJsonApi\LaravelJsonApiServiceProvider"
```

Set the necessary middleware in `app/Http/Kernel.php`:
```php
'jwt.auth' => \Tymon\JWTAuth\Middleware\GetUserFromToken::class,
'jwt.refresh' => \Tymon\JWTAuth\Middleware\RefreshToken::class,
```

## Configuration

By default the package configures all routes itself. This is the suggested option.

You can configure the json api responses as well. There are optional response elements in the json api 1.0 standard. By default we return them all, but you can turn them off if you want to save response bytes.

### routes section

#### configure

Do you want the routes being configured by the package itself? Leave it true in most cases.

#### public-route

We have public and secure routes by default in the package. Public routes do not check authentication. This is for authenticating an user or public accessible api endpoints.

Here you can configure the `prefix` for the route and the `controller` for handling requests.

#### secure-route

Secure routes check authentication with each request. Here you need a JWT access token for accessing these resources.

Here you can configure the `prefix` for the route and the `controller` for handling requests. You have also the option to define `middleware`. A `jwt.auth` called middleware is configured by default.

### response section

#### resources

Resources can have a links section. Shall the package add the self link automatically? It will be added by default, but it is not necessary in every case.

Resource items can have a self link too. This will be added automatically by the package by default. You can turn that off if you do not need it.

#### relationships

Relationships and the items itself can have a links section with the self and related link. These can all be added automatically by the package. You can turn that off, if you do not need it.

## Definition

### Setup

We suggest using the `app/Providers/AppServiceProvider` or create your own `ApiResourceServiceProvider`.

Customize your `boot` method to type hint the `\Ipunkt\LaravelJsonApi\Resources\ResourceManager` as parameter, like so:

```php
public function boot(ResourceManager $resourceManager)
```

### Define a resource

The `JsonApiController` handles the incoming api request. It uses the `ResourceManager` to get to know all defined resources. So define your resources like so:

```php
//  define in api version 1 a resource called 'posts'
$resourceManager->version(1)
    ->define('posts', function (ResourceDefinition $resource) {
        $resource->setSerializer(PostSerializer::class)
            ->setRepository(PostRepository::class);
    });
```
Each Api has various versions, 1 at minimum.

For each version you can define resources (URL `/public/v1/posts`) and as a callback you can define various types: at least a repository for fetching the resource models and a serializer to transform the fetched model data into your wanted format.

Additionally you can define a custom request handler. There you can process the whole request yourself until returning the response. So you have full control.

The Json Api Standard has various filter options. We have a Filter Factory to support this kind of layer. A Filter Factory handles the given filter and sets it to the repository by default. So you can filter by attribute or search within a time period through request parameters. If you want to use filter you have to define a filter factory. Otherwise your filters will not be applied.
