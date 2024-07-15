<?php

session_start();
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );
error_reporting( E_ALL );

require __DIR__ . '/vendor/autoload.php';

if (!isset($_COOKIE['lang'])) {
    setcookie('lang', 'en', time() + (86400 * 30), "/");
}

require_once( "routing.php" );

if (file_exists('users.json')) {
    $users = json_decode(file_get_contents('users.json'), true);
}
