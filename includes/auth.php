<?php

/**
 * =====================================================
 * MenuKH Authentication Helper
 * Version : 1.0.0
 * =====================================================
 */

/**
 * Check Login
 */
function checkLogin(): bool
{
    return isset($_SESSION['user']);
}

/**
 * Get Current User
 */
function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

/**
 * Login User
 */
function login(array $user): void
{
    session_regenerate_id(true);

    $_SESSION['user'] = [
        'id'              => $user['id'],
        'restaurant_id'   => $user['restaurant_id'],
        'restaurant_name' => $user['restaurant_name'],
        'owner_name'      => $user['owner_name'],
        'email'           => $user['email'],
        'role'            => $user['role'],
        'plan'            => $user['plan']
    ];
}

/**
 * Logout
 */
function logout(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 3600,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

/**
 * Require Login
 */
function requireLogin(): void
{
    if (!checkLogin()) {
        redirect('../login.php');
    }
}

/**
 * Require Owner
 */
function requireOwner(): void
{
    requireLogin();

    if (currentUser()['role'] !== 'owner') {

        setFlash(
            'danger',
            'Access denied.'
        );

        redirect('../login.php');
    }
}

/**
 * Require Admin
 */
function requireAdmin(): void
{
    requireLogin();

    if (currentUser()['role'] !== 'admin') {

        setFlash(
            'danger',
            'Administrator only.'
        );

        redirect('../login.php');
    }
}