# Katana

Allows you to library provides functionality to render Laravel Blade templates in any PHP project, without requiring the full Laravel framework.

> **Note:** This package is still in development and not ready for production use.

## Getting Starting

You can install the package via composer:

```bash
composer require soysudhanshu/katana
```

### Usage

```php

use Blade\Blade;

$blade = new Blade([
    'views' => __DIR__ . '/views',
    'cache' => __DIR__ . '/cache',
]);

echo $blade->render('hello', ['name' => 'Jhon Doe']);
```
