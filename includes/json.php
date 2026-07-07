<?php
/**
 * ==========================================================
 * MenuKH
 * JSON Database Engine
 * ----------------------------------------------------------
 * File : includes/json.php
 * Version : 1.0.0
 * ==========================================================
 */

class JsonDB
{

    /*
    |--------------------------------------------------------------------------
    | Read JSON File
    |--------------------------------------------------------------------------
    */

    public static function read(string $file): array
    {

        if (!file_exists($file)) {

            return [];

        }

        $json = file_get_contents($file);

        if (!$json) {

            return [];

        }

        $data = json_decode($json, true);

        return is_array($data)
            ? $data
            : [];

    }

    /*
    |--------------------------------------------------------------------------
    | Write JSON File
    |--------------------------------------------------------------------------
    */

    public static function write(
        string $file,
        array $data
    ): bool
    {

        $directory = dirname($file);

        if (!is_dir($directory)) {

            mkdir($directory, 0755, true);

        }

        return file_put_contents(

            $file,

            json_encode(

                $data,

                JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES

            ),

            LOCK_EX

        ) !== false;

    }

    /*
    |--------------------------------------------------------------------------
    | Generate UUID
    |--------------------------------------------------------------------------
    */

    public static function uuid(): string
    {

        return bin2hex(random_bytes(16));

    }

    /*
    |--------------------------------------------------------------------------
    | Generate ID
    |--------------------------------------------------------------------------
    */

    public static function makeId(
        string $prefix = ''
    ): string
    {

        return $prefix .
            substr(
                self::uuid(),
                0,
                12
            );

    }
    /*
    |--------------------------------------------------------------------------
    | Count Records
    |--------------------------------------------------------------------------
    */

    public static function count(string $file): int
    {
        return count(
            self::read($file)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Find By ID
    |--------------------------------------------------------------------------
    */

    public static function find(
        string $file,
        string $id
    ): ?array
    {
        foreach (self::read($file) as $row) {

            if (
                isset($row['id']) &&
                $row['id'] === $id
            ) {

                return $row;

            }

        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Find By Field
    |--------------------------------------------------------------------------
    */

    public static function findBy(
        string $file,
        string $field,
        $value
    ): ?array
    {
        foreach (self::read($file) as $row) {

            if (
                isset($row[$field]) &&
                $row[$field] == $value
            ) {

                return $row;

            }

        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Exists
    |--------------------------------------------------------------------------
    */

    public static function exists(
        string $file,
        string $field,
        $value
    ): bool
    {
        return self::findBy(
            $file,
            $field,
            $value
        ) !== null;
    }

    /*
    |--------------------------------------------------------------------------
    | Where
    |--------------------------------------------------------------------------
    */

    public static function where(
        string $file,
        callable $callback
    ): array
    {
        return array_values(
            array_filter(
                self::read($file),
                $callback
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | First Record
    |--------------------------------------------------------------------------
    */

    public static function first(
        string $file
    ): ?array
    {
        $rows = self::read($file);

        return $rows[0] ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | Last Record
    |--------------------------------------------------------------------------
    */

    public static function last(
        string $file
    ): ?array
    {
        $rows = self::read($file);

        if (empty($rows)) {

            return null;

        }

        return $rows[array_key_last($rows)];
    }
        /*
    |--------------------------------------------------------------------------
    | Insert Record
    |--------------------------------------------------------------------------
    */

    public static function insert(
        string $file,
        array $record
    ): bool
    {
        $rows = self::read($file);

        if (!isset($record['id'])) {
            $record['id'] = self::makeId();
        }

        $now = date('Y-m-d H:i:s');

        if (!isset($record['created_at'])) {
            $record['created_at'] = $now;
        }

        $record['updated_at'] = $now;

        $rows[] = $record;

        return self::write($file, $rows);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Record
    |--------------------------------------------------------------------------
    */

    public static function update(
        string $file,
        string $id,
        array $data
    ): bool
    {
        $rows = self::read($file);

        foreach ($rows as &$row) {

            if (
                isset($row['id']) &&
                $row['id'] === $id
            ) {

                foreach ($data as $key => $value) {

                    $row[$key] = $value;

                }

                $row['updated_at'] = date('Y-m-d H:i:s');

                break;

            }

        }

        return self::write($file, $rows);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Record
    |--------------------------------------------------------------------------
    */

    public static function delete(
        string $file,
        string $id
    ): bool
    {
        $rows = array_filter(

            self::read($file),

            function ($row) use ($id) {

                return !isset($row['id']) ||
                       $row['id'] !== $id;

            }

        );

        return self::write(
            $file,
            array_values($rows)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Order By
    |--------------------------------------------------------------------------
    */

    public static function orderBy(
        string $file,
        string $field,
        string $direction = 'ASC'
    ): array
    {
        $rows = self::read($file);

        usort($rows, function ($a, $b) use ($field, $direction) {

            $valueA = $a[$field] ?? null;
            $valueB = $b[$field] ?? null;

            if ($valueA == $valueB) {
                return 0;
            }

            if (strtoupper($direction) === 'DESC') {

                return ($valueA < $valueB) ? 1 : -1;

            }

            return ($valueA > $valueB) ? 1 : -1;

        });

        return $rows;
    }

}