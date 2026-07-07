<?php

class JsonDB
{
    /**
     * Read JSON File
     */
    public static function read(string $file): array
    {
        if (!file_exists($file)) {
            return [];
        }

        $json = file_get_contents($file);

        if (trim($json) === '') {
            return [];
        }

        $data = json_decode($json, true);

        return is_array($data) ? $data : [];
    }

    /**
     * Write JSON File
     */
    public static function write(string $file, array $data): bool
    {
        $json = json_encode(
            $data,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES
        );

        return file_put_contents(
            $file,
            $json,
            LOCK_EX
        ) !== false;
    }

    /**
     * Insert Record
     */
    public static function insert(string $file, array $row): bool
    {
        $data = self::read($file);

        $data[] = $row;

        return self::write($file, $data);
    }

    /**
     * Find First Record
     */
    public static function find(string $file, callable $callback): ?array
    {
        $data = self::read($file);

        foreach ($data as $row) {

            if ($callback($row)) {
                return $row;
            }

        }

        return null;
    }

    /**
     * Update Records
     */
    public static function update(
        string $file,
        callable $callback
    ): bool {

        $data = self::read($file);

        foreach ($data as $index => $row) {

            $data[$index] = $callback($row);

        }

        return self::write($file, $data);
    }

    /**
     * Delete Records
     */
    public static function delete(
        string $file,
        callable $callback
    ): bool {

        $data = array_values(array_filter(

            self::read($file),

            fn($row) => !$callback($row)

        ));

        return self::write($file, $data);
    }

    /**
     * Count Records
     */
    public static function count(string $file): int
    {
        return count(
            self::read($file)
        );
    }

    /**
     * Exists
     */
    public static function exists(
        string $file,
        callable $callback
    ): bool {

        return self::find(
            $file,
            $callback
        ) !== null;

    }

    /**
     * UUID
     */
    public static function uuid(): string
    {
        return bin2hex(
            random_bytes(16)
        );
    }

    /**
     * Slug
     */
    public static function slug(string $text): string
    {
        $slug = strtolower(trim($text));

        $slug = preg_replace(
            '/[^a-z0-9]+/',
            '-',
            $slug
        );

        return trim($slug, '-');
    }
}