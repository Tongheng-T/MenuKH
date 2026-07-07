<?php
/**
 * ==========================================================
 * MenuKH
 * Dashboard
 * ----------------------------------------------------------
 * File : owner/dashboard/index.php
 * Version : 1.0.0
 * ==========================================================
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/security.php';
require_once __DIR__ . '/../../includes/json.php';
require_once __DIR__ . '/../../includes/restaurant.php';
require_once __DIR__ . '/../../includes/routes.php';

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

requireOwner();

/*
|--------------------------------------------------------------------------
| Page
|--------------------------------------------------------------------------
*/

$pageTitle = 'Dashboard';

/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

$restaurant = restaurantInfo();

$menuCount       = restaurantMenuCount();
$categoryCount   = restaurantCategoryCount();
$orderCount      = restaurantOrderCount();
$tableCount      = restaurantTableCount();
$todayOrders     = restaurantTodayOrders();
$todayQrScans    = restaurantTodayQrScans();

$currentPlan     = restaurantPlan();
$restaurantLogo  = restaurantLogo();
$restaurantName  = restaurantName();

/*
|--------------------------------------------------------------------------
| Checklist
|--------------------------------------------------------------------------
*/

$checklist = [

    [
        'title' => 'Restaurant Created',
        'done'  => true
    ],

    [
        'title' => 'Upload Logo',
        'done'  => !empty($restaurantLogo)
    ],

    [
        'title' => 'Create Category',
        'done'  => $categoryCount > 0
    ],

    [
        'title' => 'Create Menu',
        'done'  => $menuCount > 0
    ],

    [
        'title' => 'Generate QR Code',
        'done'  => false
    ],

    [
        'title' => 'Share QR Code',
        'done'  => false
    ]

];

require_once __DIR__ . '/../../layouts/dashboard/header.php';
?>

<div class="container-fluid">

<div class="welcome-card mb-4">

    <h2>

        Welcome back,
        <?= e(currentUser()['owner_name'] ?? 'Owner') ?> 👋

    </h2>

    <p class="mb-0">

        Manage your restaurant, update menus,
        generate QR codes and monitor your business.

    </p>

</div>

<div class="row g-4 mb-4">

    <div class="col-lg-3 col-md-6">

        <div class="stat-card">

            <h2><?= $categoryCount ?></h2>

            <p class="mb-0">

                Categories

            </p>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="stat-card">

            <h2><?= $menuCount ?></h2>

            <p class="mb-0">

                Menus

            </p>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="stat-card">

            <h2><?= $todayOrders ?></h2>

            <p class="mb-0">

                Today's Orders

            </p>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="stat-card">

            <h2><?= $todayQrScans ?></h2>

            <p class="mb-0">

                QR Scans

            </p>

        </div>

    </div>

</div>
<div class="row g-4">

    <!-- Quick Setup -->

    <div class="col-lg-6">

        <div class="check-card">

            <h5 class="fw-bold mb-4">

                Quick Setup

            </h5>

            <?php foreach ($checklist as $item): ?>

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <span>

                        <?= e($item['title']) ?>

                    </span>

                    <?php if ($item['done']): ?>

                        <span class="badge bg-success">

                            Done

                        </span>

                    <?php else: ?>

                        <span class="badge bg-warning text-dark">

                            Pending

                        </span>

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

    <!-- Recent Activity -->

    <div class="col-lg-6">

        <div class="activity-card">

            <h5 class="fw-bold mb-4">

                Recent Activity

            </h5>

            <?php if ($orderCount > 0): ?>

                <div class="alert alert-success mb-0">

                    <i class="bi bi-check-circle me-2"></i>

                    Your restaurant has

                    <strong><?= $orderCount ?></strong>

                    order(s).

                </div>

            <?php else: ?>

                <div class="text-center py-4">

                    <i class="bi bi-clock-history display-5 text-secondary"></i>

                    <p class="text-secondary mt-3 mb-0">

                        No activity yet.

                        Start by creating your first category and menu.

                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body">

        <h5 class="fw-bold mb-4">

            Quick Actions

        </h5>

        <div class="row g-3">

            <div class="col-md-3">

                <a href="<?= route('categories.create') ?>"
                   class="btn btn-primary w-100 py-3">

                    <i class="bi bi-folder-plus d-block fs-3 mb-2"></i>

                    Add Category

                </a>

            </div>

            <div class="col-md-3">

                <a href="<?= route('menus.create') ?>"
                   class="btn btn-success w-100 py-3">

                    <i class="bi bi-cup-hot d-block fs-3 mb-2"></i>

                    Add Menu

                </a>

            </div>

            <div class="col-md-3">

                <a href="<?= route('restaurant.qr') ?>"
                   class="btn btn-warning w-100 py-3">

                    <i class="bi bi-qr-code d-block fs-3 mb-2"></i>

                    QR Code

                </a>

            </div>

            <div class="col-md-3">

                <a href="<?= route('orders.index') ?>"
                   class="btn btn-dark w-100 py-3">

                    <i class="bi bi-bag-check d-block fs-3 mb-2"></i>

                    Orders

                </a>

            </div>

        </div>

    </div>

</div>