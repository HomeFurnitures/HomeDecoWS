<?php

namespace App\Services;

use App\Services\Interfaces\IUserService;
use App\User;
use App\Userdetail;
use Session;
use Validator;

class UserService implements IUserService
{

    public function userRules()
    {
        return [
            'User.Username' => 'required|alpha_dash|unique:users,Username',
            'User.Password' => 'required|min:6|regex:/^[a-zA-Z0-9\.\-\!\@\#\$\%\^\&\*]+$/',
            'User.Email' => 'required|email|unique:users,Email',
            "Userdetail.FirstName" => 'required|alpha',
            "Userdetail.LastName" => 'required|alpha',
            "Userdetail.Birthday" => 'required|date',
            "Userdetail.Address" => 'alpha_num',
            "Userdetail.PostalCode" => 'alpha_num',
            "Userdetail.City" => 'alpha',
            "Userdetail.State" => 'alpha',
            "Userdetail.Country" => 'alpha',
            "Userdetail.Phone" => 'integer',
            "Userdetail.MobilePhone" => 'integer'
        ];
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
     * Get logged user with his details.
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