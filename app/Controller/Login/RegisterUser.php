<?php
namespace App\Controller\Login;
class RegisterUser
{
    private $username;
    private $raw_password;
    private $encrypted_password;
    public $error;
    public $success;
    private $storage = "users.json";
    private $stored_users;
    private $new_user;


    public function __construct($username, $password){

        $this->username = filter_var(trim($username), FILTER_SANITIZE_STRING);
        $this->raw_password = filter_var(trim($password), FILTER_SANITIZE_STRING);
        $this->encrypted_password = password_hash($this->raw_password, PASSWORD_DEFAULT);

        $this->stored_users = json_decode(file_get_contents($this->storage), true);

        $this->new_user = [
            "username" => $this->username,
            "password" => $this->encrypted_password,
        ];

        if($this->checkFieldValues()){
            $this->insertUser();
        }
    }


    private function checkFieldValues() {
        if(empty($this->username) || empty($this->raw_password) || strlen($this->raw_password) < 8){
            $this->error = "Both fields need to fill up, and password must be at least 8 characters.";
            return false;
        }else{
            return true;
        }
    }


    private function usernameExists(){

        foreach($this->stored_users as $user){
            if($this->username == $user['username']){
                $this->error = "Username already taken, please choose a different one.";
                return true;
            }
        }
        return false;
    }


    private function insertUser() {
        if($this->usernameExists() == FALSE){
            array_push($this->stored_users, $this->new_user);
            if(file_put_contents($this->storage, json_encode($this->stored_users, JSON_PRETTY_PRINT))){
                $this->success = "Your registration was successful";
            }else{
                $this->error = "Something went wrong, please try again";
            }
        }
    }



}