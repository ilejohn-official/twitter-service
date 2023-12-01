<?php

use Illuminate\Support\Facades\Route;
use App\Api\V1\Controllers\AuthController;
use App\Api\V1\Controllers\MessageController;
use App\Api\V1\Controllers\WebhookController;
use App\Api\V1\Controllers\WelcomeController;
use App\Api\V1\Controllers\UserController;

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

Route::post('/login', [AuthController::class, 'login']);
//This route should be set in twitter developer dashboard as the Callback URI / Redirect URL
Route::get('/login-callback', [AuthController::class, 'callback'])->name('login.callback');

Route::post('/webhook/messenger', [WebhookController::class, 'handleResponse']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/me', [UserController::class, 'getUser']);
    Route::post('/tweet', [UserController::class, 'tweet']);
    Route::post('/chatbot/subscribe', [UserController::class, 'subscribeToChatBot']);
    Route::post('/chat/subscribe', [UserController::class, 'subscribeToChat']);
});

Route::middleware(['user-auth'])->group(function () {
    Route::get('/bot-user', [UserController::class, 'getBotUser']);
    Route::post('/messages/send', [MessageController::class, 'send']);
});
