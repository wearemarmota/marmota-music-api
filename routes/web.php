<?php

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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

# Authenticated routes:
$router->group(['middleware' => 'auth'], function () use($router) {
    # Artists routes:
    $router->get('/artists', 'ArtistController@index');
    $router->post('/artists', 'ArtistController@store');
    $router->get('/artists/{artist}', 'ArtistController@show');
    $router->put('/artists/{artist}', 'ArtistController@update');
    $router->patch('/artists/{artist}', 'ArtistController@update');
    $router->delete('/artists/{artist}', 'ArtistController@destroy');
    # Albums routes:
    $router->get('/albums', 'AlbumController@index');
    $router->post('/albums', 'AlbumController@store');
    $router->get('/albums/{album}', 'AlbumController@show');
    $router->put('/albums/{album}', 'AlbumController@update');
    $router->patch('/albums/{album}', 'AlbumController@update');
    $router->delete('/albums/{album}', 'AlbumController@destroy');
    $router->get('/artists/{artist}/albums', 'AlbumController@indexByArtist');
    # Songs routes:
    $router->get('/songs', 'SongController@index');
    $router->post('/songs', 'SongController@store');
    $router->get('/songs/{song}', 'SongController@show');
    $router->put('/songs/{song}', 'SongController@update');
    $router->patch('/songs/{song}', 'SongController@update');
    $router->delete('/songs/{song}', 'SongController@destroy');
    $router->get('/albums/{album}/songs', 'SongController@indexByAlbum');
    # Users routes:
    $router->get('/me', 'UserController@profile');
    $router->get('/users/{id}', 'UserController@singleUser');
    $router->get('/users', 'UserController@allUsers');
    # Common routes:
    $router->get('/search', 'SearchController@index');
    # Favorites routes:
    $router->get('/favorites', 'FavoriteController@index');
    $router->post('/favorites', 'FavoriteController@store');
    $router->delete('/favorites/{song_id}', 'FavoriteController@destroy');
});

# Routes for non-authenticated users:
$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');
