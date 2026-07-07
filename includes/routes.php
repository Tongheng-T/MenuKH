<?php
/**
 * ==========================================================
 * MenuKH
 * Route Helper
 * ----------------------------------------------------------
 * File : includes/routes.php
 * Version : 1.0.0
 * ==========================================================
 */

function routes(): array
{
    return [

        // Authentication
        'login' => 'login.php',
        'register' => 'register.php',
        'logout' => 'logout.php',

        // Owner Dashboard
        'owner.dashboard' => 'owner/dashboard/',

        // Categories
        'categories.index' => 'owner/categories/',
        'categories.create' => 'owner/categories/create.php',
        'categories.edit' => 'owner/categories/edit.php',
        'categories.delete' => 'owner/categories/delete.php',
        'categories.status' => 'owner/categories/status.php',

        // Menus
        'menus.index' => 'owner/menus/',
        'menus.create' => 'owner/menus/create.php',

        // Orders
        'orders.index' => 'owner/orders/',

        // Customers
        'customers.index' => 'owner/customers/',

        // Restaurant
        'restaurant.settings' => 'owner/restaurant/settings.php',
        'restaurant.qr' => 'owner/restaurant/qr.php',

        // Profile
        'profile.index' => 'owner/profile/'

    ];
}
/*
|--------------------------------------------------------------------------
| Generate Route URL
|--------------------------------------------------------------------------
*/

function route(string $name): string
{
    $routes = routes();

    if (!isset($routes[$name])) {
        throw new InvalidArgumentException(
            "Route '{$name}' does not exist."
        );
    }

    return url($routes[$name]);
}

/*
|--------------------------------------------------------------------------
| Redirect To Route
|--------------------------------------------------------------------------
*/

function redirectRoute(string $name): void
{
    redirect(
        route($name)
    );
}

/*
|--------------------------------------------------------------------------
| Check Current Route
|--------------------------------------------------------------------------
*/

function isCurrentRoute(string $name): bool
{
    $routes = routes();

    if (!isset($routes[$name])) {
        return false;
    }

    $current = strtok($_SERVER['REQUEST_URI'], '?');

    $target = parse_url(
        route($name),
        PHP_URL_PATH
    );

    return rtrim($current, '/') === rtrim($target, '/');
}