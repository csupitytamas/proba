<?php

namespace App\Controller\Authenticaton;

use App\database\Mysql;

class RegisterController
{
    private Mysql $mysql;

    public function __construct()
    {
        $this->mysql = new Mysql();
    }

    public static function register() {

    }

    public static function showRegistrationPage()
    {

    }
}