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

// $router->group(['middleware' => ['auth:api', 'role_base_provider']], function ($router) {
//     $router->get('foo', [function () {
//         return 'Hello World';
//     }, 'as' => 'hello']);
// });

$router->get('/get-image/{filename}', ['uses' => 'PostController@getImage', 'as' => 'post.get_image']);

$router->group(['prefix' => 'auth'], function ($router) {
    $router->post('signup', 'AuthController@signup');
    $router->post('login', 'AuthController@login');
});

$router->group(['prefix' => 'admin', 'middleware' => ['auth:api', 'role_base_provider']], function ($router) {
    $router->post('logout', ['uses' => 'AuthController@logout', 'as' => 'user.logout']);

    $router->get('user', [function () {
        return auth()->user();
    }, 'as' => 'user.info']);

    // Admin Dasboard
    $router->get('dashboard', ['uses' => 'DashboardController@index', 'as' => 'admin.dashboard']);

    // User routes
    $router->group(['prefix' => 'users'], function ($router) {
        $router->get('/', ['uses' => 'UserController@index', 'as' => 'user.list']);
        $router->post('/', ['uses' => 'UserController@store', 'as' => 'user.store']);
        $router->get('/show/{id}', ['uses' => 'UserController@show', 'as' => 'user.show']);
        $router->post('/update/{id}', ['uses' => 'UserController@update', 'as' => 'user.update']);
        $router->delete('/destroy/{id}', ['uses' => 'UserController@destroy', 'as' => 'user.delete']);
    });
    
    // Category routes
    $router->group(['prefix' => 'category'], function ($router) {
        $router->get('/', ['uses' => 'CategoryController@index', 'as' => 'category.list']);
        $router->get('/parent', ['uses' => 'CategoryController@parentShow', 'as' => 'category.parent']);
        $router->post('/', ['uses' => 'CategoryController@store', 'as' => 'category.store']);
        $router->get('/show/{id}', ['uses' => 'CategoryController@show', 'as' => 'category.show']);
        $router->put('/update/{id}', ['uses' => 'CategoryController@update', 'as' => 'category.update']);
        $router->delete('/destroy/{id}', ['uses' => 'CategoryController@destroy', 'as' => 'category.delete']);
    });

    // Post routes
    $router->group(['prefix' => 'post'], function ($router) {
        $router->get('/', ['uses' => 'PostController@index', 'as' => 'post.list']);
        $router->get('/category', ['uses' => 'PostController@getCategory', 'as' => 'post.get_category']);
        $router->post('/', ['uses' => 'PostController@store', 'as' => 'post.store']);
        $router->get('/show/{id}', ['uses' => 'PostController@show', 'as' => 'post.show']);
        $router->post('/update/{id}', ['uses' => 'PostController@update', 'as' => 'post.update']);
        $router->delete('/destroy/{id}', ['uses' => 'PostController@destroy', 'as' => 'post.delete']);
    });

    // Public Post routes
    $router->group(['prefix' => 'public-post'], function ($router) {
        $router->get('/', ['uses' => 'PublicPostController@index', 'as' => 'public-post.list']);
        $router->post('update/{id}', ['uses' => 'PublicPostController@handlePublic', 'as' => 'public-post.handle']);
    });

    // Contact routes
    $router->group(['prefix' => 'contact'], function ($router) {
        $router->get('/', ['uses' => 'ContactController@index', 'as' => 'contact.list']);
        $router->get('/reply/{id}', ['uses' => 'ContactController@reply', 'as' => 'contact.reply']);
        $router->post('/reply/{id}', ['uses' => 'ContactController@sendReply', 'as' => 'contact.send_reply']);
    });
    
});

$router->group(['prefix' => 'user'], function($router) {
    $router->get('/post', ['uses' => 'User\PostController@index', 'as' => 'user.post.list']);
    $router->get('/post/show/{slug}', ['uses' => 'User\PostController@show', 'as' => 'user.post.show']);
    $router->post('/contact', ['uses' => 'ContactController@contract', 'as' => 'user.contract']);
    $router->post('/comment', ['uses' => 'User\CommentController@sendComment', 'as' => 'user.comment']);
});