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

Route::middleware(['auth', 'json-only'])
    ->prefix('app')
    ->as('app.')
    ->group(function() {
        Route::get('user', function(\Illuminate\Http\Request $request) {
            return $request->user();
        });

        Route::get('todo', 'ToDoController@index')->name('todo-fetch');
        Route::post('todo', 'ToDoController@store')->name('todo-create');
        Route::patch('todo/{todo}/title', 'ToDoController@updateTitle')->name('todo-update-title');
        Route::patch('todo/{todo}/status', 'ToDoController@updateStatus')->name('todo-update-status');
        Route::delete('todo/{todo}', 'ToDoController@destroySingle')->name('todo-destroy-single');
        Route::delete('todo', 'ToDoController@destroyComplete')->name('todo-destroy-complete');
    });

Route::middleware('auth')->get('/{any}', 'SpaController')->where('any', '.*');
