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