<?php
/**
 * ==========================================================
 * MenuKH
 * Owner Dashboard
 * ----------------------------------------------------------
 * File : owner/dashboard.php
 * Version : 1.0.0
 * ==========================================================
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/json.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/restaurant.php';

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

if (!isLoggedIn()) {

    redirect('../login.php');

}

if ($_SESSION['user']['role'] !== 'owner') {

    redirect('../login.php');

}

/*
|--------------------------------------------------------------------------
| Restaurant Data
|--------------------------------------------------------------------------
*/

$info = restaurantInfo();

$settings = restaurantSettings();

$menuCount = restaurantMenuCount();

$categoryCount = restaurantCategoryCount();

$orderCount = restaurantOrderCount();

$tableCount = restaurantTableCount();

$todayOrders = restaurantTodayOrders();

$todayQrScans = restaurantTodayQrScans();

$restaurantName = restaurantName();

$restaurantLogo = restaurantLogo();

$currentPlan = restaurantPlan();

$currency = restaurantCurrency();

/*
|--------------------------------------------------------------------------
| Quick Setup Checklist
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
        'title' => 'Share QR',
        'done'  => false
    ]

];

/*
|--------------------------------------------------------------------------
| Page Title
|--------------------------------------------------------------------------
*/

$pageTitle = 'Dashboard';

?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

<?= APP_NAME ?> | Dashboard

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
rel="stylesheet">

<link
rel="preconnect"
href="https://fonts.googleapis.com">

<link
rel="preconnect"
href="https://fonts.gstatic.com"
crossorigin>

<link
href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&display=swap"
rel="stylesheet">
<style>

:root{
    --primary:#2563EB;
    --sidebar:#0F172A;
    --bg:#F8FAFC;
}

*{
    font-family:'Kantumruy Pro',sans-serif;
}

body{
    background:var(--bg);
}

.sidebar{

    width:260px;

    min-height:100vh;

    background:var(--sidebar);

    position:fixed;

    left:0;

    top:0;

    color:#fff;

    padding:25px;

}

.logo{

    font-size:28px;

    font-weight:700;

    margin-bottom:35px;

}

.logo span{

    color:#FDBA74;

}

.sidebar a{

    display:flex;

    align-items:center;

    gap:12px;

    color:#CBD5E1;

    text-decoration:none;

    padding:13px 15px;

    border-radius:12px;

    margin-bottom:8px;

    transition:.25s;

}

.sidebar a:hover{

    background:#1E293B;

    color:#fff;

}

.sidebar a.active{

    background:var(--primary);

    color:#fff;

}

.main{

    margin-left:260px;

    padding:35px;

}

.navbar-top{

    background:#fff;

    border-radius:18px;

    padding:18px 25px;

    box-shadow:0 8px 25px rgba(0,0,0,.05);

}

.stat-card{

    background:#fff;

    border-radius:20px;

    padding:25px;

    box-shadow:0 10px 30px rgba(0,0,0,.05);

    transition:.3s;

}

.stat-card:hover{

    transform:translateY(-4px);

}

