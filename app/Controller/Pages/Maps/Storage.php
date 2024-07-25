<?php

namespace App\Controller\Pages\Maps;

use App\Controller\Traits\Response;
use App\Database\Mysql;
use Exception;
use stdClass;

class Storage extends AbstractMaps
{
    use Response;

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
    }

    /**
     * Sets the ID based on a SQL query to retrieve the ID from the "palyak" table.
     *
     * @return string Returns the SQL query to retrieve the ID.
     */
    protected function setId(): string
    {
        //
    }

    /**
     * Retrieves all data and returns it as a JSON string.
     *
     * @return bool|string The JSON string representation of the data, or false on failure.
     * @throws Exception
     */
    public function getAllData(): bool|string
    {
        try {
            $response = new StdClass();
            $response->wings = $this->getWingsOnField();
            $response->poles = $this->getPolesOnField();

            return $this->jsonResponse($response, false);
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
     * @return object An array containing the information of the wings on the field.
     * @throws Exception
     */
    protected function getWingsOnField(): object
    {
        if (!empty($this->parameters->without) && $this->parameters->without) {
            $sql = "
                SELECT `kitoro`.`id`, `kitoro`.`name_hu`, `kitoro`.`name_en`, `raktar`.`db`, `kitoro`.`kep`
                FROM `raktar`
                LEFT JOIN `kitoro` ON `kitoro`.`id` = `raktar`.`kitoro`
                WHERE `raktar`.`kitoro` IS NOT NULL
                AND `raktar`.`rudak` IS NULL
                AND `raktar`.`db` != 0
        ";
        }
        else {
            $sql = "
                SELECT `kt`.`id`, `kt`.`name_hu`, `kt`.`name_en`, `raktar`.`db`, `kt`.`kep`, IFNULL(GROUP_CONCAT(DISTINCT `palyak`.`neve` SEPARATOR ' | '), 'storage') as `palya`
                FROM `kitoro` as `kt`
                LEFT JOIN `palyan` ON `kt`.`id` = `palyan`.`kitoro`
                LEFT JOIN `palyak` ON `palyak`.`id` = `palyan`.`palya`
                LEFT JOIN `raktar` ON `kt`.`id` = `raktar`.`kitoro`
                WHERE `palyan`.`rudak` IS NULL
                GROUP BY `kt`.`id`, `kt`.`name_hu`, `kt`.`name_en`, `kt`.`db`, `kt`.`kep`
        ";
        }

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
        if (!empty($this->parameters->without) && $this->parameters->without) {
            $sql = "
                SELECT `rudak`.`id`, `rudak`.`name_hu`, `rudak`.`name_en`, `raktar`.`db`, `rudak`.`hossz`, `rudak`.`kep`
                FROM `raktar`
                LEFT JOIN `rudak` ON `rudak`.`id` = `raktar`.`rudak`
                WHERE `raktar`.`rudak` IS NOT NULL
                AND `raktar`.`kitoro` IS NULL
                AND `raktar`.`db` != 0
            ";
        }
        else {
            $sql = "
                SELECT `rd`.`id`, `rd`.`name_hu`, `rd`.`name_en`, `raktar`.`db`, `rd`.`kep`,  `rd`.`hossz`, IFNULL(GROUP_CONCAT(DISTINCT `palyak`.`neve` SEPARATOR ' | '), 'storage') as `palya`
                FROM `rudak` as `rd`
                LEFT JOIN `palyan` ON `rd`.`id` = `palyan`.`rudak`
                LEFT JOIN `palyak` ON `palyak`.`id` = `palyan`.`palya`
                LEFT JOIN `raktar` ON `rd`.`id` = `raktar`.`rudak`
                WHERE `palyan`.`kitoro` IS NULL
                GROUP BY `rd`.`id`, `rd`.`name_hu`, `rd`.`name_en`, `rd`.`db`, `rd`.`kep`, `rd`.`hossz`
        ";
        }

        return $this->mysql->queryObject($sql);
    }

    /**
     * Restock wings from field.
     *
     * @return object The SQL query for inserting the wings.
     */
    protected function addWings(): object
    {
        return "
            INSERT INTO raktar (kitoro, rudak, palya, db)
            VALUES (" . $_POST['kitoro'] . "," . null . "," . $this->fieldId . "," . $_POST['db'] . "," .$_POST['hossz'] . ") 
        ";
    }

    /**
     * Restock poles from field.
     *
     * @return object Returns the SQL query to add poles.
     */
    protected function addPoles(): object
    {
        return "
            INSERT INTO raktar (kitoro, rudak, palya, db, hossz)
            VALUES (" . null . "," . $_POST['rudak'] . "," . $this->fieldId . "," . $_POST['db'] . "," .$_POST['hossz'] . ")
        ";
    }

    /**
     * Deletes the wings from the "palyan" table based on specific conditions.
     *
     * @return string Returns the SQL query to delete the wings.
     */
    public function deleteWings(): string
    {
        return "
            DELETE FROM palyan WHERE palya = {$this->fieldId} AND rudak IS NULL AND kitoro = {$_POST["id"]}
        ";
    }

    /**
     * Deletes the poles from the "palyan" table based on specific conditions.
     *
     * @return string Returns the SQL query to delete the poles.
     */
    public function deletePoles(): string
    {
        return "
            DELETE FROM palyan WHERE palya = {$this->fieldId} AND kitoro IS NULL AND rudak = {$_POST["id"]}
        ";
    }

    public function get(bool $wing = true): false|string
    {
        try {
            $sql = "
                SELECT *
                FROM `raktar`
            ";
            if ($wing) {
                $sql .= "WHERE `kitoro` = {$this->parameters->id}";
            }
            else {
                $sql .= "WHERE `rudak` = {$this->parameters->id}";
            }

            return $this->mysql->queryObject($sql);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }
}