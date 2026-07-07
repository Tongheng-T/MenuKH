<?php

/**
 * =====================================================
 * MenuKH Configuration
 * =====================================================
 */

date_default_timezone_set('Asia/Phnom_Penh');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| App
|--------------------------------------------------------------------------
*/

define('APP_NAME', 'MenuKH');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/MenuKH');

/*
|--------------------------------------------------------------------------
| Paths
|--------------------------------------------------------------------------
*/

define('ROOT_PATH', dirname(__DIR__));

define('STORAGE_PATH', ROOT_PATH . '/storage');

define('UPLOAD_PATH', ROOT_PATH . '/uploads');

define('RESTAURANT_PATH', STORAGE_PATH . '/restaurants');

/*
|--------------------------------------------------------------------------
| JSON Files
|--------------------------------------------------------------------------
*/

define('USERS_FILE', STORAGE_PATH . '/users.json');

define('PLANS_FILE', STORAGE_PATH . '/plans.json');

define('ADS_FILE', STORAGE_PATH . '/ads.json');

define('SETTINGS_FILE', STORAGE_PATH . '/settings.json');

/*
|--------------------------------------------------------------------------
| Create Storage
|--------------------------------------------------------------------------
*/

$folders = [
    STORAGE_PATH,
    RESTAURANT_PATH,
    UPLOAD_PATH
];

foreach ($folders as $folder) {

    if (!is_dir($folder)) {

        mkdir($folder, 0755, true);

    }

}

$files = [
    USERS_FILE,
    PLANS_FILE,
    ADS_FILE,
    SETTINGS_FILE
];

foreach ($files as $file) {

    if (!file_exists($file)) {

        file_put_contents($file, '[]');

    }

}