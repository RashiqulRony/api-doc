<?php

/**
 * Routes for the api documentation.
 */

//This is the route for the documentation info page.
Route::view('info', 'apidoc::partials.info')->name('info');

//This is the route for the root documentation view page.
Route::view('', 'apidoc::documentation')->name('root');
