<?php
/**
 * =====================================================
 * MenuKH
 * Header Layout
 * Version : 1.0.0
 * =====================================================
 */

if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../includes/config.php';
}

$pageTitle = $pageTitle ?? APP_NAME;
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title><?= e($pageTitle) ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- Main CSS -->
    <link rel="stylesheet"
          href="<?= asset('css/style.css'); ?>">

</head>

<body>

<div class="app-wrapper"></div>