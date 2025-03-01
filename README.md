A package to facilitate the server side validation of Cloudflare's Turnstile captcha service.

> **Requires [PHP 8.3+](https://php.net/releases/)**

⚡️ Install the package using [Composer](https://getcomposer.org):

```bash
composer require vincenzoraco/turnstile-php
```

### Usage

```php
$turnstile = new Turnstile($secret));

/** @var TurnstileValidateResponseDTO $response */
$response = $turnstile->validate(new TurnstileValidateDTO(
    $token,
));

$response->isSuccess(); // bool
```

**Turnstile PHP** was created by **[Vincenzo Raco](https://github.com/vincenzoraco)**.
