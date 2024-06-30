<?php

use App\controller\Kitoro;

session_start();
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );
error_reporting( E_ALL );

require __DIR__ . '/vendor/autoload.php';

$kitoro = new Kitoro();
var_dump($kitoro->getKitoro());
die();

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["email"]) && isset($_POST["password"])){
        $email = $_POST["email"];
        $passwd = $_POST["password"];
    }
}

if (file_exists('users.json')) {
    $users = json_decode(file_get_contents('users.json'), true);
}
