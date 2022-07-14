# About Laravel OTP ▲

## Reference
  
[Laravel OTP](https://github.com/ichtrojan/laravel-otp "Cheers")

## Introduction

This is a simple package to generate and validate OTPs (One Time Passwords). This can be implemented mostly in Authentication.

## Installation

Install via composer

```bash
composer require abdukhaligov/laravel-otp
```

Add service provider to the `config/app.php` file

```php
<?php
   /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [
        ...
        Abdukhaligov\LaravelOtp\OtpServiceProvider::class,
    ];
...
```

Add alias to the `config/app.php` file

```php
<?php

   /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [
        ...
        'otp' => Abdukhaligov\LaravelOtp\Otp::class,
    ];
...
```

Run Migrations

```bash
php artisan migrate
```

## Usage


### Generate OTP

```php
<?php

Otp::generate(string $identifier, int $digits = 6, int $validity = 10)

Otp::validate(string $identifier, string $token)
```

### Delete expired tokens
You can delete expired tokens by running the following artisan command:
```bash
php artisan otp:clean
```
You can also add this artisan command to `app/Console/Kernel.php` to automatically clean on scheduled
```php
<?php

protected function schedule(Schedule $schedule)
{
    $schedule->command('otp:clean')->daily();
}
```
