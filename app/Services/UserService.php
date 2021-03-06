<?php

namespace App\Services;

use App\Services\Interfaces\IUserService;
use App\User;
use App\Userdetail;
use DateTime;
use DB;

class UserService implements IUserService
{

    public function userRules()
    {
        return [
            'User.Username' => 'required|alpha_dash|min:4|max:32|unique:users,Username',
            'User.Password' => 'required|min:6|max:32|password',
            'User.Email' => 'required|email|max:64|unique:users,Email',
            "Userdetail.FirstName" => 'required|alpha|max:32',
            "Userdetail.LastName" => 'required|alpha|max:32',
            "Userdetail.Birthday" => 'required|date_format:d/m/Y',
            "Userdetail.Address" => 'alpha_num_spaces|max:64',
            "Userdetail.PostalCode" => 'alpha_num|max:32',
            "Userdetail.City" => 'alpha|max:85',
            "Userdetail.State" => 'alpha|max:64',
            "Userdetail.Country" => 'alpha|max:64',
            "Userdetail.Phone" => 'phone|min:10|max:20',
            "Userdetail.MobilePhone" => 'phone|min:10|max:20'
        ];
    }

    public function userUpdateRules()
    {
        return [
            'User.Username' => 'alpha_dash|min:4|max:32|unique:users,Username',
            'User.Password' => 'min:6|max:32|password',
            'User.Email' => 'email|max:64|unique:users,Email',
            "Userdetail.FirstName" => 'alpha|max:32',
            "Userdetail.LastName" => 'alpha|max:32',
            "Userdetail.Birthday" => 'date_format:d/m/Y',
            "Userdetail.Address" => 'alpha_num_spaces|max:64',
            "Userdetail.PostalCode" => 'alpha_num|max:32',
            "Userdetail.City" => 'alpha|max:85',
            "Userdetail.State" => 'alpha|max:64',
            "Userdetail.Country" => 'alpha|max:64',
            "Userdetail.Phone" => 'phone|min:10|max:20',
            "Userdetail.MobilePhone" => 'phone|min:10|max:20'
        ];
    }

    public function registerUser($fullUser)
    {
        $user = User::create([
            'username' => $fullUser['User']['Username'],
            'password' => bcrypt($fullUser['User']['Password']),
            'email' => $fullUser['User']['Email'],
        ]);
        $thisUserId = $user->id;

        $date = DateTime::createFromFormat('d/m/Y', $fullUser['Userdetail']['Birthday']);

        $userDetails = new Userdetail();
        $userDetails->UserID = $thisUserId;
        $userDetails->FirstName = $fullUser['Userdetail']['FirstName'];
        $userDetails->LastName = $fullUser['Userdetail']['LastName'];
        $userDetails->Birthday = $date;
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
        return User::all(); //TODO details
    }

    public function getUserById($id)
    {
        return User::where(['id' => $id])->get(); //TODO details
    }

    public function updateUser($data, $id)
    {
        $user = User::find($id);
        if (isset($data['User']['Username']))
            $user->username = $data['User']['Username'];
        if (isset($data['User']['Password']))
            $user->password = bcrypt($data['User']['Password']);
        if (isset($data['User']['Email']))
            $user->email = $data['User']['Email'];
        $user->save();

        $userDetail =  DB::table('userdetails')->select('UserdetailsID')->where('UserID', $id)->get();
        $userDetailId = $userDetail['0']->UserdetailsID;
        if (isset($data['Userdetail']))
            DB::table('userdetails')
            ->where('UserdetailsID', $userDetailId)
            ->update($data['Userdetail']);
             
    }

    public function deleteUser($id)
    {
        User::destroy($id); //TODO delete order
    }

    /**
     * Get logged user with his details.
     *
     * @return array
     */
    public function getLoggedUser($userId)
    {
        $user = User::find($userId);
        $userdetails = Userdetail::where(['UserID' => $userId])->firstOrFail();
        return [
            'Username' => $user->username,
            'Email' => $user->email,
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