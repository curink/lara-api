<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //return view('welcome');
    return response()->json([
        'Laravel VERSION' => Illuminate\Foundation\Application::VERSION,
        'PHP VERSION' => PHP_VERSION,
        'time' => date_format(now(), 'd-m-Y H:i:s')
    ]);
});
