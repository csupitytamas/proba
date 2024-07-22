<?php

namespace App\Controller\Maps;

use App\Controller\Entities\Kitoro;
use App\Controller\Traits\Response;
use App\Database\Mysql;
use Exception;
use stdClass;

class Main extends AbstractMaps
{
    use Response;

    public const FIELD_NAME = 'main';
    public Mysql $mysql;
    public object $parameters;
    public int $fieldId;

    /**
     * Constructor method for the class.
     *
     * @param object $parameters An array of parameters for the constructor.
     *
     * @return void
     */
    public function __construct(object $parameters)
    {
        $this->mysql = new Mysql();
        $this->parameters = $parameters;
        $this->fieldId = $this->getFieldId();
    }

    /**
     * Sets the ID based on a SQL query to retrieve the ID from the "palyak" table.
     *
     * @return string Returns the SQL query to retrieve the ID.
     */
    protected function setId(): string
    {
        return "
            SELECT `id`
            FROM palyak
            WHERE `neve` = '" . self::FIELD_NAME . "'
        ";
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

        return $this->jsonResponse($response);
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

        return $this->mysql->queryObject($sql, false);
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

        return $this->mysql->queryObject($sql, false);
    }

    /**
     * Adds wings to the "kitoro" table.
     *
     * @return string The SQL query for inserting the wings.
     * @throws Exception
     */
    protected function addWings(): string
    {
        if (!isset($_POST['id']) && !isset($_POST['db'])) {
            throw new Exception('No data to add wing');
        }

        $parameter = new StdClass();
        $parameter->id = $_POST['id'];
        $wingsEntity = new Kitoro($parameter);
        $wing = json_decode($wingsEntity->get());

        if (!$wing) {
            throw new Exception('Wing not found!');
        }
        return "
            INSERT INTO palyan (kitoro, rudak, palya, db)
            VALUES (" . $wing->id . ", null ," . $this->fieldId . "," . $_POST['db'] . ") 
        ";
    }

    /**
     * Inserts data into the "rudak" table based on user input.
     *
     * @return string Returns the SQL query to add poles.
     */
    protected function addPoles(): string
    {
        return "
            INSERT INTO palyan (kitoro, rudak, palya, db, hossz)
            VALUES (" . null . "," . $_POST['rudak'] . "," . $this->fieldId . "," . $_POST['db'] . "," .$_POST['hossz'] . ")
        ";
    }

    /**
     * Deletes the wings from the "palyan" table based on specific conditions.
     *
     * @return array Returns the SQL query to delete the wings.
     * @throws Exception
     */
    public function deleteWings(): array
    {
        $deletedWings = [];
        if (is_array($_POST['wings'])) {
            foreach ($_POST['wings'] as $wingId) {
                $deletedWings[] = "DELETE FROM palyan WHERE palya = " . $this->fieldId . " AND rudak IS NULL AND kitoro = '" . $wingId . "'";
            }
        }
        else {
            throw new Exception('Nem t0mb az adathalmaz');
        }
        return $deletedWings;
    }

    /**
     * Deletes the poles from the "palyan" table based on specific conditions.
     *
     * @return array Returns an array of SQL queries to delete the poles.
     * @throws Exception Throws an exception if the input is not an array.
     */
    public function deletePoles(): array
    {
        $deletedPoles = [];
        if (is_array($_POST['poles'])) {
            foreach ($_POST['poles'] as $poleId) {
                $deletedPoles[] = "DELETE FROM palyan WHERE palya = " . $this->fieldId . " AND kitoro IS NULL AND rudak = '" . $poleId . "'";
            }
        }
        else {
            throw new Exception('Nem t0mb az adathalmaz');
        }
        return $deletedPoles;
    }
}