<?php
/**
 * ==========================================================
 * MenuKH
 * Digital QR Menu Platform
 * ----------------------------------------------------------
 * File : login.php
 * Version : 1.0.0
 * ==========================================================
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/json.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/security.php';
require_once __DIR__ . '/includes/auth.php';

/*
|--------------------------------------------------------------------------
| Redirect if logged in
|--------------------------------------------------------------------------
*/

if (isLoggedIn()) {
    redirect('owner/dashboard/dashboard.php');
}

/*
|--------------------------------------------------------------------------
| Default Variables
|--------------------------------------------------------------------------
*/

$errors = [];

$form = [
    'email' => ''
];

/*
|--------------------------------------------------------------------------
| Handle Login
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Protection
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request.';
    }

    // Clean Input
    $form['email'] = strtolower(
        clean($_POST['email'] ?? '')
    );

    $password = $_POST['password'] ?? '';

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if (!valid_email($form['email'])) {
        $errors[] = 'Invalid email address.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        $users = JsonDB::read(USERS_FILE);

        $currentUser = null;

        foreach ($users as $user) {

            if (
                isset($user['email']) &&
                strtolower($user['email']) === $form['email']
            ) {

                $currentUser = $user;

                break;

            }

        }

        if (!$currentUser) {

            $errors[] = 'Invalid email or password.';

        } elseif (
            !password_verify(
                $password,
                $currentUser['password']
            )
        ) {

            $errors[] = 'Invalid email or password.';

        } elseif (
            $currentUser['status'] !== 'active'
        ) {

            $errors[] = 'Your account has been disabled.';

        } else {

            /*
            |--------------------------------------------------------------------------
            | Update Last Login
            |--------------------------------------------------------------------------
            */

            foreach ($users as &$user) {

                if ($user['id'] === $currentUser['id']) {

                    $user['last_login'] = date('Y-m-d H:i:s');

                    break;

                }

            }

            JsonDB::write(
                USERS_FILE,
                $users
            );

            /*
            |--------------------------------------------------------------------------
            | Login Session
            |--------------------------------------------------------------------------
            */

            $_SESSION['user'] = [

                'id' => $currentUser['id'],

                'restaurant_id' => $currentUser['restaurant_id'],

                'restaurant_slug' => $currentUser['restaurant_slug'],

                'role' => $currentUser['role'],

                'name' => $currentUser['owner_name'],

                'restaurant_name' => $currentUser['restaurant_name'],

                'email' => $currentUser['email']

            ];

            setFlash(
                'success',
                'Welcome back ' .
                $currentUser['owner_name'] .
                ' 👋'
            );

            redirect(
                'owner/dashboard.php'
            );

        }

    }

}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | <?= APP_NAME ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary: #2563EB;
            --secondary: #F97316;
            --bg: #f8fafc;
        }

        * {
            font-family: 'Kantumruy Pro', sans-serif;
        }

        body {
            background: linear-gradient(135deg,
                    #dbeafe,
                    #ffffff,
                    #ffedd5);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-card {
            background: rgba(255, 255, 255, .82);
            backdrop-filter: blur(16px);
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .08);
            overflow: hidden;
        }

        .left-side {
            background: linear-gradient(135deg,
                    var(--primary),
                    #1D4ED8);

            color: #fff;

            padding: 60px;

            height: 100%;
        }

        .left-side h1 {
            font-size: 42px;
            font-weight: 700;
        }

        .left-side p {
            margin-top: 20px;
            line-height: 1.8;
            opacity: .9;
        }

        .logo {
            font-size: 34px;
            font-weight: 800;
        }

        .logo span {
            color: #FDBA74;
        }

        .right-side {
            padding: 50px;
        }

        .form-control {
            border-radius: 14px;
            padding: 14px;
        }

        .btn-login {
            background: var(--primary);
            color: #fff;
            border-radius: 14px;
            padding: 14px;
            font-weight: 600;
        }

        .btn-login:hover {
            background: #1D4ED8;
            color: #fff;
        }

        @media(max-width:991px) {

            .left-side {
                display: none;
            }

        }
    </style>

</head>

<body>

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-xl-10">

                <div class="login-card">

                    <div class="row g-0">

                        <div class="col-lg-5">

                            <div class="left-side">

                                <div class="logo">

                                    Menu<span>KH</span>

                                </div>

                                <h1 class="mt-5">

                                    Welcome Back

                                </h1>

                                <p>

                                    Sign in to manage your restaurant,
                                    update menus,
                                    receive customer orders,
                                    and grow your business.

                                </p>

                            </div>

                        </div>

                        <div class="col-lg-7">

                            <div class="right-side">

                                <h2 class="fw-bold">

                                    Sign In

                                </h2>

                                <p class="text-secondary">

                                    Welcome back to MenuKH.

                                </p>

                                <?php if (!empty($errors)): ?>

                                    <div class="alert alert-danger">

                                        <ul class="mb-0 ps-3">

                                            <?php foreach ($errors as $error): ?>

                                                <li>
                                                    <?= e($error) ?>
                                                </li>

                                            <?php endforeach; ?>

                                        </ul>

                                    </div>

                                <?php endif; ?>

                                <form method="POST" autocomplete="off">

                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Email Address

                                        </label>

                                        <input type="email" name="email" class="form-control"
                                            placeholder="you@example.com" value="<?= e($form['email']) ?>" required>

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Password

                                        </label>

                                        <input type="password" name="password" class="form-control"
                                            placeholder="Enter password" required>

                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-4">

                                        <div class="form-check">

                                            <input class="form-check-input" type="checkbox" name="remember"
                                                id="remember">

                                            <label class="form-check-label" for="remember">

                                                Remember me

                                            </label>

                                        </div>

                                        <a href="#" class="text-decoration-none">

                                            Forgot Password?

                                        </a>

                                    </div>

                                    <button class="btn btn-login w-100">

                                        Sign In

                                    </button>

                                </form>

                                <div class="text-center mt-4">

                                    Don't have an account?

                                    <a href="register.php" class="text-decoration-none fw-semibold">

                                        Create Restaurant

                                    </a>

                                </div>

                                <hr class="my-4">

                                <div class="alert alert-info mb-0">

                                    Login using the email and password
                                    you created during registration.

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <script>

            const form = document.querySelector('form');

            const btn = form.querySelector('button');

            form.addEventListener('submit', () => {

                btn.disabled = true;

                btn.innerHTML = `
    <span class="spinner-border spinner-border-sm"></span>
    Signing In...
    `;

            });

        </script>

</body>

</html>