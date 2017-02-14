<?php

class LabUserController extends BaseController {

	public static function login() {
		View::make('user/login.html');
	}

	public static function logout() {
		$_SESSION['user'] = null;
		Redirect::to('/', array('message' => 'You have been logged out.'));
	}

	public static function handleLogin() {
		$params = $_POST;

		$user = LabUser::authenticate($params['username'], $params['password']);

		if(!$user) {
			View::make('user/login.html', array('error' => 'Wrong username or password', 'username' => $params['username']));
		} else {
			$_SESSION['user'] = $user->id;

			Redirect::to('/userpage', array('message' => 'Welcome'));
		}
	}

	public static function userpage() {
		View::make('user/userpage.html');
	}
}

