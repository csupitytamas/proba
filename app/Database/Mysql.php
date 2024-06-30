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
     * Executes a SQL query and returns the result as an object.
     *
     * @param string $sql The SQL query to be executed.
     *
     * @return object The result of the query as an object.
     *
     * @throws Exception If the execution of the query fails or if there are no results.
     */
    public function queryObject(string $sql): object
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

    /**
     * Queries the database and returns the result as an array.
     *
     * @param string $sql The SQL query to execute.
     *
     * @return array The result of the query as an associative array.
     * @throws Exception If the query execution failed or there is no result.
     *
     */
    public function queryArray(string $sql): array
    {
        $data = [];
        $result = $this->mysqli->query($sql);
        if (!$result) {
            throw new Exception('Execution failed');
        }
        if ($result->num_rows <= 0) {
            throw new Exception('Elkerdezesnek nincs eredmenye');
        }

        while ($row = $result->fetch_row()) {
            $data[] = $row;
        }

        return $data;
    }
}