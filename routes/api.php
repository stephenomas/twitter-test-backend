<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwitterController;


///twitter test
Route::get('/authenticate', [TwitterController::class, 'authenticate']);

Route::get('/message', [TwitterController::class, 'send_message']);
