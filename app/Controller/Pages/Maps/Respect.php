<?php

namespace App\Controller\Pages\Maps;

use App\Controller\Entities\Kitoro;
use App\Controller\Entities\Rudak;
use App\Controller\Traits\Response;
use App\Database\Mysql;
use Exception;
use stdClass;

class Respect extends AbstractMaps
{
    use Response;

    public const FIELD_NAME = 'respect';
    public Mysql $mysql;
    public object $parameters;
    private int $fieldId;

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
    public function getAllData(): bool|string|null
    {
        try {
            $response = new StdClass();
            $response->wings = $this->getWingsOnField();
            $response->poles = $this->getPolesOnField();

            return $this->jsonResponse($response);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage() . "|" . $exception->getFile() . "|" . $exception->getLine());
        }
    }

    /**
     * Retrieves the information of the wings on the field.
     *
     * This method executes a SQL query to fetch the name, quantity, and image of the wings
     * from the "palyan" and "kitoro" tables based on the current MAIN_ID value.
     *
     * @return object|null An array containing the information of the wings on the field.
     * @throws Exception
     */
    protected function getWingsOnField(): ?object
    {
        $sql = "
            SELECT `palyan`.`id`, `kitoro`.`name_hu`, `kitoro`.`name_en`, `palyan`.`db`, `kitoro`.`kep`
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
     * @return object|null An array containing the information of the poles on the field.
     * @throws Exception
     */
    protected function getPolesOnField(): ?object
    {
        $sql = "
            SELECT `palyan`.`id`, `rudak`.`name_hu`, `rudak`.`name_en`, `palyan`.`db`, `palyan`.`hossz`, `rudak`.`kep`
            FROM `palyan`
            LEFT JOIN `rudak` ON `palyan`.`rudak` = `rudak`.`id`
            WHERE `palyan`.`palya` = " . $this->fieldId . "
            AND `palyan`.`rudak` IS NOT NULL";

        return $this->mysql->queryObject($sql, false);
    }

    /**
     * Adds wings to the "kitoro" table.
     *
     * @return object The SQL query for inserting the wings.
     * @throws Exception
     */
    protected function addWings(): object
    {
        if (!isset($_POST['id']) && !isset($_POST['db'])) {
            throw new Exception('No data to add wing');
        }
        $response = new StdClass();
        $parameter = new StdClass();
        $parameter->id = $_POST['id'];
        $parameter->db = $_POST['db'];
        $wingsEntity = new Kitoro($parameter);
        $response->wing = json_decode($wingsEntity->get());
        $response->updatedField = $parameter;
        $response->updatedField->palya = $this->fieldId;

        if (!$response->wing) {
            throw new Exception('Wing not found!');
        }
        if ($response->wing->db - $parameter->db < 0) {
            throw new Exception('Not enough data to update storage');
        }

        if ($this->onField($this->fieldId, $response->wing->id)) {
            $response->type = 'update';
            $response->sql = "
                UPDATE palyan
                SET `db` = `db` + {$parameter->db}
                WHERE `kitoro` = {$response->wing->id}
                AND `rudak` IS NULL
                AND `palya` = {$this->fieldId}
            ";
        } else {
            $response->type = 'insert';
            $response->sql = "
                INSERT INTO palyan (kitoro, rudak, palya, db)
                VALUES (" . $response->wing->id . ", null ," . $this->fieldId . "," . $parameter->db . ") 
            ";
        }

        return $response;
    }

    /**
     * Inserts data into the "rudak" table based on user input.
     *
     * @return object Returns the SQL query to add poles.
     * @throws Exception
     */
    protected function addPoles(): object
    {
        if (!isset($_POST['id']) && !isset($_POST['db'])) {
            throw new Exception('No data to add pole');
        }
        $response = new StdClass();
        $parameter = new StdClass();
        $parameter->id = $_POST['id'];
        $parameter->db = $_POST['db'];
        $poleEntity = new Rudak($parameter);
        $response->pole = json_decode($poleEntity->get());
        $response->updatedField = $parameter;
        $response->updatedField->palya = $this->fieldId;

        if (!$response->pole) {
            throw new Exception('Pole not found!');
        }
        if ($response->pole->db - $parameter->db < 0) {
            throw new Exception('Not enough data to update storage' . $response->pole->db . "," . $parameter->db);
        }

        if ($this->onField($this->fieldId, $response->pole->id, false)) {
            $response->type = 'update';
            $response->sql = "
                UPDATE palyan
                SET `db` = `db` + {$parameter->db}
                WHERE `rudak` = {$response->pole->id}
                AND `kitoro` IS NULL
                AND `palya` = {$this->fieldId}
            ";
        } else {
            $response->type = 'insert';
            $response->sql = "
                INSERT INTO palyan (kitoro, rudak, palya, db, hossz)
                VALUES (null," . $response->pole->id . "," . $this->fieldId . "," . $parameter->db . "," . $response->pole->hossz . ") 
            ";
        }

        return $response;
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
                $object = new StdClass();
                $parameter = new StdClass();
                $parameter->id = $wingId;
                $object->updatedField = $this->get($parameter->id);
                $wingEntity = new Kitoro($object->updatedField);
                $object->wing = $wingEntity->get();
                $object->sql = "DELETE FROM palyan WHERE `id` = '" . $parameter->id . "'";

                $deletedWings[] = $object;
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
                $object = new StdClass();
                $parameter = new StdClass();
                $parameter->id = $poleId;
                $object->updatedField = $this->get($parameter->id);
                $poleEntity = new Rudak($parameter);
                $object->pole = $poleEntity->get();
                $object->sql = "DELETE FROM palyan WHERE `id` = '" . $parameter->id . "'";

                $deletedPoles[] = $object;
            }
        }
        else {
            throw new Exception('Nem t0mb az adathalmaz');
        }
        return $deletedPoles;
    }

    /**
     * Retrieves a specific record from the "palyan" table based on the provided id.
     *
     * @param  int  $id  The id of the record to retrieve.
     *
     * @return mixed|null Returns the retrieved record from the "palyan" table or null if no record is found.
     * @throws Exception if there is an error while retrieving the record.
     */
    public function get(int $id)
    {
        try {
            $sql = "
                SELECT *
                FROM `palyan`
                WHERE `id` = {$id}
            ";
            return $this->mysql->queryObject($sql);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}
