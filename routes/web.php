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

/*
 * public user routes
 */
Route::get('/', function () {
    return view('welcome');
});
// Register, Authentication  & Password Reset User Routes...
Auth::routes();

/*
 * Protected user routes only for logged in users
 */
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')
        ->name('home');
});

/*
 * admin routes
 */
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function () {

    /*
     * public admin routes
     */

    // Authentication Admin Routes...
    $this->get('login', 'Auth\LoginController@showLoginForm')
        ->name('login');
    $this->post('login', 'Auth\LoginController@login');
    $this->post('logout', 'Auth\LoginController@logout')
        ->name('logout');

    // Password Reset Admin Routes...
    $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')
        ->name('password.request');
    $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
        ->name('password.email');
    $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')
        ->name('password.reset');
    $this->post('password/reset', 'Auth\ResetPasswordController@reset');

    /*
     * protected admin routes
     */
    Route::group(['middleware' => ['auth:admin']], function () {
        Route::resource('users', 'UsersController');
        Route::resource('admins', 'AdminsController');
        Route::get('/home', 'HomeController@index')
            ->name('home');
    });
});

