<?php

namespace App\Controller\Login;

class LoginUser{
    // class properties
    private $username;
    private $password;
    public $error;
    public $success;
    private $storage = "users.json";
    private $stored_users;


    public function __construct($username, $password){
        $this->username = $username;
        $this->password = $password;
        $this->stored_users = json_decode(file_get_contents($this->storage), true);
        $this->login();
    }



}