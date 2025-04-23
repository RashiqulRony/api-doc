<?php

/** @var Laravel\Lumen\Routing\Router $router */

//This is the route for the documentation info page.
$router->get('info', [function () { return view('apidoc::partials.info'); }, 'as' => 'info']);

//This is the route for the root documentation view page.
$router->get('', [function () { return view('apidoc::documentation'); }, 'as' => 'root']);
