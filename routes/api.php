<?php

/** @var $router \Illuminate\Routing\Router */

//  public route configuration
$router->group(['prefix' => config('json-api.routes.public-route.prefix'), 'middleware' => 'api', 'namespace' => ''],
    function (\Illuminate\Routing\Router $router) {

        $router->group(['prefix' => 'v{version}'], function (\Illuminate\Routing\Router $router) {

            $router->get('/{resource}', [
                'uses' => config('json-api.routes.public-route.controller') . '@collection',
                'as' => 'api.resource',
            ])->where(['resource' => '[a-z\-]+']);

            $router->post('/{resource}', [
                'uses' => config('json-api.routes.public-route.controller') . '@create',
            ]);

            $router->get('/{resource}/{id}', [
                'uses' => config('json-api.routes.public-route.controller') . '@item',
                'as' => 'api.resource.item',
            ])->where(['resource' => '[a-z\-]+']);

            $router->patch('/{resource}/{id}', [
                'uses' => config('json-api.routes.public-route.controller') . '@patch',
            ]);

            $router->delete('/{resource}/{id}', [
                'uses' => config('json-api.routes.public-route.controller') . '@delete',
            ]);

            $router->get('/{resource}/{id}/relationships/{relationship}', [
                'uses' => config('json-api.routes.public-route.controller') . '@relatedCollection',
                'as' => 'api.resource.relationship',
            ])->where(['resource' => '[a-z\-]+', 'relationship' => '[a-z\-]+']);

            $router->post('/{resource}/{id}/relationships/{relationship}', [
                'uses' => config('json-api.routes.public-route.controller') . '@relatedPost',
            ])->where(['resource' => '[a-z\-]+', 'relationship' => '[a-z\-]+']);

            $router->patch('/{resource}/{id}/relationships/{relationship}', [
                'uses' => config('json-api.routes.public-route.controller') . '@relatedPatch',
            ])->where(['resource' => '[a-z\-]+', 'relationship' => '[a-z\-]+']);

            $router->get('/{resource}/{id}/relationships/{relationship}/{parameter}', [
                'uses' => config('json-api.routes.public-route.controller') . '@relatedItem',
                'as' => 'api.resource.relationship.item',
            ])->where(['resource' => '[a-z\-]+', 'relationship' => '[a-z\-]+']);

	        $router->delete('/{resource}/{id}/relationships/{relationship}', [
		        'uses' => config('json-api.routes.public-route.controller') . '@relatedDelete',
	        ])->where(['resource' => '[a-z\-]+', 'relationship' => '[a-z\-]+']);

            $router->delete('/{resource}/{id}/relationships/{relationship}/{parameter}', [
                'uses' => config('json-api.routes.public-route.controller') . '@relatedItemDelete',
            ])->where(['resource' => '[a-z\-]+', 'relationship' => '[a-z\-]+']);
        });
    });

//  secure route configuration
$router->group([
    'prefix' => config('json-api.routes.secure-route.prefix'),
    'middleware' => 'secure-api',
    'namespace' => ''
], function (\Illuminate\Routing\Router $router) {

    $router->group(['prefix' => 'v{version}'], function (\Illuminate\Routing\Router $router) {

        $router->post('/tokens/refresh', [
            'middleware' => 'jwt.refresh',
            'as' => 'token.refresh',
            'uses' => function () {
                return response()->json([], 201, [
                    'Access-Control-Expose-Headers' => 'Authorization'
                ]);
            }
        ]);

        $router->get('/{resource}', [
            'uses' => config('json-api.routes.secure-route.controller') . '@collection',
            'as' => 'secure-api.resource',
            'middleware' => config('json-api.routes.secure-route.middleware', 'jwt.auth'),
        ])->where(['resource' => '[a-z\-]+']);

        $router->post('/{resource}', [
            'uses' => config('json-api.routes.secure-route.controller') . '@create',
            'middleware' => config('json-api.routes.secure-route.middleware', 'jwt.auth'),
        ])->where(['resource' => '[a-z\-]+']);

        $router->get('/{resource}/{id}', [
            'uses' => config('json-api.routes.secure-route.controller') . '@item',
            'as' => 'secure-api.resource.item',
            'middleware' => config('json-api.routes.secure-route.middleware', 'jwt.auth'),
        ])->where(['resource' => '[a-z\-]+']);

        $router->patch('/{resource}/{id}', [
            'uses' => config('json-api.routes.public-route.controller') . '@patch',
            'middleware' => config('json-api.routes.secure-route.middleware', 'jwt.auth'),
        ]);

        $router->delete('/{resource}/{id}', [
            'uses' => config('json-api.routes.public-route.controller') . '@delete',
            'middleware' => config('json-api.routes.secure-route.middleware', 'jwt.auth'),
        ]);

        $router->get('/{resource}/{id}/relationships/{relationship}', [
            'uses' => config('json-api.routes.secure-route.controller') . '@relatedCollection',
            'as' => 'secure-api.resource.relationship',
            'middleware' => config('json-api.routes.secure-route.middleware', 'jwt.auth'),
        ])->where(['resource' => '[a-z\-]+', 'relationship' => '[a-z\-]+']);

        $router->post('/{resource}/{id}/relationships/{relationship}', [
            'uses' => config('json-api.routes.public-route.controller') . '@relatedPost',
            'middleware' => config('json-api.routes.secure-route.middleware', 'jwt.auth'),
        ])->where(['resource' => '[a-z\-]+', 'relationship' => '[a-z\-]+']);

        $router->patch('/{resource}/{id}/relationships/{relationship}', [
            'uses' => config('json-api.routes.public-route.controller') . '@relatedPatch',
            'middleware' => config('json-api.routes.secure-route.middleware', 'jwt.auth'),
        ])->where(['resource' => '[a-z\-]+', 'relationship' => '[a-z\-]+']);

        $router->get('/{resource}/{id}/relationships/{relationship}/{parameter}', [
            'uses' => config('json-api.routes.secure-route.controller') . '@relatedItem',
            'as' => 'secure-api.resource.relationship.item',
            'middleware' => config('json-api.routes.secure-route.middleware', 'jwt.auth'),
        ])->where(['resource' => '[a-z\-]+', 'relationship' => '[a-z\-]+']);

	    $router->delete('/{resource}/{id}/relationships/{relationship}', [
		    'uses' => config('json-api.routes.secure-route.controller') . '@relatedDelete',
		    'middleware' => config('json-api.routes.secure-route.middleware', 'jwt.auth'),
	    ])->where(['resource' => '[a-z\-]+', 'relationship' => '[a-z\-]+']);

        $router->delete('/{resource}/{id}/relationships/{relationship}/{parameter}', [
            'uses' => config('json-api.routes.secure-route.controller') . '@relatedItemDelete',
            'middleware' => config('json-api.routes.secure-route.middleware', 'jwt.auth'),
        ])->where(['resource' => '[a-z\-]+', 'relationship' => '[a-z\-]+']);
    });
});