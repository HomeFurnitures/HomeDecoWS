<?php
namespace App\Services;

use Session;

use App\Services\Interfaces\IAuthService;
use App\User;

class AuthService implements IAuthService
{
	public function checkUser($user)
	{
		
		try
		{
			$result = User::where(['Username' => $user->Username])->firstOrFail();

			if ($result->Password == $user->Password) 
			{
				return true;	
			}

		   	return false;
		} 
		catch(\Exception $e)
		{
		   	return false;
		}

	}

	public function createToken($username)
	{
		$result = User::where(['Username' => $username])->firstOrFail();
		$store = ['token' => uniqid("", false), 'userid' => $result->UserID];
		Session::put('login', $store);
		return $store['token'];
	}

	public function checkUsername($username)
	{
		try
		{
			$result = User::where(['Username' => $username])->firstOrFail();
			return true;
		}
		catch(\Exception $e)
		{
		   	return false;
		}
	}

	public function checkEmail($email)
	{
		try
		{
			$result = User::where(['Email' => $email])->firstOrFail();
			return true;
		}
		catch(\Exception $e)
		{
		   	return false;
		}
	}

	public function validateUserData($fullUser)
	{
		//TODO
		return false;
	}

	public function registerUser($fullUser)
	{
		//TODO
	}

	public function checkData($data)
	{
		return true;
	}
}