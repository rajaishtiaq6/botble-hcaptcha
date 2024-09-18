<?php

namespace Shaqi\HCaptcha\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;
use Shaqi\HCaptcha\Facades\HCaptcha;

class HCaptchaSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            HCaptcha::getSettingKey('enabled') => [new OnOffRule()],
            HCaptcha::getSettingKey('site_key') => ['nullable', 'string'],
            HCaptcha::getSettingKey('secret_key') => ['nullable', 'string'],
            ...$this->getFormRules(),
        ];
    }

    protected function getFormRules(): array
    {
        $rules = [];

        foreach (array_keys(HCaptcha::getForms()) as $form) {
            $rules[HCaptcha::getFormSettingKey($form)] = [new OnOffRule()];
        }

        return $rules;
    }
}
