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


Route::get('/vernier-caliper','App\Http\Controllers\experimentController@vernierCaliper');

Route::get('/explore', 'App\Http\Controllers\ExploreController@index');
Route::get('/vernierEquipment', function ()
{
	return view('experiment.vernierEquipment');
})->name('vernierEquipment');

Route::get('/login', 'App\Http\Controllers\loginController@index');
Route::get('/vewCourse', 'App\Http\Controllers\ViewCourseController@index');
Route::get('/UserDashboard', 'App\Http\Controllers\ViewUserDashboard@index');


Route::get('/', 'App\Http\Controllers\PagesController@index');

	