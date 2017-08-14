XSS Middleware
=========================

A simple middleware for use in Laravel projects.

#### Installation

Clone the repository

```bash
composer require sneek/laravel-xss-middleware
```

Add to the Http kernel `App\Http\Kernel`

```php
    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            ...
            \Sneek\Http\Middleware\XSSProtection::class,
        ],
    ....
```

