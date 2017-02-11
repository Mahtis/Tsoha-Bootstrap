<?php

class LabUser extends BaseModel {
	public $id, $name, $username, $password, $email;

	public function __construct($attributes) {
		parent::__construct($attributes);
    //$this->validators = array('validateName');
	}

	public static function authenticate($username, $password) {
		$query = DB::connection()->prepare('SELECT * FROM LabUser WHERE username = :username AND password = :password LIMIT 1');
    	$query->execute(array('username' => $username, 'password' => $password));
    	$row = $query->fetch();

    	if($row) {
    		$user = new LabUser(array('id' => $row['id'],
    			'name' => $row['name'],
    			'username' => $row['username'],
    			'password' => $row['password'],
    			'email' => $row['email']));
    		return $user;
    	} else {
    		return null;
    	}
	}

	public static function findOne($id) {
		$query = DB::connection()->prepare('SELECT * FROM LabUser WHERE id = :id LIMIT 1');
    	$query->execute(array('id' => $id));
    	$row = $query->fetch();

    	if($row) {
    		$user = new LabUser(array('id' => $row['id'],
    			'name' => $row['name'],
    			'username' => $row['username'],
    			'password' => $row['password'],
    			'email' => $row['email']));
    		return $user;
    	}
	}

}