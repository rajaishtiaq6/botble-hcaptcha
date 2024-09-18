<?php

namespace Shaqi\HCaptcha\Forms\Fields;

use Botble\Base\Forms\FormField;
use Shaqi\HCaptcha\Facades\HCaptcha;

class HCaptchaField extends FormField
{
    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true): string
    {
        return parent::render($options, $showLabel, $showField, $showError)
            . view('plugins/hcaptcha::script', ['url' => HCaptcha::getJsLink()])->render();
    }

    protected function getTemplate(): string
    {
        return 'plugins/hcaptcha::forms.fields.hcaptcha';
    }
}
