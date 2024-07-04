<?php

namespace App\Controller\Maps;

use App\Database\Mysql;
use Exception;
use stdClass;
class Storage extends AbstractMaps
{
    public const FIELD_NAME = 'raktar';
    public Mysql $mysql;
    public object $parameters;
    private int $fieldId;

    /**
     * @throws Exception
     */
    public function __construct($parameters)
    {
        $this->mysql = new Mysql();
        $this->fieldId = $this->getFieldId();
        $this->parameters = $parameters;
    }

    /**
     * Get the main arena id from palyak
     *
     * @return string       The id of main arena
     * @throws Exception    Sql exception
     */
    protected function getFieldId(): string
    {
        $sql = "
            SELECT `id`
            FROM palyak
            WHERE `neve` = '" . self::FIELD_NAME . "'";

        $result = $this->mysql->queryObject($sql);
        return $result->id;
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
        // get all poles
        // get all wings

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
        if (!isset($_POST['kitoro']) ||!isset($_POST['rudak']) || !isset($_POST['palya'])) {
            throw new Exception('Nincs megfelelő posztolt adat a mentéshez.');
        }

        $sql = "
            INSERT INTO palyan (kitoro,rudak,palya)
            VALUES (" . $_POST["kitoro"] . "," . $_POST["rudak"] . "," . $_POST["palya"] . ")
        ";

        $result = $this->mysql->queryObject($sql);

        header('Content-Type: application/json');
        return $result;
    }
    public function newPoles()
    {
        try {
            $sqlPalyak = "SELECT `id` FROM palyak";
            $sqlKitoro = "SELECT `neve`, `db`, `kep` FROM kitoro";
            $queryKitoro = $this->mysql->queryObject($sqlKitoro);

            $sqlRudak = "SELECT `neve`, `db`, `hossz`, `kep` FROM rudak";
            $queryRudak = $this->mysql->queryObject($sqlRudak);

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
            $queryKitoro = $this->mysql->queryObject($sqlKitoro);

            $sqlRudak = "SELECT `neve`, `db`, `hossz`, `kep` FROM rudak";
            $queryRudak = $this->mysql->queryObject($sqlRudak);

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
            $queryKitoro = $this->mysql->queryObject($sqlKitoro);

            $sqlRudak = "SELECT `neve`, `db`, `hossz`, `kep` FROM rudak";
            $queryRudak = $this->mysql->queryObject($sqlRudak);

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
    protected function getWingsOnField(): object
    {
        $sql = "
            SELECT `kitoro`.*
            FROM `palyan`
            LEFT JOIN `kitoro` ON `palyan`.`kitoro` = `kitoro`.`id`
            WHERE `palyan`.`palya` = " . $this->fieldId . "
            AND `palyan`.`kitoro` IS NOT NULL";

        foreach ( $this->parameters as $key => $value ) {
            switch ($key) {
                case 'neve':
                    $sql .= "AND `kitoro`.`neve` = '" . $value . "'";
                    break;
                case 'db':
                    $sql .= "AND `kitoro`.`db` = '" . $value . "'";
                    break;
            }
        }

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
    protected function getPolesOnField(): object
    {
        $sql = "
            SELECT `rudak`.*
            FROM `palyan`
            LEFT JOIN `rudak` ON `palyan`.`rudak` = `rudak`.`id`
            WHERE `palyan`.`palya` = " . $this->fieldId . "
            AND `palyan`.`rudak` IS NOT NULL";

        foreach ( $this->parameters as $key => $value ) {
            switch ($key) {
                case 'name':
                    $sql .= "AND `rudak`.`neve` = '" . $value . "'";
                    break;
                case 'db':
                    $sql .= "AND `rudak`.`db` = '" . $value . "'";
                    break;
                case 'hossz':
                    $sql .= "AND `rudak`.`hossz` = '" . $value . "'";
                    break;
            }
        }
        return $this->mysql->queryObject($sql);
    }
}