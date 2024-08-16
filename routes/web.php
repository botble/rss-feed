<?php

use Botble\RssFeed\Http\Controllers\RssFeedController;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

if (defined('THEME_MODULE_SCREEN_NAME')) {
    Theme::registerRoutes(function () {
        Route::group(['controller' => RssFeedController::class], function () {
            Route::get('feed/{name}', 'show')->name('feeds.show');
        });
    });
}
