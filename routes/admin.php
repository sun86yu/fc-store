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
    Route::match(['delete'], 'productdel/{id}', 'Admin\ProductController@destroyProduct')->where('id', '[0-9]+');

    Route::match(['get', 'post'], 'productadd', 'Admin\ProductController@create')->name('admin_product_add');
    Route::match(['get', 'post'], 'productedit/{id}', 'Admin\ProductController@create')->where('id', '[0-9]+');
    Route::match(['get', 'post'], 'productupload', 'Admin\ProductController@upload');
    Route::get('catforminfo/{id}', 'Admin\CategoryController@showCatForm')->where('id', '[0-9]+');

    Route::get('orders', 'Admin\OrderController@index')->name('admin_order_list');

    Route::match(['get', 'post'], 'cats', 'Admin\CategoryController@index')->name('admin_category_list');
    Route::get('catmodule', 'Admin\CategoryController@module')->name('admin_category_module');
    Route::get('catconst', 'Admin\CategoryController@consts')->name('admin_category_const');

    Route::get('catinfo/{id}', 'Admin\CategoryController@showCat')->where('id', '[0-9]+');
    Route::get('moduleinfo/{id}', 'Admin\CategoryController@showModule')->where('id', '[0-9]+');
    Route::get('catsoninfo/{id}', 'Admin\CategoryController@showCatSon')->where('id', '[0-9]+');
    Route::get('catmoduleinfo/{id}', 'Admin\CategoryController@showCatModule')->where('id', '[0-9]+');
    Route::get('constinfo/{id}', 'Admin\CategoryController@showCatConst')->where('id', '[0-9]+');

    Route::match(['post'], 'catedit/{id}', 'Admin\CategoryController@editCat')->where('id', '[0-9]+');
    Route::match(['post'], 'moduleedit/{id}', 'Admin\CategoryController@editModule')->where('id', '[0-9]+');
    Route::match(['post'], 'constedit/{id}', 'Admin\CategoryController@editConst')->where('id', '[0-9]+');

    Route::match(['delete'], 'catdel/{id}', 'Admin\CategoryController@destroyCat')->where('id', '[0-9]+');
    Route::match(['delete'], 'moduledel/{id}', 'Admin\CategoryController@destroyModule')->where('id', '[0-9]+');
    Route::match(['delete'], 'constdel/{id}', 'Admin\CategoryController@destroyConst')->where('id', '[0-9]+');

    Route::match(['get', 'post'], 'geos', 'Admin\GeoController@index')->name('admin_geo_list');
    Route::get('geoinfo/{id}', 'Admin\GeoController@show')->where('id', '[0-9]+');
    Route::match(['delete'], 'geodel/{id}', 'Admin\GeoController@destroy')->where('id', '[0-9]+');
    Route::match(['post'], 'geoedit/{id}', 'Admin\GeoController@edit')->where('id', '[0-9]+');

    Route::match(['get', 'post'], 'articles', 'Admin\ArticleController@index')->name('admin_article_list');
    Route::match(['get', 'post'], 'articleadd', 'Admin\ArticleController@create')->name('admin_article_add');
    Route::match(['get', 'post'], 'articleedit/{id}', 'Admin\ArticleController@edit')->where('id', '[0-9]+');
    Route::match(['delete'], 'articledel/{id}', 'Admin\ArticleController@destroy')->where('id', '[0-9]+');
    Route::match(['get', 'post'], 'articleupload', 'Admin\ArticleController@upload');


    Route::match(['get', 'post'], 'logs', 'Admin\LogController@index')->name('admin_logs_list');
});