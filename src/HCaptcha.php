<?php

namespace Shaqi\HCaptcha;

use Botble\Theme\FormFrontManager;
use Shaqi\HCaptcha\Contracts\HCaptcha as HCaptchaContract;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class HCaptcha implements HCaptchaContract
{

    public const VERIFY_BASE_URL = 'https://hcaptcha.com';
    public const VERIFY_URL = 'https://hcaptcha.com/siteverify';
    public const CLIENT_API = 'https://hcaptcha.com/1/api.js';

    protected array $forms = [];
    protected array $requests = [];

    protected array $verifiedResponses = [];


    public function __construct(
        protected ?string $siteKey,
        protected ?string $secretKey,
    ) {}

    public function registerForm(string $form, string $request, string $title): static
    {
        $this->forms[$form] = $title;
        $this->requests[$form] = $request;

        return $this;
    }

    public function getForms(): array
    {
        foreach (FormFrontManager::forms() as $form) {
            $this->registerForm($form, FormFrontManager::formRequestOf($form), $form::formTitle());
        }

        return $this->forms;
    }

    public function isEnabled(): bool
    {
        return (bool) $this->getSetting('enabled', false) && ! empty($this->siteKey) && ! empty($this->secretKey);
    }

    public function isEnabledForForm(string $form): bool
    {
        return (bool) setting($this->getFormSettingKey($form), false);
    }

    public function getFormByRequest(string $request): string
    {
        return array_search($request, $this->requests, true);
    }

    public function getFormSettingKey(string $form): string
    {
        return $this->getSettingKey(sprintf('%s_%s', str_replace('\\', '', Str::snake($form)), 'enabled'));
    }

    public function verify(string $captchaResponse, ?string $clientIp = null): array
    {
         // Return false if the captcha response is empty
         if (empty($captchaResponse)) {
            return false;
        }

        // Check if the captcha response was previously verified
        if (in_array($captchaResponse, $this->verifiedResponses, true)) {
            return true;
        }

        // Send the POST request to the reCAPTCHA verification URL
        $response = Http::asForm()->post(self::VERIFY_URL, [
            'secret' => $this->secretKey,
            'response' => $captchaResponse,
            'remoteip' => $clientIp,
        ]);
        // Decode the JSON response
        $responseBody = $response->json();
        // Check if the 'success' key is true in the response
        $success = $responseBody['success'] ?? false;
        // If the response is successful, store it in the verified responses array
        if ($success) {
            $this->verifiedResponses[] = $captchaResponse;
        }
        return $responseBody;
    }

    public function getSettingKey(string $key): string
    {
        return "hcaptcha_$key";
    }


    public function getSetting(string $key, mixed $default = null): mixed
    {
        return setting($this->getSettingKey($key), $default);
    }

    public function display($attributes = [])
    {
        if (!$this->isEnabled()) {
            return '';
        }

        $attributes = $this->prepareAttributes($attributes);
        return '<div ' . $this->buildAttributes($attributes) . '> </div>';
    }

    public function displaySubmit(string $formIdentifier, string $buttonText = 'submit', array $attributes = []): string
    {
        $javascript = '';
        if (! isset($attributes['data-callback'])) {
            $functionName = sprintf('on%sSubmit', Str::of($formIdentifier)->title()->replace(['-', '=', '\'', '"', '<', '>', '`'], ''));
            $attributes['data-callback'] = $functionName;

            $javascript = view('hcaptcha::default-submit-callback', [
                'formIdentifier' => $formIdentifier,
                'functionName' => $functionName,
            ])->render();
        }

        $attributes = $this->prepareAttributes($attributes);

        $button = view('hcaptcha::submit', [
            'attributes' => $this->buildAttributes($attributes),
            'text' => $buttonText,
        ])->render();

        return $button . $javascript;
    }


    /** Get hCaptcha js link. */
    public function getJsLink(?string $lang = null, bool $hasCallback = false, string $onLoadClass = 'onloadCallBack'): string
    {
        $params = [];

        if ($hasCallback) {
            $params['render'] = 'explicit';
            $params['onload'] = $onLoadClass;
        }

        if ($lang) {
            $params['hl'] = $lang;
        }

        if (empty($params)) {
            return self::CLIENT_API;
        }

        return self::CLIENT_API . '?' . http_build_query($params);
    }


    public function prepareAttributes(array $attributes): array
    {
        $attributes['data-sitekey'] = $this->siteKey;

        if (! isset($attributes['class'])) {
            $attributes['class'] = '';
        }

        $attributes['class'] = trim('h-captcha ' . $attributes['class']);

        return $attributes;
    }

    public function buildAttributes(array $attributes): string
    {
        $htmlAttributesAsString = [];

        foreach ($attributes as $key => $value) {
            $htmlAttributesAsString[] = sprintf('%s="%s"', $key, $value);
        }

        return implode(' ', $htmlAttributesAsString);
    }

}
