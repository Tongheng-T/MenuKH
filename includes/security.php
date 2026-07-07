<?php

/**
 * =====================================================
 * MenuKH Security Helper
 * =====================================================
 */

/**
 * Generate CSRF Token
 */
function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF Token
 */
function verify_csrf(?string $token): bool
{
    if (
        !isset($_SESSION['csrf_token']) ||
        empty($token)
    ) {
        return false;
    }

    return hash_equals(
        $_SESSION['csrf_token'],
        $token
    );
}

/**
 * Clean Input
 */
function clean(?string $value): string
{
    return trim(strip_tags($value ?? ''));
}

/**
 * Validate Email
 */
function valid_email(string $email): bool
{
    return filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    ) !== false;
}

/**
 * Validate Password
 */
function valid_password(string $password): bool
{
    return strlen($password) >= 8;
}

/**
 * Validate Image Upload
 */
function valid_image(array $file): bool
{
    if (
        !isset($file['tmp_name']) ||
        $file['error'] !== UPLOAD_ERR_OK
    ) {
        return false;
    }

    $mime = mime_content_type(
        $file['tmp_name']
    );

    $allow = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    return in_array($mime, $allow, true);
}