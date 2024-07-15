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
}