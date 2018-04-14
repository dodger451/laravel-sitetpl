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
 * Route::resource admins creates:
 *
/*
|        | GET|HEAD  | admin/admins                  | admin.admins.index     | Sitetpl\Http\Controllers\Admin\AdminsController@index                            | web,auth:admin |
|        | POST      | admin/admins                  | admin.admins.store     | Sitetpl\Http\Controllers\Admin\AdminsController@store                            | web,auth:admin |
|        | GET|HEAD  | admin/admins/create           | admin.admins.create    | Sitetpl\Http\Controllers\Admin\AdminsController@create                           | web,auth:admin |
|        | PUT|PATCH | admin/admins/{admin}          | admin.admins.update    | Sitetpl\Http\Controllers\Admin\AdminsController@update                           | web,auth:admin |
|        | GET|HEAD  | admin/admins/{admin}          | admin.admins.show      | Sitetpl\Http\Controllers\Admin\AdminsController@show                             | web,auth:admin |
|        | DELETE    | admin/admins/{admin}          | admin.admins.destroy   | Sitetpl\Http\Controllers\Admin\AdminsController@destroy                          | web,auth:admin |
|        | GET|HEAD  | admin/admins/{admin}/edit     | admin.admins.edit
*/
Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function () {

    Route::group(['middleware' => ['auth:admin']], function () {
        // Protected Routes
        Route::resource('users', 'UsersController');
        Route::resource('admins', 'AdminsController');
        Route::get('/home', 'HomeController@index')->name('home');
    });
    
    // Authentication Admin Routes...
    $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
    $this->post('login', 'Auth\LoginController@login');
    $this->post('logout', 'Auth\LoginController@logout')->name('logout');

    // Password Reset Admin Routes...
    $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    $this->post('password/reset', 'Auth\ResetPasswordController@reset');

});
// Register, Authentication  & Password Reset User Routes... 
Auth::routes();
//User Routes set own guards
Route::get('/home', 'HomeController@index')->name('home');
