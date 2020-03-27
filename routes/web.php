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

Route::get('/', 'PagesController@welcome');

Route::get('/about','PagesController@about');
Route::get('/quote','QuotesController@quote')->middleware('auth');
Route::get('/restart','QuotesController@restart')->middleware('auth');
Route::post('/quote/{id1}/{id2}','QuotesController@update')->middleware('auth');


Route::resource('skills','SkillsController')->middleware('auth')->except('index');
Route::get('/skills','SkillsController@index');
Route::delete('/skills2','SkillsController@destroy2')->middleware('auth');

Route::post('/myposts/{id}','SkillsController@showMyPosts')->middleware('auth');
Route::post('/searchSkills','SkillsController@searchSkills');

//Route::resource('posts','PostsController')->middleware('auth');
Route::get('/posts','PostsController@index')->middleware('auth')->name('posts.index');
Route::post('/posts','PostsController@store')->middleware('auth')->name('posts.store');
Route::get('/posts/create/{skill}','PostsController@create')->middleware('auth')->name('posts.create');
Route::get('/posts/{post}','PostsController@show')->middleware('auth')->name('posts.show');
Route::delete('/posts/{post}','PostsController@destroy')->middleware('auth')->name('posts.destroy');
Route::match(['put', 'patch'],'/posts/{post}','PostsController@update')->middleware('auth')->name('posts.update');
Route::get('/posts/{post}/edit','PostsController@edit')->middleware('auth')->name('posts.edit');

Route::post('/hide/{id}','PostsController@hide')->middleware('auth');
Route::post('/unhide/{id}','PostsController@unhide')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/rec', 'RecommendationsController@rec')->middleware('auth');
Route::post('/newRec/{type}','RecommendationsController@new_type')->middleware('auth');
Route::post('/expireRec/{id}','RecommendationsController@expire')->middleware('auth');
Route::post('/addskill/{id}','RecommendationsController@addskill')->middleware('auth');

Route::match(['put', 'patch'],'/majors/{major}','MajorsController@update')->middleware('auth')->name('majors.update');
Route::get('/majors/{major}/edit','MajorsController@edit')->middleware('auth')->name('majors.edit');
