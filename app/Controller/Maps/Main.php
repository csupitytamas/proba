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
        return $this->jsonResponse($response);
    }

    /**
     * Add new wings to field.
     *
     * @return string The result of the MySQL query.
     * @throws Exception when the $_POST variable is not set.
     */
    public function addWingsToMain(): string
    {
        try {
           $this->getPostCheck();

            $sql = "
            INSERT INTO kitoro (name_hu, name_en,db,kep)
            VALUES (" . $_POST["name_hu"] . "," . $_POST["name_en"] . "," . $_POST["db"] . "," . $_POST["kep"] . ")
        ";

            $result = $this->mysql->queryObject($sql);

            header('Content-Type: application/json');
            return $this->jsonResponse($result);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }

    /**
     * @return object|string
     */
    public function addPolesOnMain(): object|string
    {
        try {
            $this->getPostCheck();

            $sql = "
                INSERT INTO rudak (name_hu, name_en,db,, hossz, kep)
                VALUES (" . $_POST['name_hu'] . "," . $_POST['name_en'] . "," . $_POST['db'] . "," .$_POST['hossz'] . "," . $_POST['kep'] . ")
            ";

            $result = $this->mysql->queryObject($sql);

            header('Content-Type: application/json');
            return $this->jsonResponse($result);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }

    /**
     * @return false|string
     */
    public function deleteWings(): false|string
    {
        try {
            $this->getPostCheck();

            $sql = "
                DELETE FROM palyan WHERE rudak IS NULL AND kitoro = " . $_POST["id"] . "
            ";

            $result = $this->mysql->query($sql);

            if ($result) {
                throw new Exception("Delete don't execute");
            }

            header('Content-Type: application/json');
            return json_encode([
                'status' => 'success',
                'deleted_id' => $_POST["id"]
            ]);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }

    /**
     * @return false|string
     */
    public function deletePoles(): false|string
    {
        try {
            $this->getPostCheck();

            $sql = "
                DELETE FROM palyan WHERE kitoro IS NULL AND rudak = " . $_POST["id"] . "
            ";

            $result = $this->mysql->query($sql);

            if ($result) {
                throw new Exception("Delete don't execute");
            }

            header('Content-Type: application/json');
            return json_encode([
                'status' => 'success',
                'deleted_id' => $_POST["id"]
            ]);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
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

    /**
     * @param $message
     *
     * @return string
     */
    private function getExceptionFormat($message): string
    {
        $data = [
            'status' => 'error',
            'message' => $message
        ];
        return $this->jsonResponse($data);
    }

    /**
     * @param     $data
     * @param int $status
     *
     * @return false|string
     */
    private function jsonResponse($data, int $status = 200): false|string
    {
        header('Content-Type: application/json');
        // TODO add bad request header status code
        return json_encode($data);
    }

    /**
     * @return void
     * @throws Exception
     */
    private function getPostCheck(): void
    {
        if (!isset($_POST)) {
            throw new Exception('POST method only allowed');
        }
    }
}