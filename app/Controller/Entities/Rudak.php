<?php

namespace App\Controller\Entities;

use App\Controller\Interfaces\EntityInterface;
use App\Controller\Pages\Maps\Storage;
use App\Controller\Traits\Response;
use App\Database\Mysql;
use Exception;

class Rudak extends AbstractEntity implements EntityInterface
{
    use Response;
    protected const TABLE_NAME = 'rudak';
    protected const STRUCTURE_SCHEMA = [
        'name_hu',
        'name_en',
        'db',
        'hossz',
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
                SELECT `rd`.*, IFNULL(GROUP_CONCAT(DISTINCT `palyak`.`neve` SEPARATOR ' | '), 'storage') as `palya`
                FROM " . self::TABLE_NAME . " as `rd`
                LEFT JOIN `palyan` ON `rd`.`id` = `palyan`.`rudak`
                LEFT JOIN `palyak` ON `palyak`.`id` = `palyan`.`palya`
                LEFT JOIN `raktar` ON `rd`.`id` = `raktar`.`rudak`
                WHERE `palyan`.`kitoro` IS NULL
                GROUP BY `rd`.`id`, `rd`.`name_hu`, `rd`.`name_en`, `rd`.`db`, `rd`.`kep`, `rd`.`hossz`;
            ";

            $result = $this->mysql->queryObject($sql, false);

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

            $result = $this->insertData(self::TABLE_NAME,self::STRUCTURE_SCHEMA, $validated,true);

            if (empty($result)) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => "Rudat nem lehet elmenteni."
                ]);
            }

            $this->getParameters->id = $result;
            $rud = json_decode($this->get());
            $rud->rudak = $rud->id;
            $storage = new Storage($rud);
            $storage->addPoles();

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
            if (!isset($this->getParameters->id)) {
                throw new Exception('Missing id parameter or empty');
            }
            $storageEntity = new Storage($this->getParameters);
            $rud = json_decode($this->get());
            $storage = $storageEntity->get(false);
            if ($rud->db != $storage->db) {
                throw new Exception('Some poles on field!');
            }

            $deleteFromStorage = $storageEntity->deletePole();

            if (!$deleteFromStorage) {
                throw new Exception('Can not delete from storage!');
            }

            $sql = "
                DELETE FROM " . self::TABLE_NAME . "
                WHERE id = {$this->getParameters->id}
            ";

            $this->mysql->delete($sql);

            return $this->jsonResponse([
                'status' => 'success'
            ]);
        } catch (Exception $exception) {
            return $this->getExceptionFormat($exception->getMessage());
        }
    }
}
