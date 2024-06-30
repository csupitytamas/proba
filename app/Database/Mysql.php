<?php

namespace App\database;

use Exception;
use mysqli;

class Mysql
{
    public const serverName = "mysql";
    public const username = "root";
    public const password = "root";
    public const databaseName = "samorin";

    private mysqli $mysqli;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->mysqli = new mysqli(self::serverName, self::username, self::password, self::databaseName);
        $this->mysqli->set_charset("utf8");

        if ($this->mysqli->connect_error) {
            throw new Exception('Database connection failed: ' . $this->mysqli->connect_error);
        }
    }

    /**
     * @throws Exception
     */
    public function query($sql): object
    {
        $data = [];
        $result = $this->mysqli->query($sql);
        if (!$result) {
            throw new Exception('Execution failed');
        }
        if ($result->num_rows <= 0) {
            throw new Exception('Elkerdezesnek nincs eredmenye');
        }

        while ($row = $result->fetch_object()) {
            $data[] = $row;
        }

        return (object)$data;
    }
}