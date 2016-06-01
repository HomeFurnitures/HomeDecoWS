<?php

namespace App\Services\Interfaces;

/**
 * Interface Repository
 *
 * Provides the standard functions to be expected of any repository.
 *
 * @package App\Services\Interfaces
 */
interface IAuthService
{
	public function checkUser($user);

	public function createToken($username);

	public function checkUsername($username);

	public function checkEmail($email);

	public function validateUserData($fullUser);

	public function registerUser($fullUser);

	public function checkData($data);
}