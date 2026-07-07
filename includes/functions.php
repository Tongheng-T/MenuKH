<?php

/**
 * =====================================================
 * MenuKH Helper Functions
 * =====================================================
 */

/**
 * Escape HTML
 */
function e($value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect
 */
function redirect(string $url): void
{
    header("Location: {$url}");
    exit;
}

/**
 * Redirect Back
 */
function back(): void
{
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? APP_URL));
    exit;
}

/**
 * Current URL
 */
function url(string $path = ''): string
{
    return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
}

/**
 * Asset URL
 */
function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Generate Random String
 */
function randomString(int $length = 32): string
{
    return bin2hex(random_bytes($length / 2));
}

/**
 * Flash Message
 */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get Flash
 */
function flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];

    unset($_SESSION['flash']);

    return $flash;
}

/**
 * Old Input
 */
function old(string $key, $default = '')
{
    return $_POST[$key] ?? $default;
}


/**
 * Debug
 */
function dd($data): void
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit;
}




// ////////////////////////

/*
|--------------------------------------------------------------------------
| Get Flash Message
|--------------------------------------------------------------------------
*/

function getFlash(): ?array
{

    if (!isset($_SESSION['flash'])) {

        return null;

    }

    $flash = $_SESSION['flash'];

    unset($_SESSION['flash']);

    return $flash;

}

/*
|--------------------------------------------------------------------------
| Has Flash
|--------------------------------------------------------------------------
*/

function hasFlash(): bool
{

    return isset($_SESSION['flash']);

}

/*
|--------------------------------------------------------------------------
| Clear Flash
|--------------------------------------------------------------------------
*/

function clearFlash(): void
{

    unset($_SESSION['flash']);

}