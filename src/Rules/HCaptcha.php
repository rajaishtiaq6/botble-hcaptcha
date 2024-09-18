<?php

namespace Shaqi\HCaptcha\Rules;

use Closure;
use Shaqi\HCaptcha\Facades\HCaptcha as HCaptchaFacade;
use Illuminate\Contracts\Validation\ValidationRule;

class HCaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail(__('validation.required'));

            return;
        }

        if (HCaptchaFacade::verify($value)['success'] !== true) {
            $fail(trans('plugins/hcaptcha::hcaptcha.validation.hcaptcha'));

            return;
        }
    }
}
