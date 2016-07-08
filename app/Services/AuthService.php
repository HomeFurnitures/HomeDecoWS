<?php
namespace App\Services;

use Exception;
use Session;

use App\Services\Interfaces\IAuthService;
use App\User;
use Validator;

class AuthService implements IAuthService
{
    /**
     * Validate user's credentials
     *
     * @param $user
     * @return bool
     */
    public function checkUser($user)
    {
        try {
            $result = User::where(['Username' => $user->Username])->firstOrFail();

            if ($result->Password == $user->Password) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Create unique token and Session
     *
     * @param $username
     * @return String token
     */
    public function createToken($username)
    {
        $result = User::where(['Username' => $username])->firstOrFail();
        $store = ['token' => uniqid("", false), 'userid' => $result->UserID];
        Session::put('login', $store);
        return $store['token'];
    }

    /**
     * Log out by destroying the session
     */
    public function destroySession()
    {
        Session::forget('login');
    }

    public function checkLogin($token)
    {
        try {
            $sesToken = Session::get('login')['token'];
            if ($sesToken == $token) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Validate $dataJson as a JSON
     *
     * @param $dataJson
     * @return Validator $validator
     */
    public function validJson($dataJson)
    {
        
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