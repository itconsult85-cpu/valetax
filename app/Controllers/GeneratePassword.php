<?php

namespace App\Controllers;

class GeneratePassword extends BaseController
{
    public function index()
    {
        $password = 'admin123';
        echo password_hash($password, PASSWORD_BCRYPT);
    }
}
