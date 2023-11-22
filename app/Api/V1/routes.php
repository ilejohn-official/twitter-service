<?php

use Illuminate\Support\Facades\Route;
use App\Api\V1\Controllers\MessageController;
use App\Api\V1\Controllers\WelcomeController;
use App\Api\V1\Controllers\UserController;
use App\Api\V1\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['middleware' => 'user-auth'], function () {
    Route::post('/chatbot/subscribe', [UserController::class, 'subscribeToChatBot']);
    Route::post('/chat/subscribe', [UserController::class, 'subscribeToChat']);

    Route::post('/messages/send', [MessageController::class, 'send']);
});

Route::post('/webhook/messenger', [WebhookController::class, 'handleResponse']);
