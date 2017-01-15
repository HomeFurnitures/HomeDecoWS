<?php
namespace App\Services;

use App\Services\Interfaces\IAuthService;
use App\User;
use Validator;

class AuthService implements IAuthService
{
    public function loginRules()
    {
        return [
            'username' => 'required|alpha_dash',
            'password' => 'required|password'
        ];
    }


    /**
     * Validate $dataJson as a JSON
     *
     * @param $dataJson
     * @return boolean
     */
    public function validJson($dataJson)
    {
        if (!$dataJson) {
            return false;
        }

        $validator = Validator::make(
            array(
                'jsonData' => $dataJson
            ),
            array(
                'jsonData' => 'json'
            )
        );

        if ($validator->fails()) {
            return false;
        }

        return true;
    }
}