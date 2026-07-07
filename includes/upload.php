<?php
/**
 * ==========================================================
 * MenuKH
 * Upload Engine
 * ----------------------------------------------------------
 * File : includes/upload.php
 * Version : 1.0.0
 * ==========================================================
 */

class Upload
{

    /*
    |--------------------------------------------------------------------------
    | Upload Image
    |--------------------------------------------------------------------------
    */

    public static function image(
        array $file,
        string $directory,
        array $allowed = ['jpg', 'jpeg', 'png', 'webp']
    ): ?string
    {

        if (
            empty($file['tmp_name']) ||
            $file['error'] !== UPLOAD_ERR_OK
        ) {

            return null;

        }

        if (!is_dir($directory)) {

            mkdir($directory, 0755, true);

        }

        $extension = strtolower(
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            )
        );

        if (!in_array($extension, $allowed, true)) {

            return null;

        }

        $filename = uniqid('img_', true) . '.' . $extension;

        $destination = rtrim($directory, '/') . '/' . $filename;

        if (!move_uploaded_file(
            $file['tmp_name'],
            $destination
        )) {

            return null;

        }

        return $destination;

    }
    /*
    |--------------------------------------------------------------------------
    | Validate Image
    |--------------------------------------------------------------------------
    */

    public static function validateImage(
        array $file,
        int $maxSize = 5 * 1024 * 1024
    ): array
    {

        if (empty($file['tmp_name'])) {

            return [
                'success' => false,
                'message' => 'No file selected.'
            ];

        }

        if ($file['error'] !== UPLOAD_ERR_OK) {

            return [
                'success' => false,
                'message' => 'Upload failed.'
            ];

        }

        if ($file['size'] > $maxSize) {

            return [
                'success' => false,
                'message' => 'Image size must not exceed 5MB.'
            ];

        }

        $allowedMimeTypes = [

            'image/jpeg',
            'image/png',
            'image/webp'

        ];

        $mimeType = mime_content_type(
            $file['tmp_name']
        );

        if (!in_array($mimeType, $allowedMimeTypes, true)) {

            return [
                'success' => false,
                'message' => 'Invalid image format.'
            ];

        }

        return [
            'success' => true,
            'message' => 'OK'
        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Restaurant Upload Path
    |--------------------------------------------------------------------------
    */

    public static function restaurantPath(
        string $folder
    ): string
    {

        return ROOT_PATH .
            '/uploads/restaurants/' .
            restaurantId() .
            '/' .
            trim($folder, '/');

    }

    /*
    |--------------------------------------------------------------------------
    | Upload Category Image
    |--------------------------------------------------------------------------
    */

    public static function uploadCategoryImage(
        array $file
    ): ?string
    {

        $validation = self::validateImage($file);

        if (!$validation['success']) {

            return null;

        }

        return self::image(

            $file,

            self::restaurantPath('categories')

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Upload Menu Image
    |--------------------------------------------------------------------------
    */

    public static function uploadMenuImage(
        array $file
    ): ?string
    {

        $validation = self::validateImage($file);

        if (!$validation['success']) {

            return null;

        }

        return self::image(

            $file,

            self::restaurantPath('menus')

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Upload Restaurant Logo
    |--------------------------------------------------------------------------
    */

    public static function uploadLogo(
        array $file
    ): ?string
    {

        $validation = self::validateImage($file);

        if (!$validation['success']) {

            return null;

        }

        return self::image(

            $file,

            self::restaurantPath('logo')

        );

    }
        /*
    |--------------------------------------------------------------------------
    | Delete Image
    |--------------------------------------------------------------------------
    */

    public static function deleteImage(?string $path): bool
    {

        if (
            empty($path) ||
            !file_exists($path)
        ) {

            return false;

        }

        return unlink($path);

    }

    /*
    |--------------------------------------------------------------------------
    | Replace Image
    |--------------------------------------------------------------------------
    */

    public static function replaceImage(
        ?string $oldImage,
        array $newFile,
        string $folder
    ): ?string
    {

        $newImage = self::image(
            $newFile,
            self::restaurantPath($folder)
        );

        if (!$newImage) {

            return null;

        }

        if (!empty($oldImage)) {

            self::deleteImage($oldImage);

        }

        return $newImage;

    }

    /*
    |--------------------------------------------------------------------------
    | File Exists
    |--------------------------------------------------------------------------
    */

    public static function exists(?string $path): bool
    {

        return !empty($path)
            && file_exists($path);

    }

    /*
    |--------------------------------------------------------------------------
    | Get Extension
    |--------------------------------------------------------------------------
    */

    public static function extension(
        string $filename
    ): string
    {

        return strtolower(
            pathinfo(
                $filename,
                PATHINFO_EXTENSION
            )
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Get File Size
    |--------------------------------------------------------------------------
    */

    public static function fileSize(
        string $path
    ): int
    {

        if (!file_exists($path)) {

            return 0;

        }

        return filesize($path);

    }

}