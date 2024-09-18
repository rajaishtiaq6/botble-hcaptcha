<?php

namespace Shaqi\HCaptcha\Http\Controllers\Settings;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Setting\Http\Controllers\SettingController;
use Shaqi\HCaptcha\Forms\Settings\HCaptchaSettingForm;
use Shaqi\HCaptcha\Http\Requests\Settings\HCaptchaSettingRequest;

class HCaptchaSettingController extends SettingController
{
    public function edit(): string
    {
        return HCaptchaSettingForm::create()->renderForm();
    }

    public function update(HCaptchaSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
