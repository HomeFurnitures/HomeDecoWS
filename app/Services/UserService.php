<?php

namespace App\Services;

use App\Services\Interfaces\IUserService;
use App\User;
use App\Userdetail;
use Exception;
use Session;

class UserService implements IUserService
{

    public function validateUser($fullUser)
    {
        // TODO: Implement validateUser() method.
    }

    public function registerUser($fullUser)
    {
        // TODO: Implement registerUser() method.
    }

    public function getAllUsers()
    {
        // TODO: Implement getAllUsers() method.
    }

    public function getUserById($id)
    {
        // TODO: Implement getUserById() method.
    }

    public function updateUser($data, $id)
    {
        // TODO: Implement updateUser() method.
    }

    public function deleteUser($id)
    {
        // TODO: Implement deleteUser() method.
    }

    /**
     * Check if the specified username exists
     *
     * @param $username
     * @return bool
     */
    public function checkUsername($username)
    {
        try {
            User::where(['Username' => $username])->firstOrFail();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Check if the specified e-mail exists
     *
     * @param $email
     * @return bool
     */
    public function checkEmail($email)
    {
        try {
            User::where(['Email' => $email])->firstOrFail();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * get logged user with his details
     * 
     * @return array
     */
    public function getSessionUser()
    {
        $id = Session::get('login')['userid'];
        $user = User::where(['UserID' => $id])->firstOrFail();
        $userdetails = Userdetail::where(['UserdetailsID' => $user->UserdetailsID])->firstOrFail();
        return [
            'Username' => $user->Username,
            'Email' => $user->Email,
            'FirstName' => $userdetails->FirstName,
            'LastName' => $userdetails->LastName,
            'Birthday' => $userdetails->Birthday,
            'Address' => $userdetails->Address,
            'PostalCode' => $userdetails->PostalCode,
            'City' => $userdetails->City,
            'State' => $userdetails->State,
            'Country' => $userdetails->Country,
            'Phone' => $userdetails->Phone,
            'MobilePhone' => $userdetails->MobilePhone
        ];
    }
}