<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('register', 'AuthController@register');
$router->post('login', 'AuthController@login');

$router->get('products', 'ProductsController@index');
$router->get('products/{id}', 'ProductsController@show');

$router->group(['middleware' => 'jwt.auth'], function ($router) {
    $router->post('logout', 'AuthController@logout');
    $router->post('user-profile', 'AuthController@me');
    $router->post('refresh', 'AuthController@refresh');

    $router->get('admin/users', 'Admin\UsersController@index');
    
    $router->get('admin/products', 'Admin\ProductsController@index');
    $router->post('admin/products', 'Admin\ProductsController@store');
    $router->get('admin/products/{id}', 'Admin\ProductsController@show');
    $router->patch('admin/products/{id}', 'Admin\ProductsController@update');
    $router->delete('admin/products/{id}', 'Admin\ProductsController@destroy');

    $router->get('carts', 'CartsController@index');
    $router->get('carts/count', 'CartsController@count');
    $router->get('carts/total', 'CartsController@total');
    $router->post('carts/{id}', 'CartsController@store');
    $router->delete('carts/{id}', 'CartsController@destroy');
    $router->patch('carts/decrease/{id}', 'CartsController@decrease');
    $router->patch('carts/increase/{id}', 'CartsController@increase');

    $router->post('checkout', 'CheckoutController@store');

    $router->get('payments/{id}', 'PaymentsController@index');
    $router->post('payments', 'PaymentsController@store');

    $router->get('transactions', 'TransactionsController@index');
    $router->get('transactions/{id}', 'TransactionsController@show');
    $router->patch('transactions/{id}', 'TransactionsController@update');

    $router->get('admin/transactions', 'Admin\TransactionsController@index');
    $router->get('admin/transactions/{id}', 'Admin\TransactionsController@show');

    $router->post('admin/shippings', 'Admin\ShippingsController@store');

    $router->get('admin/dashboard', 'Admin\DashboardController@index');
});

