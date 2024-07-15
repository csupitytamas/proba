<?php

namespace App\Controller\Entities;

use Exception;
use stdClass;

abstract class AbstractEntity
{
    /**
     * Validates the posted data based on a given schema.
     *
     * @param array $schema     The schema to validate against.
     * @param array $postedData The posted data to be validated.
     *
     * @return stdClass The validated data.
     * @throws Exception If no valid data is passed.
     */
    protected function validate(array $schema, array $postedData): stdClass
    {
        $validatedData = new StdClass();

        if (empty($postedData)) {
            return new stdClass();
        }

        foreach ($schema as $dbFields) {
            if (isset($postedData[$dbFields])) {
                $validatedData->$dbFields = $postedData[$dbFields];
            }
        }

        if (!$this->isEmpty($validatedData)) {
            throw new Exception('Nem lett átadva valid adat a mentéshez!');
        }

        return $validatedData;
    }

    /**
     * Checks if the given object is empty.
     *
     * @param object $object The object to check for emptiness.
     *
     * @return bool Returns true if the object is empty, false otherwise.
     */
    public function isEmpty(object $object): bool
    {
        if (empty((array) $object)) {
            return false;
        }
        return true;
    }

    /**
     * Checks if the given object does not have any properties defined in the current object.
     *
     * @param object $object The object to be checked.
     *
     * @return bool Returns true if the given object does not have any properties defined in the current object,
     *               otherwise returns false.
     */
    public function hasProperty(object $object): bool
    {
        foreach($object as $property => $value) {
            if (isset($this->$property)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Generates an SQL insert statement based on the given schema and data.
     *
     * @param string  $table The schema which defines the database table.
     * @param array  $schema The schema which defines the database fields.
     * @param object $data   The data object containing the values to be inserted.
     *
     * @return bool Returns true if the insert statement was executed successfully, false otherwise.
     * @throws Exception
     */
    public function insertData(string $table, array $schema, object $data): bool
    {
        if (empty($table)) {
            throw new Exception('Table name is required');
        }

        $last = end($schema);

        $sql = "
                INSERT INTO {$table} (";

        foreach ($schema as $dbFields) {
            $sql .= "$dbFields";
            if ($dbFields !== $last) {
                $sql .= ", ";
            }
        }

        $sql .= ")";
        $sql .= "
                Values (";

        foreach ($schema as $dbFields) {
            if (isset($data->$dbFields)) {
                $sql .= "'{$data->$dbFields}'";
            }
            else {
                $sql .= "null";
            }
            if ($dbFields !== $last) {
                $sql .= ", ";
            }
        }
        $sql .= ");";

        return $this->mysql->insert($sql);
    }

    /**
     * @throws Exception
     */
    public function updateData(string $table, array $schema, object $data)
    {
        if (empty($table)) {
            throw new Exception('Table name is required');
        }

        $numberToBeAdded = 1;
        $last = end($schema);
        $lastObjectElementId = count((array)$data);

        if (!isset($data->id)) {
            $numberToBeAdded = 0;
        }

        $sql = "
                UPDATE {$table}
                SET ";

        foreach ($schema as $key => $dbFields) {
            if (isset($data->$dbFields)) {
                $sql .= "$dbFields='{$data->$dbFields}'";
                if ($dbFields !== $last && ($key + $numberToBeAdded) < ($lastObjectElementId - $numberToBeAdded)) {
                    $sql .= ", ";
                }
            }
        }

        $sql .= "
                WHERE id = " . $data->id;

        return $this->mysql->update($sql);
    }
}