<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Accession\SchemaController;
use App\Http\Controllers\Api\V1\Accession\FileController;
use App\Http\Controllers\Api\V1\Accession\RegistrationSchemaController;

Route::group([
    'prefix' => '/accession',
    'as' => 'accession.'
], function () {
    // schemas
    Route::group([
        'prefix' => 'schemas',
        'as' => 'schemas.'
    ], function () {
        Route::get('/', [ SchemaController::class, 'index' ])->name('schemas.index');
        Route::get('/{id}', [ SchemaController::class, 'show' ])->name('schemas.show');
    });
    Route::post('/registration-schemas', [ RegistrationSchemaController::class, 'index' ])->name('schemas.registration');
    // files
    Route::apiResource('/files', FileController::class);
});


// $router->group(['prefix' => '/schema-lists'], function() use ($router) {
//     $router->get('/', ['as' => 'schemas.index', 'uses' => 'SchemaController@index']);
// });

// $router->group(['prefix' => '/schemas'], function() use ($router) {
//     $router->get('/{id}', ['as' => 'schemas.show', 'uses' => 'SchemaController@show']);
// });

// $router->group(['prefix' => '/files'], function() use ($router) {
//     $router->get('/', ['as' => 'files.index', 'uses' => 'FileController@index']);
//     $router->post('/', ['as' => 'files.store', 'uses' => 'FileController@store']);
//     $router->get('/{id}', ['as' => 'files.download', 'uses' => 'FileController@download']);
//     $router->delete('/{id}', ['as' => 'files.destroy', 'uses' => 'FileController@destroy']);
// });

// $router->post('/registration-schemas', ['as' => 'registration.schema', 'uses' => 'RegistrationSchemaController@index']);