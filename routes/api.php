<?php

use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('lead-added', [MainController::class, 'leadAdded']);
Route::post('lead-changed', [MainController::class, 'leadChanged']);
Route::post('contact-added', [MainController::class, 'contactAdded']);
Route::post('contact-changed', [MainController::class, 'contactChanged']);
