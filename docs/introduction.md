# Getting Started

## About Katana

Katana is a simple template engine inspired by Laravel Blade, tailored for use in any PHP project to streamline the templating process. Drawing from Blade's intuitive syntax and powerful features, Katana simplifies view rendering and management, making it ideal for both beginner and experienced developers alike. Its ease of integration and customization capabilities make Katana particularly well-suited for starter projects, offering a familiar yet robust solution to enhance productivity and maintainability.

### Why Katana

-   **Simplicity**: Katana's syntax is easy to learn and use, making it accessible to developers of all skill levels.

-   **Works everywhere**: Katana can be used in any PHP project, regardless of the framework or platform.

-   **Powerful features**: Katana offers a wide range of features to streamline the templating process, including template inheritance, sections, and more.

## Installation

Before using Katana, you need to install it in your project. You can install Katana via Composer by running the following command:

```bash
composer require soysudhanshu/katana
```

### Rendering Views

Before you can render views using Katana, you need to configure it with the path to your views directory and cache directory. You can do this by creating a new instance of the `Katana` class and passing the paths to the constructor:

```php
use soydhanshu\Katana;

$katana = new Katana(
    'path/to/views', // Path to the views directory
    'path/to/cache'  // Path to the cache directory
);
```

> [!Note]
> View and cache directories must be writable by the web server.

> [!WARNING]
> Ensure that cache directory is not accessible from the web by placing it outside the web root or using `.htaccess` rules..

Once you have installed and configured views and cache directories, you render views using the `render` method:

```php
echo $katana->render('welcome', ['name' => 'John']);
```
This will render the `welcome.blade.php` view with the given data.