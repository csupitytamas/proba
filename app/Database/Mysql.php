<?php

namespace App\Database;

use Exception;
use mysqli;
use mysqli_result;

class Mysql
{
    public const serverName = "localhost";
    public const username = "root";
    public const password = "";
    public const databaseName = "test";

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
        $this->executionCheck($result);

        if ($result->num_rows == 1) {
            return $result->fetch_object();
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
        $this->executionCheck($result);

        while ($row = $result->fetch_row()) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * @throws Exception
     */
    public function query(string $sql): mysqli_result|bool
    {
        if (empty($sql)) {
            throw new Exception("Empty query");
        }
        $result = $this->mysqli->query($sql);
        $this->executionCheck($result);

        return $result;
    }

    /**
     * @throws Exception
     */
    private function executionCheck($query): void
    {
        if (!$query) {
            throw new Exception('Execution failed');
        }
        if ($query->num_rows <= 0) {
            throw new Exception('Elkerdezesnek nincs eredmenye');
        }
    }

    /**
     * @throws Exception
     */
    public function insert(string $sql): bool
    {
        if (empty($sql)) {
            throw new Exception("Empty query");
        }
        $result = $this->mysqli->query($sql);
        if (!is_bool($result)) {
            throw new Exception("Hiba az insert során.");
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    public function update(string $sql): bool
    {
        if (empty($sql)) {
            throw new Exception("Empty query");
        }
        $result = $this->mysqli->query($sql);
        if (!is_bool($result)) {
            throw new Exception("Hiba az insert során.");
        }
        return $result;
    }
}