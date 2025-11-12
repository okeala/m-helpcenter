<?php

use Illuminate\Support\Facades\Route;
use Modules\Helpcenter\Http\Controllers\HelpcenterController;
use Modules\Helpcenter\Http\Controllers\TutorialController;

Route::middleware(['web'])->group(function () {
    Route::resource('tags', \Modules\Helpcenter\Http\Controllers\TagController::class)->names('tags');
});

Route::middleware(['auth', 'verified'])
    ->prefix('help')
    ->name('help-center.')
    ->group(function () {
    Route::get('/contact-us', [HelpcenterController::class, 'contactUs'])->name('contact-us');
//    Route::resource('faq-categories', FaqCategoryController::class)->names('faq-categories');
//    Route::resource('faqs', FaqController::class)->names('faqs');
    Route::resource('tutorials', TutorialController::class)->names('tutorials');
});
