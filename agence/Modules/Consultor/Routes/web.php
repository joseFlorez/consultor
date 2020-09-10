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

Route::prefix('consultor')->group(function() {
    Route::get('list', 'ConsultorController@listConsultor');
    Route::get('relatorio', 'ConsultorController@relatorio');
    Route::get('/', function(){
        return view('consultor::index');
    });
});
