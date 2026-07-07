<?php
/**
 * ==========================================================
 * MenuKH
 * Dashboard Sidebar
 * ----------------------------------------------------------
 * File : layouts/dashboard/sidebar.php
 * Version : 1.0.0
 * ==========================================================
 */

$currentPage = basename($_SERVER['PHP_SELF']);

$menus = [

    [
        'title' => 'Dashboard',
        'icon'  => 'bi-grid-fill',
        'url'   => 'dashboard.php'
    ],

    [
        'title' => 'Categories',
        'icon'  => 'bi-folder2-open',
        'url'   => 'categories.php'
    ],

    [
        'title' => 'Menus',
        'icon'  => 'bi-cup-hot',
        'url'   => 'menu.php'
    ],

    [
        'title' => 'QR Code',
        'icon'  => 'bi-qr-code',
        'url'   => 'qr.php'
    ],

    [
        'title' => 'Orders',
        'icon'  => 'bi-bag-check',
        'url'   => 'orders.php'
    ],

    [
        'title' => 'Customers',
        'icon'  => 'bi-people',
        'url'   => 'customers.php'
    ],

    [
        'title' => 'Restaurant',
        'icon'  => 'bi-shop',
        'url'   => 'restaurant.php'
    ],

    [
        'title' => 'Settings',
        'icon'  => 'bi-gear',
        'url'   => 'settings.php'
    ],

    [
        'title' => 'Profile',
        'icon'  => 'bi-person-circle',
        'url'   => 'profile.php'
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

            <a

                href="<?= e($menu['url']) ?>"

                class="<?= $currentPage == $menu['url'] ? 'active' : '' ?>">

                <i class="bi <?= e($menu['icon']) ?>"></i>

                <span>

                    <?= e($menu['title']) ?>

                </span>

            </a>

        <?php endforeach; ?>

    </div>

    <div class="mk-sidebar-footer">

        <a href="../logout.php">

            <i class="bi bi-box-arrow-right"></i>

            <span>

                Logout

            </span>

        </a>

    </div>

</aside>