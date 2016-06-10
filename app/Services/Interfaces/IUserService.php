<?php

namespace App\Services\Interfaces;


interface IUserService
{
    public function userRules();

    public function registerUser($fullUser);

    public function getAllUsers();

    public function getUserById($id);

    public function updateUser($data, $id);

    public function deleteUser($id);
    
    public function getSessionUser();
}