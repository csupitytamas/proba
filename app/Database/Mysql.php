<?php

namespace app\Database;

use Exception;
use mysqli;
use mysqli_result;

class Mysql
{
    private mysqli $mysqli;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        require_once ('Credentials.php');
        $databaseCredentials = getDatabaseCredentials();
        $this->checkConnectionCredentials($databaseCredentials);
        $this->mysqli = new mysqli($databaseCredentials->host, $databaseCredentials->user, $databaseCredentials->password, $databaseCredentials->database);
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
    public function queryObject(string $sql, $objectsResponse = true): object
    {
        $data = [];
        $result = $this->mysqli->query($sql);
        $this->executionCheck($result);

        if ($result->num_rows == 1 && $objectsResponse) {
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

    /**
     * @throws Exception
     */
    public function delete(string $sql): bool
    {
        if (empty($sql)) {
            throw new Exception("Empty query");
        }
        $result = $this->mysqli->query($sql);
        if (!is_bool($result)) {
            throw new Exception("Hiba az delete során.");
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    public function executeAsTransaction(array $sqlCommands): void
    {
        $this->mysqli->begin_transaction();

        foreach ($sqlCommands as $command) {
            if (!$this->mysqli->query($command)) {
                $this->mysqli->rollback();
                throw new Exception('Hiba történt az SQL utasítás végrehajtása közben, az egész tranzakció visszagörgetésre kerül: ' . $this->mysqli->error);
            }
        }

        $this->mysqli->commit();
    }

    /**
     * Check the validity of connection credentials.
     *
     * @param object $credentials The connection credentials.
     *
     * @throws Exception When any of the connection credentials is null or empty.
     */
    private function checkConnectionCredentials(object $credentials): void
    {
        if (!isset($credentials->host) || empty($credentials->host)) {
            throw new Exception('Host is null or empty');
        }
        if (!isset($credentials->database) || empty($credentials->database)) {
            throw new Exception('Database name is null or empty');
        }
        if (!isset($credentials->user) || empty($credentials->user)) {
            throw new Exception('User name is null or empty');
        }
        if (!isset($credentials->password) || empty($credentials->password)) {
            throw new Exception('Password is null or empty');
        }
    }
}