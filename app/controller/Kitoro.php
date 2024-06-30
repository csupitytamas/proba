<?php

namespace App\controller;

use App\database\Mysql;
use Exception;

class Kitoro
{
    public function getKitoro(): void
    {
        try {
            $mysql = new Mysql();

            $sqlKitoro = "SELECT `neve`, `db`, `kep` FROM kitoro";
            $queryKitoro = $mysql->query($sqlKitoro);

            header("Content-Type: application/json");
            echo json_encode($queryKitoro);

        } catch (Exception $exception) {
            die( $exception->getMessage() );
        }
    }
}

