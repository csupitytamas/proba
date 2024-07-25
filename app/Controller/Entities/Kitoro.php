<?php

namespace App\Controller\Entities;

use App\Controller\Interfaces\EntityInterface;
use App\Controller\Pages\Maps\Storage;
use App\Controller\Traits\Response;
use App\Database\Mysql;
use Exception;

class Kitoro extends AbstractEntity implements EntityInterface
{
    use Response;
    protected const TABLE_NAME = 'kitoro';
    protected const STRUCTURE_SCHEMA = [
        'name_hu',
        'name_en',
        'db',
        'kep',
    ];
    protected Mysql $mysql;
    private object $getParameters;

    public function __construct(object $parameters)
    {
        $this->mysql = new Mysql();
        $this->getParameters = $parameters;
    }

    public function gelAll(): false|string
    {
        try {
            $sql = "
                SELECT `kt`.`id`, `kt`.`name_hu`, `kt`.`name_en`, `kt`.`db`, `kt`.`kep`
                FROM " . self::TABLE_NAME . " as `kt`
            ";

            $result = $this->mysql->queryObject($sql);

            return $this->jsonResponse($result);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }

    public function get(): false|string
    {
        try {
            if (!isset($this->getParameters->id) && !empty($this->getParameters->id)) {
                throw new Exception('Missing id parameter or empty');
            }

            $sql = "
                SELECT *
                FROM " . self::TABLE_NAME . "
                WHERE id = " . $this->getParameters->id . "
            ";

            $result = $this->mysql->queryObject($sql);

            return $this->jsonResponse($result);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }

    /**
     * @return false|string
     */
    public function create(): false|string
    {
        try {
            $validated = $this->validate(self::STRUCTURE_SCHEMA, $_POST);

            $result = $this->insertData(self::TABLE_NAME,self::STRUCTURE_SCHEMA, $validated, true);

            if (empty($result)) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => "Kitorot nem lehet elmenteni."
                ]);
            }

            $this->getParameters->id = $result;
            $kitoro = json_decode($this->get());
            $kitoro->kitoro = $kitoro->id;
            $storage = new Storage($kitoro);
            $storage->addWings();

            return $this->jsonResponse([
                'status' => 'success'
            ]);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }

    public function update(): false|string
    {
        try {
            if (!isset($_POST['id'])) {
                throw new Exception('Missing id parameter or empty');
            }

            $validated = $this->validate(self::STRUCTURE_SCHEMA, $_POST);
            $validated->id = $_POST['id'];

            $this->updateData(self::TABLE_NAME,self::STRUCTURE_SCHEMA, $validated);

            return $this->jsonResponse([
                'status' => 'success'
            ]);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }

    public function delete(): false|string
    {
        try {
            // TODO legvégső esetben kell csak
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }
}

