<?php

namespace App\Controller\Maps;

use App\Controller\Traits\Response;
use Exception;

abstract class AbstractMaps
{
    use Response;

    abstract protected function getAllData();

    abstract protected function getWingsOnField();

    abstract protected function getPolesOnField();

    public final function getFieldId(): false|string
    {
        try {
            $sql = $this->setId();

            $result = $this->mysql->queryObject($sql);

            return $result->id;
        } catch (Exception $exception) {
            return $this->jsonResponse($this->getExceptionFormat($exception->getMessage()));
        }
    }
    abstract protected function setId();

    /**
     * Executes a SELECT query on the "palyan" table based on the given field value.
     * Optionally filters the result based on the "kitoro" or "rudak" column.
     *
     * @param  string  $fieldId   The value of the "palya" column to filter the query.
     * @param  string  $entityId  The value to use for filtering the "kitoro" or "rudak" column, depending on the value of $wing parameter.
     * @param  bool    $wing      Optional. If true, filters the result by the "kitoro" column. Otherwise, filters by the "rudak" column. Default is true.
     *
     * @return bool Returns true if the query executed successfully and returned a result, false otherwise.
     *
     * @throws Exception If an error occurs while executing the query.
     */
    public final function onField(string $fieldId, string $entityId, bool $wing = true): bool
    {
        try {
            $sql = "
                SELECT *
                FROM palyan
                WHERE palya = {$fieldId}
            ";
            if ($wing) {
                $sql .= "AND kitoro = {$entityId}";
            }
            else {
                $sql .= "AND rudak = {$entityId}";
            }
            $result = $this->mysql->queryObject($sql);

            if (empty($result)) {
                return false;
            }
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public final function addWingsToField(): false|string
    {
        try {
            $this->getPostCheck();

            $object = $this->addWings();

            if ($object->type == 'update') {
                $result = $this->mysql->update($object->sql);
                if (!$result) {
                    throw new Exception('Failed to update wings');
                }
            }
            else {
                $result = $this->mysql->insert($object->sql);
                if (!$result) {
                    throw new Exception('Failed to insert wings');
                }
            }

            $this->decreaseStorageStock($object);

            $response = [
                'status' => 'success',
            ];

            return $this->jsonResponse($response);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }

    abstract protected function addWings(): object;

    public final function addPolesToField(): false|string
    {
        try {
            $this->getPostCheck();

            $object = $this->addPoles();

            if ($object->type == 'update') {
                $result = $this->mysql->update($object->sql);
                if (!$result) {
                    throw new Exception('Failed to update poles');
                }
            }
            else {
                $result = $this->mysql->insert($object->sql);
                if (!$result) {
                    throw new Exception('Failed to insert poles');
                }
            }

            $this->decreaseStorageStock($object);

            $response = [
                'status' => 'success',
            ];
            return $this->jsonResponse($response);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }

    abstract protected function addPoles(): object;

    public final function deleteWingsFromField(): false|string
    {
        try {
            $this->getPostCheck();

            $sqlCommands = $this->deleteWings();

            $this->mysql->executeAsTransaction($sqlCommands);

            $response = [
                'status' => 'success',
                'reload' => true
            ];

            return $this->jsonResponse($response);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }

    abstract protected function deleteWings();

    public final function deletePolesFromField(): false|string
    {
        try {
            $this->getPostCheck();

            $sqlCommands = $this->deletePoles();

            $this->mysql->executeAsTransaction($sqlCommands);

            $response = [
                'status' => 'success',
                'reload' => true
            ];

            return $this->jsonResponse($response);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }
    abstract protected function deletePoles();

    /**
     * Updates the storage stock.
     *
     * @param  object  $object
     * @param  object  $fields  The fields object containing the stock information to update.
     *
     * @param  bool    $wing    The wing object to update the stock for.
     *
     * @return void Returns the formatted exception message if an exception occurs, otherwise null.
     *
     * @throws Exception
     */
    public final function increaseStorageStock(object &$object, object &$fields, bool $wing = true): void
    {
        try {
            $this->getPostCheck();

            $sqlCommand = "
                UPDATE `raktar`
                SET `db` = `db` + {$fields->db}";
            if ($wing) {
                $sqlCommand .= " WHERE `kitoro` = {$object->id}";
            }
            else {
                $sqlCommand .= " WHERE `rudak` = {$object->id}";
            }

            $result = $this->mysql->update($sqlCommand);

            if (!$result) {
                throw new Exception('Failed to update storage stock');
            }
        } catch (Exception $exception) {
             throw new  Exception($exception->getMessage());
        }
    }

    /**
     * Updates the storage stock.
     *
     * @param  object  $object
     *
     * @return void Returns the formatted exception message if an exception occurs, otherwise null.
     *
     * @throws Exception
     */
    public final function decreaseStorageStock(object &$object): void
    {
        try {
            $this->getPostCheck();

            $sqlCommand = "
                UPDATE `raktar`
                SET `db` = `db` - {$object->updatedField->db}";
            if (isset($object->wing)) {
                $sqlCommand .= " WHERE `kitoro` = {$object->wing->id}";
            }
            else {
                $sqlCommand .= " WHERE `rudak` = {$object->pole->id}";
            }

            $result = $this->mysql->update($sqlCommand);

            if (!$result) {
                throw new Exception('Failed to update storage stock');
            }
        } catch (Exception $exception) {
            throw new  Exception($exception->getMessage());
        }
    }
}