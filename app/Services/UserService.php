<?php

namespace App\Services;

use App\Services\Interfaces\IUserService;
use App\User;
use App\Userdetail;
use Session;

class UserService implements IUserService
{

    public function userRules()
    {
        return [
            'User.Username'             => 'required|alpha_dash|min:4|max:32|unique:users,Username',
            'User.Password'             => 'required|min:6|max:32|password',
            'User.Email'                => 'required|email|max:64|unique:users,Email',
            "Userdetail.FirstName"      => 'required|alpha|max:32',
            "Userdetail.LastName"       => 'required|alpha|max:32',
            "Userdetail.Birthday"       => 'required|date',
            "Userdetail.Address"        => 'alpha_num_spaces|max:64',
            "Userdetail.PostalCode"     => 'alpha_num|max:32',
            "Userdetail.City"           => 'alpha|max:85',
            "Userdetail.State"          => 'alpha|max:64',
            "Userdetail.Country"        => 'alpha|max:64',
            "Userdetail.Phone"          => 'phone|min:10|max:20',
            "Userdetail.MobilePhone"    => 'phone|min:10|max:20'
        ];
    }

    public function registerUser($fullUser)
    {
        $user = new User();
        $user->Username = $fullUser['User']['Username'];
        $user->Password = $fullUser['User']['Password'];
        $user->Email = $fullUser['User']['Email'];
        $user->save();
        $thisUserId = $user->UserID;

        $userDetails = new Userdetail();
        $userDetails->UserID = $thisUserId;
        $userDetails->FirstName = $fullUser['Userdetail']['FirstName'];
        $userDetails->LastName = $fullUser['Userdetail']['LastName'];
        $userDetails->Birthday = $fullUser['Userdetail']['Birthday'];
        $userDetails->Address = $fullUser['Userdetail']['Address'];
        $userDetails->PostalCode = $fullUser['Userdetail']['PostalCode'];
        $userDetails->City = $fullUser['Userdetail']['City'];
        $userDetails->State = $fullUser['Userdetail']['State'];
        $userDetails->Country = $fullUser['Userdetail']['Country'];
        $userDetails->Phone = $fullUser['Userdetail']['Phone'];
        $userDetails->MobilePhone = $fullUser['Userdetail']['MobilePhone'];
        $userDetails->save();
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
        $userdetails = Userdetail::where(['UserID' => $id])->firstOrFail();
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