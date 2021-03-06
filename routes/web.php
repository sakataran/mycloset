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

Route::get('/', 'CordinatesController@index')->name('index');
Route::get('/feed', 'CordinatesController@feed')->name('feed');
Route::get('/search', 'CordinatesController@search')->name('search');
Route::get('/search/cordinates', 'CordinatesController@cordinatesearchshow')->name('cordinatesearchshow');

// ユーザ登録
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

// ログイン認証
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

// ゲストユーザーログイン
Route::get('guest', 'Auth\LoginController@guestLogin')->name('login.guest');

// Facebookログイン
Route::get('/login/{provider}', 'Auth\LoginController@redirectToProvider')->where('social', 'facebook');
Route::get('/login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->where('social', 'facebook');

// ログイン時の処理
Route::group(['middleware' => ['verified']], function () {
    // ユーザ機能
    Route::resource('users', 'UsersController', ['only' => ['show', 'edit', 'update', 'destroy']]);
    // 投稿機能
    Route::resource('cordinates', 'CordinatesController', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update','destroy']]);
    // コメント機能
    Route::post('cordinates/{id}', 'CommentsController@store')->name('comments.store');
    Route::delete('cordinates/{id}/comment', 'CommentsController@destroy')->name('comments.destroy');
    // アイテム機能
    Route::resource('cordinates/{id}/items', 'ItemsController', ['only' => ['create','store', 'destroy']]);
    Route::post('/fetch/category', 'ItemsController@fetch')->name('items.fetch');
    
    // フォロー機能、クリップ一覧表示の処理
    Route::group(['prefix' => 'users/{user_id}'], function () {
        Route::post('follow', 'UserFollowController@store')->name('user.follow');
        Route::delete('unfollow', 'UserFollowController@destroy')->name('user.unfollow');
        Route::get('followings', 'UsersController@followings')->name('users.followings');
        Route::get('followers', 'UsersController@followers')->name('users.followers');
        Route::get('favorites', 'UsersController@favorites')->name('users.favorites');
    });
    // クリップ機能、いいね機能の処理
    Route::group(['prefix' => 'cordinates/{id}'], function () {
        Route::post('favorite', 'FavoritesController@store')->name('user.favorite');
        Route::delete('unfavorite', 'FavoritesController@destroy')->name('user.unfavorite');
        Route::post('onnice', 'NiceController@store')->name('user.onnice');
        Route::delete('unnice', 'NiceController@destroy')->name('user.unnice');
    });
});

// 管理者機能
Route::get('/admin/login', 'admin\AdminLoginController@showLoginform');
Route::post('/admin/login', 'admin\AdminLoginController@login');
Route::group(['middleware' => ['auth.admin']], function () {
	Route::get('/admin', 'admin\AdminTopController@show');
	Route::post('/admin/logout', 'admin\AdminLogoutController@logout');
	Route::get('/admin/brand_list', 'admin\ManageUserController@showBrandList');
	Route::get('/admin/user_list', 'admin\ManageUserController@showUserList');
	Route::get('/admin/user/{id}', 'admin\ManageUserController@showUserDetail');
	Route::get('/admin/cordinate_list', 'admin\ManageUserController@showCordinateList');
	Route::get('/admin/cordinate/{id}', 'admin\ManageUserController@showCordinateDetail');
	Route::delete('/admin/cordinate/{id}', 'admin\ManageUserController@cordinateDestroy')->name('admin.cordinateDestroy');
	Route::resource('admin', 'admin\ManageUserController')->only([ 'edit', 'update', 'destroy']); // user用
});

// 制限なしの処理
Route::get('/home', 'HomeController@index')->name('home');