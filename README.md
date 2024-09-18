# Overview
This is a plugin for Botble CMS, so you must purchase Botble CMS first to use this plugin. Purchase it here: [Botble CMS](https://codecanyon.net/item/botble-cms-php-platform-based-on-laravel-framework/16928182)

# hCaptcha

A plugin implement hCaptcha feature into Botble CMS.

hCaptcha is a CAPTCHA alternative that is privacy-focused. It is designed to protect your website from spam and abuse while letting real people pass through with ease.

![hCaptcha](./screenshot.png)

## Installation

### Requirements

* Botble core 7.2.6 or later.

### Install via Admin Panel

Go to the **Admin Panel** and click on the **Plugins** tab. Click on the **Add new** button, find the **FOB hCaptcha** plugin and click on the **Install** button.

### Install manually

1. Download the plugin from
   the [Botble Marketplace](https://marketplace.botble.com/products/rajaishtiaq6/hcaptcha).
2. Extract the downloaded file and upload the extracted folder to the `platform/plugins` directory.
3. Go to **Admin** > **Plugins** and click on the **Activate** button.

## Usage

![Demo](./art/demo.png)

In admin panel, go to `Settings` -> `Others` -> `hCaptcha` to configure the plugin.

To use hCaptcha, you'll need to [generate a hCaptcha token from hCaptcha](https://hCaptcha.com/?r=fe654a351e16). After that, and paste the `Site Key` and `Secret Key` into the plugin settings.

![Settings](./art/settings.png)

In the settings page, you can also configure which forms to enable hCaptcha on.

### Supported Forms

Currently, the plugin supports the following forms:

- Contact form
- Newsletter form
- Member:
  - Login form
  - Register form
  - Forgot password form
  - Reset password form
- Admin:
  - Login form
  - Forgot password form
  - Reset password form

You can request support for more forms by creating an issue on the [GitHub repository](../../issues).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email rajaishtiaq6@gmail.com instead of using the issue tracker.

## Credits

* [Ishtiaq Ahmed](https://github.com/rajaishtiaq6)
* [All Contributors](./contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
