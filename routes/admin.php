<?php
/**
 * Created by my-laravel.
 * Func: admin.php
 * User: sunyu
 * Date: 2018/4/24
 * Time: 下午8:18
 */
// 引入其它的路由配置文件
// require app_path('Http/Controllers/Auth/routes.php');
Route::get('/admindash/', 'Admin\HomeController@index')->name('admin_index');

Route::prefix('admin')->group(function () {
    Route::get('login', 'Admin\LoginController@index')->name('admin_login_index');

    Route::get('users', 'Admin\UserController@index')->name('admin_user_list');
    Route::get('identy', 'Admin\UserController@identy')->name('admin_user_identy');

    Route::get('user_edit/{id}', 'Admin\UserController@edit')->where('id', '[0-9]+');

    Route::match(['get', 'post'], 'managers', 'Admin\AdminController@index')->name('admin_user_list');

    Route::match(['get', 'post', 'delete'], 'admin/{id}', 'Admin\AdminController@adminfunc')->where('id', '[0-9]+');

    Route::get('roles', 'Admin\AdminController@role')->name('admin_role_list');
    Route::match(['get', 'post', 'delete'], 'role/{id}', 'Admin\AdminController@rolefunc')->where('id', '[0-9]+');

    Route::get('products', 'Admin\ProductController@index')->name('admin_product_list');
    Route::get('orders', 'Admin\OrderController@index')->name('admin_order_list');

    Route::get('cats', 'Admin\CategoryController@index')->name('admin_category_list');
    Route::get('catmodule', 'Admin\CategoryController@module')->name('admin_category_module');
    Route::get('catconst', 'Admin\CategoryController@consts')->name('admin_category_const');

    Route::get('geos', 'Admin\GeoController@index')->name('admin_geo_lsit');

    Route::get('articles', 'Admin\ArticleController@index')->name('admin_geo_lsit');
    Route::get('articleadd', 'Admin\ArticleController@create')->name('admin_geo_lsit');

    Route::get('logs', 'Admin\LogController@index')->name('admin_geo_lsit');
});