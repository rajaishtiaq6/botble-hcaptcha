<?php

namespace Shaqi\HCaptcha\Contracts;

interface HCaptcha
{
    public function registerForm(string $form, string $request, string $title): static;

    public function getForms(): array;

    public function isEnabled(): bool;

    public function isEnabledForForm(string $form): bool;

    public function getFormByRequest(string $request): ?string;

    public function getFormSettingKey(string $form): string;

    public function verify(string $response): array;

    public function getSetting(string $key, mixed $default = null): mixed;

    public function displaySubmit(string $formIdentifier, string $buttonText = 'submit', array $attributes = []): string;

    public function getJsLink(?string $lang = null, bool $hasCallback = false, string $onLoadClass = 'onloadCallBack'): string;

    public function prepareAttributes(array $attributes): array;

    public function buildAttributes(array $attributes): string;
}
