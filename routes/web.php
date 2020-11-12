<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FrontEndController;
use App\Product;
use App\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

$parentDomain = env("APP_BASE_URL", 'localhost:8100');

Route::get('uploads/{url}', 'UploadController@show')->where('url', '(.*)')->name('url.path');

Route::group(['domain' => $parentDomain], function () {
    Route::get('/update-products', function () {
        foreach (Store::query()->where('total_products', 0)->get() as $store) {
            $store->update(['total_products' => Product::query()->where("store", $store->slug)->count()]);
        }
    });
    Route::get('/', [FrontEndController::class, 'showHome']);
    Route::get('faqs', [FaqController::class, 'index']);
    Route::get('terms', [FrontEndController::class, 'terms']);
    Route::get('privacy', [FrontEndController::class, 'privacy'])->name('privacy');
    Route::get('contact-us', [ContactUsController::class, 'contactUs'])->name('contact');
    Route::post('contact-us', [ContactUsController::class, 'contact'])->name('contact');
    Auth::routes(['verify' => true]);
    Route::get('password/phone/reset', [ResetPasswordController::class, 'showResetByCode'])->name('password.reset.code');
    Route::post('password/phone/reset', [ResetPasswordController::class, 'resetByCode'])->name('password.update.code');

    Route::view('/activate/token', 'auth.activate-token')->name('activate.token');
    Route::post('/activate/token', [VerificationController::class, 'activateByCode'])->name('activate.token');
    Route::get('/activate/token/{phone_number}', [VerificationController::class, 'resendCode'])->name('resend.activation_token');
});

Route::group(['domain' => "{subdomain}.$parentDomain"], function () use ($parentDomain) {
    Route::get('/{url}', [FrontEndController::class, 'index'])->where('url', '(.*)');
});

