<?php

namespace App\Controller;

use App\database\Mysql;
use Exception;

class User
{
    private object $user;
    private string $postName;
    private string $postPassword;
    private Mysql $mysql;

    public function __construct($postData)
    {
        $this->mysql = new Mysql();
        $this->validator($postData);
    }

    /**
     * @throws Exception
     */
    public function login()
    {
        $this->checkUser();

        if(!password_verify($this->postPassword, $this->user->password)) {
            throw new Exception('Wrong username or password');
        }

        $_SESSION['user_id'] = $this->user->id;
        // TODO redirect to admin dashboard
        return;
    }

    /**
     * @throws Exception
     */
    public function registration()
    {
        $this->checkUser();

        $sql = "
            INSERT (username, password)
            VALUES ('" . $this->postName . "', '" . self::hashPassword($this->postPassword) . "')
        ";

        $result = $this->mysql->queryObject($sql);
        if (!$result) {
            throw new Exception('Hiba a mentés során');
        }
        return json_encode([
            'status' => 'success',
            'message' => 'Registration successful!'
        ]);
    }

    public function getPermissions()
    {

    }

    public function getRoles()
    {

    }

    private function validator($postData): void
    {
        if(isset($postData['password'])) {
            $this->postPassword = $postData['password'];
        }
        elseif(isset($postData['username'])) {
            $this->postName = $postData['username'];
        }
    }

    /**
     * Check user exists in database
     * @throws Exception
     */
    public function checkUser(): void
    {
        $sql = "
            SELECT *
            FROM users
            WHERE username = '" . $this->postName . "'";

        $this->user = $this->mysql->queryObject($sql);
        if (!$this->user) {
            throw new Exception('Wrong username or password');
        }
    }

    /**
     * @param $password
     * @return string
     */
    public static function hashPassword($password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}