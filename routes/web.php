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

Route::get('/redirect/{provider}', 'Auth\LoginController@redirect');
Route::get('/callback/{provider}', 'Auth\LoginController@callback');

Auth::routes();

Route::middleware('auth')->group(function () {
	
	Route::post('/forks', 'HomeController@fork')->name('forks');
	Route::get('/home', 'HomeController@index')->name('home');
	Route::get('/repos', 'HomeController@viewRepos')->name('repos');
	Route::get('/get-repos/{name}/{per}', 'HomeController@getRepos')->name('get-repos');
	Route::post('/get-repos/clone', 'HomeController@getReposClone')->name('get-repos-clone');
	Route::get('/list-repos', 'HomeController@getListRepos')->name('get-list-repos');
	Route::post('/get-list-repo-clone', 'HomeController@getListReposClone')->name('get-list-repo-clone');
});

