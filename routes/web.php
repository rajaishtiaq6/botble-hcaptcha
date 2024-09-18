<?php

use Botble\Base\Facades\AdminHelper;
use Shaqi\HCaptcha\Http\Controllers\Settings\HCaptchaSettingController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function () {
    Route::group(['permission' => 'hcaptcha.settings'], function () {
        Route::get('settings/hcaptcha', [HCaptchaSettingController::class, 'edit'])
            ->name('hcaptcha.settings');

        Route::put('settings/hcaptcha', [HCaptchaSettingController::class, 'update'])
            ->name('hcaptcha.settings.update');
    });
});
