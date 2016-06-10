<?php

namespace App\Services\Interfaces;

interface IAuthService
{
    public function checkUser($user);

    public function createToken($username);

    public function destroySession();
    
    public function checkLogin($token);
    
    public function validJson($dataJson);
}