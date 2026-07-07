<?php
/**
 * ==========================================================
 * MenuKH
 * Dashboard Sidebar
 * ----------------------------------------------------------
 * File : layouts/dashboard/sidebar.php
 * Version : 1.0.0
 * ==========================================================<a href="<?= route('owner.dashboard') ?>">
 */

$currentPage = basename($_SERVER['PHP_SELF']);

$menus = [

    [
        'title' => 'Dashboard',
        'route' => 'owner.dashboard',
        'icon' => 'bi-grid-fill'
    ],

    [
        'title' => 'Categories',
        'route' => 'categories.index',
        'icon' => 'bi-folder2-open'
    ],

    [
        'title' => 'Menus',
        'route' => 'menus.index',
        'icon' => 'bi-cup-hot'
    ],

    [
        'title' => 'Orders',
        'route' => 'orders.index',
        'icon' => 'bi-bag-check'
    ],

    [
        'title' => 'Customers',
        'route' => 'customers.index',
        'icon' => 'bi-people'
    ],

    [
        'title' => 'Restaurant',
        'route' => 'restaurant.settings',
        'icon' => 'bi-shop'
    ],

    [
        'title' => 'Profile',
        'route' => 'profile.index',
        'icon' => 'bi-person-circle'
    ]

];

?>

<aside class="mk-sidebar">

    <div class="mk-sidebar-logo">

        <a href="dashboard.php">

            Menu<span>KH</span>

        </a>

    </div>

    <div class="mk-sidebar-menu">

        <?php foreach ($menus as $menu): ?>

            <a href="<?= route($menu['route']) ?>" class="<?= isCurrentRoute($menu['route']) ? 'active' : '' ?>">

                <i class="bi <?= e($menu['icon']) ?>"></i>

                <span>

                    <?= e($menu['title']) ?>

                </span>

            </a>

        <?php endforeach; ?>

    </div>

    <div class="mk-sidebar-footer">

        <a href="<?= route('logout') ?>">

            <i class="bi bi-box-arrow-right"></i>

            <span>

                Logout

            </span>

        </a>

    </div>

</aside>