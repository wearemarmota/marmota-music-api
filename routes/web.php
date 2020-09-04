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

$router->get('/authors', 'AuthorController@index');
$router->post('/authors', 'AuthorController@store');
$router->get('/authors/{author}', 'AuthorController@show');
$router->put('/authors/{author}', 'AuthorController@update');
$router->patch('/authors/{author}', 'AuthorController@update');
$router->delete('/authors/{author}', 'AuthorController@destroy');

$router->get('/albums', 'AlbumController@index');
$router->post('/albums', 'AlbumController@store');
$router->get('/albums/{album}', 'AlbumController@show');
$router->put('/albums/{album}', 'AlbumController@update');
$router->patch('/albums/{album}', 'AlbumController@update');
$router->delete('/albums/{album}', 'AlbumController@destroy');
$router->get('/authors/{author}/albums', 'AlbumController@indexByAuthor');

$router->get('/songs', 'SongController@index');
$router->post('/songs', 'SongController@store');
$router->get('/songs/{song}', 'SongController@show');
$router->put('/songs/{song}', 'SongController@update');
$router->patch('/songs/{song}', 'SongController@update');
$router->delete('/songs/{song}', 'SongController@destroy');
$router->get('/albums/{album}/songs', 'SongController@indexByAlbum');
