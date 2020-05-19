<?php

use Illuminate\Support\Facades\Route;

Route::get('/projects', [
	'as' => 'projects.index', 
	'uses' => 'ProjectController@index'
]);

Route::get('/{project_id?}', [
	'as' => 'tasks.dashboard', 
	'uses' => 'TaskController@index'
]);

Route::resource('tasks', 'TaskController');

