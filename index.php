<?php
session_start();

$app = [
    'name' => 'MenuKH',
    'tagline' => 'Digital QR Menu Platform',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $app['name']; ?> | <?= $app['tagline']; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root{
            --primary:#2563eb;
            --secondary:#f97316;
            --dark:#0f172a;
            --light:#f8fafc;
        }

        body{
            background:var(--light);
            color:var(--dark);
            font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif;
        }

        .navbar{
            backdrop-filter:blur(14px);
            background:rgba(255,255,255,.9);
        }

        .logo{
            font-weight:800;
            font-size:30px;
            color:var(--primary);
        }

        .logo span{
            color:var(--secondary);
        }

        .hero{
            padding:110px 0;
            background:
            radial-gradient(circle at top left,#dbeafe,transparent 40%),
            radial-gradient(circle at bottom right,#ffedd5,transparent 40%);
        }

        .hero h1{
            font-size:56px;
            font-weight:800;
        }

        .hero p{
            font-size:20px;
            color:#475569;
        }

        .btn-main{
            background:var(--primary);
            color:#fff;
            border-radius:14px;
            padding:14px 28px;
            font-weight:600;
        }

        .btn-main:hover{
            background:#1d4ed8;
            color:#fff;
        }

        .btn-outline-main{
            border:2px solid var(--primary);
            color:var(--primary);
            border-radius:14px;
            padding:14px 28px;
            font-weight:600;
        }

        .phone{
            background:#fff;
            border-radius:35px;
            box-shadow:0 25px 60px rgba(0,0,0,.12);
            padding:20px;
        }

        .menu-item{
            display:flex;
            justify-content:space-between;
            padding:14px 0;
            border-bottom:1px solid #eee;
        }

        .badge-free{
            background:#dcfce7;
            color:#166534;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg sticky-top shadow-sm">
    <div class="container">

        <a class="navbar-brand logo" href="#">
            Menu<span>KH</span>
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
            ☰
        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav ms-auto me-4">

                <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#features">Features</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#pricing">Pricing</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#faq">FAQ</a>
                </li>

            </ul>

            <a href="login.php" class="btn btn-outline-primary me-2">
                Login
            </a>

            <a href="register.php" class="btn btn-main">
                Get Started
            </a>

        </div>

    </div>
</nav>


<section class="hero">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <span class="badge badge-free mb-3 p-2">
                    🚀 Free Plan Available
                </span>

                <h1>
                    Create Your Digital QR Menu in Minutes
                </h1>

                <p class="mt-4">
                    MenuKH helps restaurants, cafés and coffee shops create
                    beautiful QR menus without installing any app.
                    Customers simply scan and browse your menu instantly.
                </p>

                <div class="mt-5">

                    <a href="register.php" class="btn btn-main me-3">
                        Start Free
                    </a>

                    <a href="#" class="btn btn-outline-main">
                        Live Demo
                    </a>

                </div>

            </div>

            <div class="col-lg-6">

                <div class="phone">

                    <h4 class="fw-bold mb-4">
                        Heng Heng Restaurant
                    </h4>

                    <div class="menu-item">
                        <span>🍔 Beef Burger</span>
                        <strong>$5.90</strong>
                    </div>

                    <div class="menu-item">
                        <span>🍟 French Fries</span>
                        <strong>$2.50</strong>
                    </div>

                    <div class="menu-item">
                        <span>🥤 Coca Cola</span>
                        <strong>$1.50</strong>
                    </div>

                    <div class="menu-item">
                        <span>🍕 Pizza</span>
                        <strong>$9.90</strong>
                    </div>

                    <div class="text-center mt-4">

                        <button class="btn btn-success w-100">
                            Scan QR to View Menu
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>