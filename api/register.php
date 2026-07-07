<?php

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/json.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/security.php';

if (isLoggedIn()) {
    redirect('owner/dashboard.php');
}

$errors = [];

$form = [
    'restaurant_name' => '',
    'owner_name'      => '',
    'email'           => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request.';
    }

    $form['restaurant_name'] = clean($_POST['restaurant_name'] ?? '');
    $form['owner_name']      = clean($_POST['owner_name'] ?? '');
    $form['email']           = strtolower(clean($_POST['email'] ?? ''));

    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

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

    if (empty($errors)) {

        $restaurantId = 'rst_' . substr(JsonDB::uuid(), 0, 12);
        $userId       = 'usr_' . substr(JsonDB::uuid(), 0, 12);

        $restaurantFolder = RESTAURANT_PATH . '/' . $restaurantId;

        if (!is_dir($restaurantFolder)) {
            mkdir($restaurantFolder, 0755, true);
        }

        $newUser = [

            'id' => $userId,

            'restaurant_id' => $restaurantId,

            'restaurant_name' => $form['restaurant_name'],

            'owner_name' => $form['owner_name'],

            'email' => $form['email'],

            'password' => password_hash(
                $password,
                PASSWORD_DEFAULT
            ),

            'role' => 'owner',

            'plan' => 'free',

            'status' => 'active',

            'created_at' => date('Y-m-d H:i:s')

        ];

        $users[] = $newUser;

        JsonDB::write(
            USERS_FILE,
            $users
        );

        JsonDB::write(
            $restaurantFolder . '/info.json',
            [
                'id' => $restaurantId,
                'name' => $form['restaurant_name'],
                'owner' => $form['owner_name'],
                'email' => $form['email'],
                'theme' => '#2563EB'
            ]
        );

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
                'ads' => true,
                'currency' => '$'
            ]
        );

        $_SESSION['user'] = [
            'id' => $userId,
            'restaurant_id' => $restaurantId,
            'name' => $form['owner_name'],
            'role' => 'owner'
        ];

        setFlash(
            'success',
            'Welcome to MenuKH'
        );

        redirect('owner/dashboard.php');
    }
}

?>