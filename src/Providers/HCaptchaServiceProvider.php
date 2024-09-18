<?php

namespace Shaqi\HCaptcha\Providers;

use Botble\ACL\Forms\Auth\ForgotPasswordForm;
use Botble\ACL\Forms\Auth\LoginForm;
use Botble\ACL\Forms\Auth\ResetPasswordForm;
use Botble\ACL\Http\Requests\ForgotPasswordRequest;
use Botble\ACL\Http\Requests\LoginRequest;
use Botble\ACL\Http\Requests\ResetPasswordRequest;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Botble\Support\Http\Requests\Request;
use Botble\Theme\FormFront;
use Shaqi\HCaptcha\Contracts\HCaptcha as HCaptchaContract;
use Shaqi\HCaptcha\Facades\HCaptcha as HCaptchaFacade;
use Shaqi\HCaptcha\Forms\Fields\HCaptchaField;
use Shaqi\HCaptcha\Rules\HCaptcha as HCaptchaRule;
use Shaqi\HCaptcha\HCaptcha;
use Illuminate\Routing\Events\Routing;
use Illuminate\Support\Facades\Event;

class HCaptchaServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->singleton(HCaptchaContract::class, function () {
            $siteKey = setting('hcaptcha_site_key');
            $secretKey = setting('hcaptcha_secret_key');

            return new HCaptcha($siteKey, $secretKey);
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/hcaptcha')
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes()
            ->registerPanelSection()
            ->loadAndPublishConfigurations('permissions')
            ->registerHCaptcha();
    }

    protected function registerPanelSection(): self
    {
        PanelSectionManager::default()->beforeRendering(function () {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('hcaptcha')
                    ->setTitle(trans('plugins/hcaptcha::hcaptcha.settings.title'))
                    ->withIcon('ti ti-mail-cog')
                    ->withPriority(10)
                    ->withDescription(trans('plugins/hcaptcha::hcaptcha.settings.description'))
                    ->withRoute('hcaptcha.settings')
            );
        });

        return $this;
    }

    protected function registerHCaptcha(): self
    {
        HCaptchaFacade::registerForm(
            LoginForm::class,
            LoginRequest::class,
            trans('plugins/hcaptcha::hcaptcha.forms.admin_login')
        );

        HCaptchaFacade::registerForm(
            ForgotPasswordForm::class,
            ForgotPasswordRequest::class,
            trans('plugins/hcaptcha::hcaptcha.forms.admin_forgot_password')
        );

        HCaptchaFacade::registerForm(
            ResetPasswordForm::class,
            ResetPasswordRequest::class,
            trans('plugins/hcaptcha::hcaptcha.forms.admin_reset_password')
        );

        if (! HCaptchaFacade::isEnabled()) {
            return $this;
        }

        FormAbstract::beforeRendering(function (FormAbstract $form): void {
            $fieldKey = 'submit';

            if ($form instanceof FormFront) {
                $fieldKey = $form->has($fieldKey) ? $fieldKey : array_key_last($form->getFields());
            }

            if (! HCaptchaFacade::isEnabledForForm($form::class)) {
                return;
            }

            $form->addBefore(
                $fieldKey,
                'h-captcha',
                HCaptchaField::class
            );
        });

        Event::listen(Routing::class, function () {
            add_filter('core_request_rules', function (array $rules, Request $request) {
                HCaptchaFacade::getForms();

                if (HCaptchaFacade::isEnabledForForm(
                    HCaptchaFacade::getFormByRequest($request::class)
                )) {
                    $rules['h-captcha-response'] = [new HCaptchaRule()];
                }

                return $rules;
            }, 999, 2);

            add_filter('core_request_attributes', function (array $attributes, Request $request) {
                HCaptchaFacade::getForms();

                if (HCaptchaFacade::isEnabledForForm(
                    HCaptchaFacade::getFormByRequest($request::class)
                )) {
                    $attributes['h-captcha-response'] = 'hCaptcha';
                }

                return $attributes;
            }, 999, 2);
        });

        return $this;
    }
}
