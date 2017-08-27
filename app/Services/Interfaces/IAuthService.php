<?php

namespace App\Services\Interfaces;

interface IAuthService
{
    public function loginRules();
    
    public function validJson($dataJson);

    public function androidLogin($id, $token);

    public function checkAndroidAuth($token);

    public function androidLogOut($id);

    public function getAndroidUserId($token);
}