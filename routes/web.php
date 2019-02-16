<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

Route::middleware(['auth', 'verified'])
    ->prefix('app')
    ->as('app.')
    ->group(function() {
        Route::get('user', function(\Illuminate\Http\Request $request) {
            return $request->user();
        });
    });

Route::middleware('verified')->get('/{any}', 'SpaController')->where('any', '.*');
