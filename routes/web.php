<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function(){
    Route::get('/projects', 'ProjectsController@index')->name('projects.index');
    Route::get('/projects/create', 'ProjectsController@create')->name('projects.create');
    Route::get('/projects/{project}', 'ProjectsController@show')->name('projects.show');
    Route::get('/projects/{project}/edit', 'ProjectsController@edit')->name('projects.edit');
    Route::patch('/projects/{project}', 'ProjectsController@update')->name('projects.update');
    Route::post('/projects', 'ProjectsController@store')->name('projects.store');
    Route::delete('/projects/{project}', 'ProjectsController@destroy')->name('projects.delete');

    Route::post('/projects/{project}/tasks', 'ProjectsTasksController@store')->name('projects.tasks.store');
    Route::patch('/projects/{project}/tasks/{task}', 'ProjectsTasksController@update')->name('projects.tasks.update');

    Route::post('/projects/{project}/invitations', 'ProjectsInvitationsController@store')->name('projects.invitations.store');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
