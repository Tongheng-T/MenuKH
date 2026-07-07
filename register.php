<?php
/**
 * ==========================================================
 * MenuKH
 * Digital QR Menu Platform
 * ----------------------------------------------------------
 * File : register.php
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
    redirect('owner/dashboard.php');
}

/*
|--------------------------------------------------------------------------
| Default Variables
|--------------------------------------------------------------------------
*/

$errors = [];

$form = [
    'restaurant_name' => '',
    'owner_name' => '',
    'email' => ''
];

/*
|--------------------------------------------------------------------------
| Handle Register
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF Protection
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request.';
    }

    // Clean Input
    $form['restaurant_name'] = clean($_POST['restaurant_name'] ?? '');
    $form['owner_name'] = clean($_POST['owner_name'] ?? '');
    $form['email'] = strtolower(clean($_POST['email'] ?? ''));

    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if ($form['restaurant_name'] === '') {
        $errors[] = 'Restaurant name is required.';
    }

    if ($form['owner_name'] === '') {
        $errors[] = 'Owner name is required.';
    }

    if (!valid_email($form['email'])) {
        $errors[] = 'Invalid email address.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($password !== $confirm) {
        $errors[] = 'Password confirmation does not match.';
    }

    /*
    |--------------------------------------------------------------------------
    | Check Existing Email
    |--------------------------------------------------------------------------
    */

    $users = JsonDB::read(USERS_FILE);

    foreach ($users as $user) {

        if (
            isset($user['email']) &&
            strtolower($user['email']) === $form['email']
        ) {
            $errors[] = 'Email already exists.';
            break;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Save
    |--------------------------------------------------------------------------
    */

    if (empty($errors)) {

        $restaurantId = 'rst_' . substr(JsonDB::uuid(), 0, 12);
        $userId = 'usr_' . substr(JsonDB::uuid(), 0, 12);

        $restaurantSlug = JsonDB::slug(
            $form['restaurant_name']
        );

        $user = [
            'id' => $userId,

            'restaurant_id' => $restaurantId,

            'restaurant_slug' => $restaurantSlug,

            'role' => 'owner',

            'status' => 'active',

            'plan' => 'free',

            'restaurant_name' => $form['restaurant_name'],

            'owner_name' => $form['owner_name'],

            'email' => $form['email'],

            'password' => password_hash(
                $password,
                PASSWORD_DEFAULT
            ),

            'created_at' => date('Y-m-d H:i:s'),

            'updated_at' => date('Y-m-d H:i:s')
        ];
        /*
|--------------------------------------------------------------------------
| Save User
|--------------------------------------------------------------------------
*/

        $users[] = $user;

        JsonDB::write(
            USERS_FILE,
            $users
        );

        /*
        |--------------------------------------------------------------------------
        | Create Restaurant Folder
        |--------------------------------------------------------------------------
        */

        $restaurantFolder = RESTAURANT_PATH . '/' . $restaurantId;

        if (!is_dir($restaurantFolder)) {
            mkdir($restaurantFolder, 0755, true);
        }

        /*
        |--------------------------------------------------------------------------
        | Restaurant Information
        |--------------------------------------------------------------------------
        */

        $restaurantInfo = [

            'id' => $restaurantId,

            'slug' => $restaurantSlug,

            'name' => $form['restaurant_name'],

            'owner' => $form['owner_name'],

            'email' => $form['email'],

            'phone' => '',

            'address' => '',

            'description' => '',

            'logo' => '',

            'cover' => '',

            'theme' => '#2563EB',

            'currency' => '$',

            'plan' => 'free',

            'status' => 'active',

            'created_at' => date('Y-m-d H:i:s')
        ];

        JsonDB::write(
            $restaurantFolder . '/info.json',
            $restaurantInfo
        );

        /*
        |--------------------------------------------------------------------------
        | Default JSON Files
        |--------------------------------------------------------------------------
        */

        JsonDB::write(
            $restaurantFolder . '/menu.json',
            []
        );

        JsonDB::write(
            $restaurantFolder . '/categories.json',
            []
        );

        JsonDB::write(
            $restaurantFolder . '/orders.json',
            []
        );

        JsonDB::write(
            $restaurantFolder . '/tables.json',
            []
        );

        JsonDB::write(
            $restaurantFolder . '/settings.json',
            [
                'theme' => '#2563EB',
                'language' => 'en',
                'ads' => true,
                'currency' => '$'
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Login Automatically
        |--------------------------------------------------------------------------
        */

        $_SESSION['user'] = [

            'id' => $user['id'],

            'restaurant_id' => $restaurantId,

            'restaurant_slug' => $restaurantSlug,

            'role' => 'owner',

            'name' => $form['owner_name'],

            'restaurant_name' => $form['restaurant_name'],

            'email' => $form['email']

        ];

        setFlash(
            'success',
            'Welcome to MenuKH 🎉'
        );

        redirect('owner/dashboard.php');

    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | <?= APP_NAME ?></title>

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

        .register-card {

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

            color: white;

            padding: 60px;

            height: 100%;

        }

        .left-side h1 {

            font-weight: 700;

            font-size: 42px;

        }

        .left-side p {

            opacity: .9;

            margin-top: 20px;

            line-height: 1.8;

        }

        .right-side {

            padding: 50px;

        }

        .logo {

            font-size: 34px;

            font-weight: 800;

            color: white;

        }

        .logo span {

            color: #FDBA74;

        }

        .form-control {

            border-radius: 14px;

            padding: 14px;

        }

        .btn-register {

            background: var(--primary);

            color: #fff;

            border-radius: 14px;

            padding: 14px;

            font-weight: 600;

        }

        .btn-register:hover {

            background: #1D4ED8;

            color: white;

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

                <div class="register-card">

                    <div class="row g-0">

                        <div class="col-lg-5">

                            <div class="left-side">

                                <div class="logo">

                                    Menu<span>KH</span>

                                </div>

                                <h1 class="mt-5">

                                    Start Your Restaurant Online

                                </h1>

                                <p>

                                    Create your restaurant in minutes,
                                    generate QR Codes,
                                    manage menus,
                                    receive orders
                                    and grow your business.

                                </p>

                            </div>

                        </div>

                        <div class="col-lg-7">

                            <div class="right-side">

                                <h2 class="fw-bold">

                                    Create Restaurant

                                </h2>

                                <p class="text-secondary">

                                    It's free to get started.

                                </p>
                                <?php if (!empty($errors)): ?>

                                    <div class="alert alert-danger">

                                        <ul class="mb-0 ps-3">

                                            <?php foreach ($errors as $error): ?>

                                                <li><?= e($error) ?></li>

                                            <?php endforeach; ?>

                                        </ul>

                                    </div>

                                <?php endif; ?>

                                <form method="POST" autocomplete="off">

                                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Restaurant Name

                                        </label>

                                        <input type="text" class="form-control" name="restaurant_name"
                                            placeholder="Heng Heng Restaurant"
                                            value="<?= e($form['restaurant_name']) ?>" required>

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Owner Name

                                        </label>

                                        <input type="text" class="form-control" name="owner_name"
                                            placeholder="Tong Heng" value="<?= e($form['owner_name']) ?>" required>

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">

                                            Email Address

                                        </label>

                                        <input type="email" class="form-control" name="email"
                                            placeholder="you@example.com" value="<?= e($form['email']) ?>" required>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-6">

                                            <div class="mb-3">

                                                <label class="form-label">

                                                    Password

                                                </label>

                                                <input type="password" class="form-control" name="password"
                                                    placeholder="Minimum 8 characters" required>

                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="mb-3">

                                                <label class="form-label">

                                                    Confirm Password

                                                </label>

                                                <input type="password" class="form-control" name="confirm_password"
                                                    placeholder="Confirm password" required>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="form-check mb-4">

                                        <input class="form-check-input" type="checkbox" id="agree" required>

                                        <label class="form-check-label" for="agree">

                                            I agree to the Terms &
                                            Privacy Policy

                                        </label>

                                    </div>

                                    <button class="btn btn-register w-100">

                                        Create Restaurant

                                    </button>

                                </form>

                                <div class="text-center mt-4">

                                    Already have an account?

                                    <a href="login.php" class="text-decoration-none fw-semibold">

                                        Login

                                    </a>

                                </div>
                                <hr class="my-4">

                                <div class="row text-center">

                                    <div class="col-4">

                                        <h5 class="fw-bold text-primary mb-1">

                                            Free

                                        </h5>

                                        <small class="text-muted">

                                            Plan

                                        </small>

                                    </div>

                                    <div class="col-4">

                                        <h5 class="fw-bold text-success mb-1">

                                            QR

                                        </h5>

                                        <small class="text-muted">

                                            Unlimited

                                        </small>

                                    </div>

                                    <div class="col-4">

                                        <h5 class="fw-bold text-warning mb-1">

                                            24/7

                                        </h5>

                                        <small class="text-muted">

                                            Access

                                        </small>

                                    </div>

                                </div>

                                <div class="alert alert-info mt-4 mb-0">

                                    <strong>Free Plan</strong><br>

                                    Your restaurant will be created with the
                                    <strong>Free Plan</strong>.

                                    Advertisement will be displayed inside
                                    your digital menu.

                                    You can upgrade to
                                    <strong>Premium</strong>
                                    anytime to remove advertisements.

                                </div>

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
        Creating Restaurant...
    `;

        });

    </script>
    </script>

</body>

</html>