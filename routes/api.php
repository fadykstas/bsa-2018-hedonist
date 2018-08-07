<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::group(['prefix' => '/auth', 'namespace' => 'Api'], function() {

        Route::post('/signup','AuthController@register');

        Route::post('/login','AuthController@login');

        Route::post('/logout','AuthController@logout');

        Route::post('/reset','AuthController@resetPassword');

        Route::post('/recover','AuthCOntroller@recoverPassword');

        Route::group(['middleware' => 'jwt.auth'], function() {

            Route::post('/refresh', 'AuthController@refresh');

            Route::get('/me', 'AuthController@me');
        });
    });

    Route::group(['middleware' => 'jwt.auth'], function() {
        Route::post('/places/{id}/like', 'Api\LikeController@likePlace')->name('place.like');
        Route::post('/places/{id}/dislike', 'Api\DislikeController@dislikePlace')->name('place.dislike');

        Route::prefix('tastes')->group(function () {
            Route::get('/', 'Api\UserTaste\TasteController@getTastes')
                ->name('tastes.getTastes');
            Route::get('/my', 'Api\User\UserTasteController@getTastes')
                ->name('user.tastes.getTastes');
            Route::post('/my', 'Api\User\UserTasteController@addTaste')
                ->name('user.tastes.addTaste');
            Route::delete('my/{id}', 'Api\User\UserTasteController@deleteTaste')
                ->name('user.tastes.deleteTaste');
        });
        
        Route::get('/places/features/', 'Api\Places\PlaceFeaturesController@indexPlaceFeature')
            ->name('place.features.indexFeature');

        Route::post('/places/features', 'Api\Places\PlaceFeaturesController@storePlaceFeature')
            ->name('place.features.storeFeature');

        Route::get('/places/features/{id}', 'Api\Places\PlaceFeaturesController@showPlaceFeature')
            ->name('place.features.showFeature');

        Route::delete('/places/features/{id}', 'Api\Places\PlaceFeaturesController@destroyPlaceFeature')
            ->name('place.features.deleteFeature');
  
    });
});