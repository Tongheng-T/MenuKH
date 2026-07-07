<?php
/**
 * ==========================================================
 * MenuKH
 * Dashboard Navbar
 * ----------------------------------------------------------
 * File : layouts/dashboard/navbar.php
 * Version : 1.0.0
 * ==========================================================
 */

$info = restaurantInfo();

$user = $_SESSION['user'];

?>

<nav class="mk-navbar">

    <div class="mk-navbar-left">

        <button
            class="mk-sidebar-toggle"
            id="sidebarToggle">

            <i class="bi bi-list"></i>

        </button>

        <div>

            <h4 class="mb-0 fw-bold">

                <?= e($info['name'] ?? 'Restaurant') ?>

            </h4>

            <small class="text-secondary">

                <?= e($user['email']) ?>

            </small>

        </div>

    </div>

    <div class="mk-navbar-right">

        <span class="badge bg-primary">

            <?= strtoupper(restaurantPlan()) ?>

        </span>

        <button
            class="btn btn-light position-relative">

            <i class="bi bi-bell"></i>

            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                0

            </span>

        </button>

        <div class="dropdown">

            <button
                class="btn btn-light dropdown-toggle d-flex align-items-center gap-2"
                data-bs-toggle="dropdown">

                <?php if (restaurantLogo()): ?>

                    <img
                        src="<?= e(restaurantLogo()) ?>"
                        width="36"
                        height="36"
                        class="rounded-circle">

                <?php else: ?>

                    <div
                        class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                        style="width:36px;height:36px;">

                        <?= strtoupper(substr($user['name'],0,1)) ?>

                    </div>

                <?php endif; ?>

                <?= e($user['name']) ?>

            </button>

            <ul class="dropdown-menu dropdown-menu-end">

                <li>

                    <a
                        class="dropdown-item"
                        href="profile.php">

                        <i class="bi bi-person me-2"></i>

                        Profile

                    </a>

                </li>

                <li>

                    <a
                        class="dropdown-item"
                        href="settings.php">

                        <i class="bi bi-gear me-2"></i>

                        Settings

                    </a>

                </li>

                <li><hr class="dropdown-divider"></li>

                <li>

                    <a
                        class="dropdown-item text-danger"
                        href="../logout.php">

                        <i class="bi bi-box-arrow-right me-2"></i>

                        Logout

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>