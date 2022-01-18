<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function (){
	Route::get('/dashboard', function () {
	    return view('dashboard');
	})->name('dashboard');

	Route::get('/contacts', 'App\Http\Controllers\ContactController@index')->name('contacts');

	Route::get('/click-event', 'App\Http\Controllers\ContactController@clickEvent')->name('click-event');

	Route::post('/bulk-upload', 'App\Http\Controllers\ContactController@uploadSampleFile')->name('bulk-upload');

	Route::post('/create-contact', 'App\Http\Controllers\ContactController@create')->name('create-contact');


});



require __DIR__.'/auth.php';
