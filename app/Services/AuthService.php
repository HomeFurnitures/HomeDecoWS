<?php
namespace App\Services;

use App\Services\Interfaces\IAuthService;
use App\Android_token;
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

    public function androidLogin($id, $token)
    {
        $db_token = new Android_token();
        $db_token->user_id = $id;
        $db_token->token = $token;
        $db_token->save();
    }

    public function checkAndroidAuth($token)
    {
        try {
            Android_token::where(['token' => $token])->get();
        }
        catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function androidLogOut($id)
    {
        Android_token::destroy($id);
    }

    public function getAndroidUserId($token)
    {
        $userId = Android_token::where(['token' => $token])->get(['user_id'])[0]->user_id;
        return $userId;
    }
}