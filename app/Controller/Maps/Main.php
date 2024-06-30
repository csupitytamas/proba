<?php

namespace App\Controller\Maps;

use App\Database\Mysql;
use Exception;
use stdClass;

class Main
{
    public const MAIN_ID = 2;
    public Mysql $mysql;
    public object $parameters;

    public function __construct($parameters)
    {
        $this->mysql = new Mysql();
        $this->parameters = $parameters;
    }

    /**
     * Retrieves all data and returns it as a JSON string.
     *
     * @return bool|string The JSON string representation of the data, or false on failure.
     * @throws Exception
     */
    public function getAllData(): bool|string
    {
        $response = new StdClass();
        $response->wings = $this->getWingsOnField();
        $response->poles = $this->getPolesOnField();

        header('Content-Type: application/json');
        return json_encode($response);
    }

    /**
     * Add new wings to field.
     *
     * @return object The result of the MySQL query.
     * @throws Exception when the $_POST variable is not set.
     */
    public function newWings(): object
    {
        if (!isset($_POST)) {
            throw new Exception('POST method only allowed');
        }

        $sql = "
            INSERT INTO kitoro (neve,db,kep)
            VALUES (" . $_POST["neve"] . "," . $_POST["db"] . "," . $_POST["kep"] . ")
        ";

        $result = $this->mysql->query($sql);

        header('Content-Type: application/json');
        return $result;
    }
    public function newPoles()
    {
        try {
            $sqlPalyak = "SELECT `id` FROM palyak";
            $sqlKitoro = "SELECT `neve`, `db`, `kep` FROM kitoro";
            $queryKitoro = $this->mysql->query($sqlKitoro);

            $sqlRudak = "SELECT `neve`, `db`, `hossz`, `kep` FROM rudak";
            $queryRudak = $this->mysql->query($sqlRudak);

            header('Content-Type: application/json');
            return $queryRudak;
        } catch (Exception $exception) {
            die( $exception->getMessage() );
        }
    }
    public function deleteWings()
    {
        try {
            $sqlPalyak = "SELECT `id` FROM palyak";
            $sqlKitoro = "SELECT `neve`, `db`, `kep` FROM kitoro";
            $queryKitoro = $this->mysql->query($sqlKitoro);

            $sqlRudak = "SELECT `neve`, `db`, `hossz`, `kep` FROM rudak";
            $queryRudak = $this->mysql->query($sqlRudak);

            header('Content-Type: application/json');
            return $queryRudak;
        } catch (Exception $exception) {
            die( $exception->getMessage() );
        }
    }
    public function deletePoles()
    {
        try {
            $sqlPalyak = "SELECT `id` FROM palyak";
            $sqlKitoro = "SELECT `neve`, `db`, `kep` FROM kitoro";
            $queryKitoro = $this->mysql->query($sqlKitoro);

            $sqlRudak = "SELECT `neve`, `db`, `hossz`, `kep` FROM rudak";
            $queryRudak = $this->mysql->query($sqlRudak);

            header('Content-Type: application/json');
            return $queryRudak;
        } catch (Exception $exception) {
            die( $exception->getMessage() );
        }
    }

    /**
     * Retrieves the information of the wings on the field.
     *
     * This method executes a SQL query to fetch the name, quantity, and image of the wings
     * from the "palyan" and "kitoro" tables based on the current MAIN_ID value.
     *
     * @return object An array containing the information of the wings on the field.
     * @throws Exception
     */
    private function getWingsOnField(): object
    {
        $sql = "
            SELECT `kitoro`.*
            FROM `palyan`
            LEFT JOIN `kitoro` ON `palyan`.`kitoro` = `kitoro`.`id`
            WHERE `palyan`.`palya` = " . self::MAIN_ID . "
            AND `palyan`.`kitoro` IS NOT NULL";

        return $this->mysql->queryObject($sql);
    }

    /**
     * Retrieves the information of the poles on the field.
     *
     * This method executes a SQL query to fetch the name, quantity, and image of the poles
     * from the "palyan" and "rudak" tables based on the current MAIN_ID value.
     *
     * @return object An array containing the information of the poles on the field.
     * @throws Exception
     */
    private function getPolesOnField(): object
    {
        $sql = "
            SELECT `rudak`.*
            FROM `palyan`
            LEFT JOIN `rudak` ON `palyan`.`rudak` = `rudak`.`id`
            WHERE `palyan`.`palya` = " . self::MAIN_ID . "
            AND `palyan`.`rudak` IS NOT NULL";

        return $this->mysql->queryObject($sql);
    }
}