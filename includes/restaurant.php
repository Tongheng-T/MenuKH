<?php
/**
 * ==========================================================
 * MenuKH
 * Restaurant Helper
 * ----------------------------------------------------------
 * File : includes/restaurant.php
 * Version : 1.0.0
 * ==========================================================
 */

if (!defined('RESTAURANT_PATH')) {
    exit('No direct script access allowed.');
}

/*
|--------------------------------------------------------------------------
| Current Restaurant ID
|--------------------------------------------------------------------------
*/

function restaurantId(): ?string
{
    return $_SESSION['user']['restaurant_id'] ?? null;
}

/*
|--------------------------------------------------------------------------
| Restaurant Folder
|--------------------------------------------------------------------------
*/

function restaurantFolder(): string
{
    return RESTAURANT_PATH . '/' . restaurantId();
}

/*
|--------------------------------------------------------------------------
| Restaurant Info
|--------------------------------------------------------------------------
*/

function restaurantInfo(): array
{
    return JsonDB::read(
        restaurantFolder() . '/info.json'
    );
}

/*
|--------------------------------------------------------------------------
| Restaurant Settings
|--------------------------------------------------------------------------
*/

function restaurantSettings(): array
{
    return JsonDB::read(
        restaurantFolder() . '/settings.json'
    );
}

/*
|--------------------------------------------------------------------------
| Categories
|--------------------------------------------------------------------------
*/

function restaurantCategories(): array
{
    return JsonDB::read(
        restaurantFolder() . '/categories.json'
    );
}

/*
|--------------------------------------------------------------------------
| Menu
|--------------------------------------------------------------------------
*/

function restaurantMenus(): array
{
    return JsonDB::read(
        restaurantFolder() . '/menu.json'
    );
}

/*
|--------------------------------------------------------------------------
| Orders
|--------------------------------------------------------------------------
*/

function restaurantOrders(): array
{
    return JsonDB::read(
        restaurantFolder() . '/orders.json'
    );
}

/*
|--------------------------------------------------------------------------
| Tables
|--------------------------------------------------------------------------
*/

function restaurantTables(): array
{
    return JsonDB::read(
        restaurantFolder() . '/tables.json'
    );
}

/*
|--------------------------------------------------------------------------
| Menu Count
|--------------------------------------------------------------------------
*/

function restaurantMenuCount(): int
{
    return count(
        restaurantMenus()
    );
}

/*
|--------------------------------------------------------------------------
| Category Count
|--------------------------------------------------------------------------
*/

function restaurantCategoryCount(): int
{
    return count(
        restaurantCategories()
    );
}

/*
|--------------------------------------------------------------------------
| Order Count
|--------------------------------------------------------------------------
*/

function restaurantOrderCount(): int
{
    return count(
        restaurantOrders()
    );
}

/*
|--------------------------------------------------------------------------
| Table Count
|--------------------------------------------------------------------------
*/

function restaurantTableCount(): int
{
    return count(
        restaurantTables()
    );
}

/*
|--------------------------------------------------------------------------
| Restaurant Name
|--------------------------------------------------------------------------
*/

function restaurantName(): string
{
    $info = restaurantInfo();

    return $info['name'] ?? '';
}

/*
|--------------------------------------------------------------------------
| Restaurant Logo
|--------------------------------------------------------------------------
*/

function restaurantLogo(): string
{
    $info = restaurantInfo();

    return $info['logo'] ?? '';
}

/*
|--------------------------------------------------------------------------
| Restaurant Theme
|--------------------------------------------------------------------------
*/

function restaurantTheme(): string
{
    $settings = restaurantSettings();

    return $settings['theme'] ?? '#2563EB';
}

/*
|--------------------------------------------------------------------------
| Restaurant Plan
|--------------------------------------------------------------------------
*/

function restaurantPlan(): string
{
    $info = restaurantInfo();

    return $info['plan'] ?? 'free';
}

/*
|--------------------------------------------------------------------------
| Restaurant Currency
|--------------------------------------------------------------------------
*/

function restaurantCurrency(): string
{
    $settings = restaurantSettings();

    return $settings['currency'] ?? '$';
}

/*
|--------------------------------------------------------------------------
| Today Orders
|--------------------------------------------------------------------------
*/

function restaurantTodayOrders(): int
{
    $today = date('Y-m-d');

    $count = 0;

    foreach (restaurantOrders() as $order) {

        if (
            isset($order['created_at']) &&
            str_starts_with($order['created_at'], $today)
        ) {

            $count++;

        }

    }

    return $count;
}

/*
|--------------------------------------------------------------------------
| Today QR Scans
|--------------------------------------------------------------------------
|
| Version 1.0
| (Analytics module will be added later)
|--------------------------------------------------------------------------
*/

function restaurantTodayQrScans(): int
{
    $file = restaurantFolder() . '/analytics.json';

    if (!file_exists($file)) {
        return 0;
    }

    $today = date('Y-m-d');

    $count = 0;

    foreach (JsonDB::read($file) as $scan) {

        if (
            isset($scan['created_at']) &&
            str_starts_with($scan['created_at'], $today)
        ) {

            $count++;

        }

    }

    return $count;
}