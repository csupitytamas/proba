<?php

namespace App\controller;

use App\database\Mysql;
use Exception;

class Rudak {

    private Mysql $mysql;

    public function __construct()
    {
        $this->mysql = new Mysql();
    }

    /**
     * @return false|string
     */
    public function create(): false|string
    {
        try {
            if (!isset($_POST['name_en']) || !isset($_POST['name_hu']) || !isset($_POST['db']) || !isset($_POST['hossz']) || !isset($_POST['kep'])) {
                throw new Exception('Nincs megfelelő posztolt adat a mentéshez.');
            }

            $sql = "
                INSERT INTO rudak (name_en, name_hu, db, hossz,kep)
                Values ('{$_POST['name_en']}', {$_POST['name_hu']}','{$_POST['db']}', '{$_POST['hossz']}', '{$_POST['kep']}');
            ";

            $result = $this->mysql->queryObject($sql);

            header('Content-Type: application/json');
            if (empty($result)) {
                return json_encode([
                    'status' => 'error',
                    'message' => "Kitorot nem lehet elmenteni."
                ]);
            }
            return json_encode([
                'status' => 'success'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json', true, 400);
            return json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
