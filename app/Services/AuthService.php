<?php
namespace App\Services;

use Exception;
use Session;

use App\Services\Interfaces\IAuthService;
use App\User;

class AuthService implements IAuthService
{
    /**
     * Check if user credentials are valid
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
     * Log out
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
}