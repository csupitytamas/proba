<?php

namespace App\Controller\Maps;

use Exception;

abstract class AbstractMaps
{
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

    public final function addWingsToField(): false|string
    {
        try {
            $this->getPostCheck();

            $sql = $this->addWings();

            $result = $this->mysql->queryObject($sql);

            return $this->jsonResponse($result);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }
    abstract protected function addWings();

    public final function addPolesToField(): false|string
    {
        try {
            $this->getPostCheck();

            $sql = $this->addPoles();

            $result = $this->mysql->queryObject($sql);

            return $this->jsonResponse($result);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }

    abstract protected function addPoles();

    public final function deleteWingsFromField(): false|string
    {
        try {
            $this->getPostCheck();

            $sql = $this->deleteWings();

            $result = $this->mysql->query($sql);

            if ($result) {
                throw new Exception("Delete don't execute");
            }

            $response = [
                'status' => 'success',
                'deleted_id' => $_POST["id"]
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

            $sql = $this->deletePoles();

            $result = $this->mysql->query($sql);

            if ($result) {
                throw new Exception("Delete don't execute");
            }

            $response = [
                'status' => 'success',
                'deleted_id' => $_POST["id"]
            ];

            return $this->jsonResponse($response);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }
    abstract protected function deletePoles();

    /**
     * @param $message
     *
     * @return string
     */
    protected function getExceptionFormat($message): string
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
    protected function jsonResponse($data, int $status = 200): false|string
    {
        header('Content-Type: application/json');
        // TODO add bad request header status code
        return json_encode($data);
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function getPostCheck(): void
    {
        if (!isset($_POST)) {
            throw new Exception('POST method only allowed');
        }
    }
}