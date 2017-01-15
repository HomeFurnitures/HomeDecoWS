<?php

namespace App\Services\Interfaces;

interface IAuthService
{
    public function loginRules();
    
    public function validJson($dataJson);
}