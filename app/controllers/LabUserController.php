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

			Redirect::to('/userpage', array('message' => 'Login successfull.'));
		}
	}

	public static function userpage() {
		$user = parent::get_user_logged_in();
		$experiments = Experiment::findByLabUser($user->id);
		$counts = array();
		foreach($experiments as $exp) {
			$n = Reservation::countReservationsForExperiment($exp->id);
			if($n != null) {
				$counts[$exp->id] = $n;
			} else {
				$counts[$exp->id] = 0;
			}
		}
		$reservations = Reservation::findUpcomingByLabUser($user->id);
		View::make('user/userpage.html', array('exps' => $experiments, 'counts' => $counts, 'reservations' => $reservations));
	}

	public static function listUsers() {
		$users = LabUser::findAll();
		$experiments = array();
		foreach ($users as $user) {
			$experiments[$user->id] = Experiment::findByLabUser($user->id);
		}
		View::make('user/all_users.html', array('users' => $users, 'experiments' => $experiments));
	}

}

