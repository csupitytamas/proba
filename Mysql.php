<?php

class Mysql
{
    public const serverName = "localhost";
    public const username = "root";
    public const password = "modmq888";
    public const databaseName = "myDB";

    private mysqli $mysqli;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->mysqli = new mysqli(self::serverName, self::username, self::password, self::databaseName);
        $this->mysqli->set_charset("utf8");

        if($this->mysqli->connect_error)
        {
            throw new Exception('Database connection failed: ' . $this->mysqli->connect_error);
        }
    }

    /**
     * @throws Exception
     */
    public function query($sql): array
    {
        $query = $this->mysqli->query($sql);
        if(!$query) {
            throw new Exception('Execution failed');
        }
        if($query->num_rows <= 0) {
            throw new Exception('Elkerdezesnek nincs eredmenye');
        }
        return $query->fetch_all( MYSQLI_ASSOC );
    }
}