.welcome-card{

    background:linear-gradient(135deg,#2563EB,#1D4ED8);

    color:#fff;

    border-radius:22px;

    padding:35px;

}

.check-card{

    background:#fff;

    border-radius:20px;

    padding:25px;

    box-shadow:0 10px 30px rgba(0,0,0,.05);

}

.activity-card{

    background:#fff;

    border-radius:20px;

    padding:25px;

    box-shadow:0 10px 30px rgba(0,0,0,.05);

}

@media(max-width:992px){

.sidebar{

display:none;

}

.main{

margin-left:0;

}

}

</style>

</head>

<body>

<div class="sidebar">

<div class="logo">

Menu<span>KH</span>

</div>

<a class="active" href="#">

<i class="bi bi-grid-fill"></i>

Dashboard

</a>

<a href="categories.php">

<i class="bi bi-folder2-open"></i>

Categories

</a>

<a href="menu.php">

<i class="bi bi-cup-hot"></i>

Menus

</a>

<a href="#">

<i class="bi bi-qr-code"></i>

QR Code

</a>

<a href="#">

<i class="bi bi-bag-check"></i>

Orders

</a>

<a href="#">

<i class="bi bi-people"></i>

Customers

</a>

<a href="#">

<i class="bi bi-gear"></i>

Restaurant

</a>

<a href="#">

<i class="bi bi-person-circle"></i>

Profile

</a>

<a href="../logout.php">

<i class="bi bi-box-arrow-right"></i>

Logout

</a>

</div>

<div class="main">

<div class="navbar-top d-flex justify-content-between align-items-center mb-4">

<div>

<h4 class="fw-bold mb-0">

<?= e($restaurantName) ?>

</h4>

<small class="text-secondary">

<?= e($_SESSION['user']['email']) ?>

</small>

</div>

<div>

<span class="badge bg-primary">

<?= strtoupper($currentPlan) ?>

</span>

</div>

</div>

<div class="welcome-card mb-4">

<h2>

Welcome back,
<?= e($_SESSION['user']['name']) ?> 👋

</h2>

<p class="mb-0">

Manage your restaurant,
update menus,
generate QR Code
and receive customer orders.

</p>

</div>

<div class="row g-4 mb-4">

<div class="col-lg-3">

<div class="stat-card">

<h2>

<?= $categoryCount ?>

</h2>

<p class="mb-0">

Categories

</p>

</div>

</div>

<div class="col-lg-3">

<div class="stat-card">

<h2>

<?= $menuCount ?>

</h2>

<p class="mb-0">

Menus

</p>

</div>

</div>

<div class="col-lg-3">

<div class="stat-card">

<h2>

<?= $todayOrders ?>

</h2>

<p class="mb-0">

Today's Orders

</p>

</div>

</div>

<div class="col-lg-3">

<div class="stat-card">

<h2>

<?= $todayQrScans ?>

</h2>

<p class="mb-0">

QR Scans

</p>

</div>

</div>

</div>

<div class="row g-4">

<div class="col-lg-6">

<div class="check-card">

<h5 class="fw-bold mb-4">

Quick Setup

</h5>

<?php foreach($checklist as $item): ?>

<div class="d-flex justify-content-between mb-3">

<span>

<?= e($item['title']) ?>

</span>

<?php if($item['done']): ?>

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

<div class="col-lg-6">

<div class="activity-card">

<h5 class="fw-bold">

Recent Activity

</h5>

<p class="text-secondary mb-0">

No activity yet.

Start by creating your first menu.

</p>

</div>

</div>

</div>
<div class="mt-5">

    <hr>

    <div class="d-flex justify-content-between align-items-center">

        <small class="text-secondary">

            © <?= date('Y') ?> <?= APP_NAME ?>

        </small>

        <small class="text-secondary">

            Version 1.0.0

        </small>

    </div>

</div>

</div><!-- /.main -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

/**
 * ==========================================================
 * MenuKH Dashboard
 * Version : 1.0.0
 * ==========================================================
 */

'use strict';

/*
|--------------------------------------------------------------------------
| Active Sidebar
|--------------------------------------------------------------------------
*/

const currentPage = window.location.pathname.split('/').pop();

document.querySelectorAll('.sidebar a').forEach(link => {

    const href = link.getAttribute('href');

    if (href === currentPage) {

        document
            .querySelectorAll('.sidebar a')
            .forEach(item => item.classList.remove('active'));

        link.classList.add('active');

    }

});

/*
|--------------------------------------------------------------------------
| Card Hover Animation
|--------------------------------------------------------------------------
*/

document.querySelectorAll('.stat-card').forEach(card => {

    card.addEventListener('mouseenter', () => {

        card.style.transform = 'translateY(-6px)';

    });

    card.addEventListener('mouseleave', () => {

        card.style.transform = '';

    });

});

/*
|--------------------------------------------------------------------------
| Auto Hide Flash Message
|--------------------------------------------------------------------------
*/

const alertBox = document.querySelector('.alert');

if (alertBox) {

    setTimeout(() => {

        alertBox.classList.add('fade');

        setTimeout(() => {

            alertBox.remove();

        }, 300);

    }, 4000);

}

</script>

</body>

</html>