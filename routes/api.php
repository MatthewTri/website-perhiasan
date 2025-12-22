<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WebhookController;


Route::post('/webhook/xendit', [WebhookController::class, 'handleXendit']);

Route::get('/reviews', [ReviewController::class, 'index']);
