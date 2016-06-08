<?php

namespace App\Services\Interfaces;


interface IUserService
{
    public function validateUser($fullUser);

    public function registerUser($fullUser);

    public function getAllUsers();

    public function getUserById($id);

    public function updateUser($data, $id);

    public function deleteUser($id);

    public function checkUsername($username);

    public function checkEmail($email);
    
    public function getSessionUser();
}