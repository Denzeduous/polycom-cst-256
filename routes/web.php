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

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
	return view('login');
});

Route::get('/register', function () {
	return view('register');
});

Route::post('/logincheck', 'LoginController@LoginCheck');
Route::post('/registercheck', 'LoginController@RegisterCheck');

Route::get('/logout', 'LoginController@Logout');

Route::get('/profile/{username}', 'ProfileController@GetProfile');
Route::post('/profile/{username}/delete', 'AdminController@DeleteProfile');
Route::post('/profile/{username}/suspend', 'AdminController@SuspendProfile');

Route::post('/editprofile', 'ProfileController@EditProfile');
Route::post('/addexperience', 'ProfileController@AddExperience');
Route::post('/profile/{username}/editexperience/{id}', 'ProfileController@EditExperience');
Route::get('/profile/{username}/deleteexperience/{id}', 'ProfileController@DeleteExperience');

Route::get('/group/{group}', 'GroupController@GetGroup');
Route::get('/creategroup', function () { return view('creategroup'); });
Route::post('/creategroup/create', 'GroupController@CreateGroup');
Route::get('/group/delete/{group}', 'GroupController@DeleteGroup');
Route::get('/group/join/{group}', 'GroupController@JoinGroup');
Route::get('/group/leave/{group}', 'GroupController@LeaveGroup');
Route::get('/group/{group}/members', 'GroupController@GroupMembers');

Route::get('/email-verify/{id}', 'MailController@VerifyEmail');

Route::get('/search/user', 'SearchController@SearchUsers');
Route::get('/search/group', 'SearchController@SearchGroups');
Route::get('/search/job', 'SearchController@SearchJobs');

Route::get('/job/{id}', 'ProfileController@GetJob');

Route::get('/rest/user/{username}', 'RestController@UserFromUsername');
Route::get('/rest/jobs/{query}', 'RestController@GetJobs');
Route::get('/rest/job/{id}', 'RestController@GetJob');