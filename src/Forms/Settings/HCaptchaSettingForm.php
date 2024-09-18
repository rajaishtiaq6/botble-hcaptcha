<?php

namespace Shaqi\HCaptcha\Forms\Settings;

use Botble\Base\Forms\FieldOptions\AlertFieldOption;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\LabelFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\AlertField;
use Botble\Base\Forms\Fields\LabelField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Forms\FormCollapse;
use Botble\Setting\Forms\SettingForm;
use Shaqi\HCaptcha\Facades\HCaptcha;
use Shaqi\HCaptcha\Http\Requests\Settings\HCaptchaSettingRequest;

class HCaptchaSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setUrl(route('hcaptcha.settings'))
            ->setSectionTitle(trans('plugins/hcaptcha::hcaptcha.settings.title'))
            ->setSectionDescription(trans('plugins/hcaptcha::hcaptcha.settings.description'))
            ->setValidatorClass(HCaptchaSettingRequest::class)
            ->add(
                HCaptcha::getSettingKey('enabled'),
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/hcaptcha::hcaptcha.settings.enable'))
                    ->value($value = old(HCaptcha::getSettingKey('enabled'), HCaptcha::isEnabled()))
            )
            ->addOpenCollapsible('hcaptcha_enabled', '1', $value)
            ->add(
                'description',
                AlertField::class,
                AlertFieldOption::make()
                    ->content(str_replace(
                        '<a>',
                        '<a href="https://hCaptcha.com/?r=fe654a351e16">',
                        trans('plugins/hcaptcha::hcaptcha.settings.help_text')
                    ))
                    ->toArray()
            )
            ->add(
                HCaptcha::getSettingKey('site_key'),
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/hcaptcha::hcaptcha.settings.site_key'))
                    ->value(HCaptcha::getSetting('site_key'))
                    ->toArray()
            )
            ->add(
                HCaptcha::getSettingKey('secret_key'),
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/hcaptcha::hcaptcha.settings.secret_key'))
                    ->value(HCaptcha::getSetting('secret_key'))
                    ->toArray()
            )
            ->add(
                HCaptcha::getSettingKey('enable_form_label'),
                LabelField::class,
                LabelFieldOption::make()
                    ->label(trans('plugins/hcaptcha::hcaptcha.settings.enable_form'))
                    ->toArray()
            )
            ->addSelectFormFields('enable_hcaptcha')
            ->addCloseCollapsible('hcaptcha_enabled', '1');
    }

    public function addSelectFormFields(string $key): static
    {
        foreach (HCaptcha::getForms() as $form => $title) {
            $this->add(
                HCaptcha::getFormSettingKey($form, $key),
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label($title)
                    ->value(HCaptcha::isEnabledForForm($form))
                    ->toArray()
            );
        }

        return $this;
    }
}
