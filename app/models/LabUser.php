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

    public static function findAll() {
        $query = DB::connection()->prepare('SELECT * FROM LabUser');
        $query->execute();
        $rows = $query->fetchAll();
        $users = array();
        foreach($rows as $row){
            $users[] = new LabUser(array('id' => $row['id'],
                'name' => $row['name'],
                'username' => $row['username'],
                'password' => $row['password'],
                'email' => $row['email']));
        }
        return $users;
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

    public static function findByExperiment($exp_id) {
        $query = DB::connection()->prepare('SELECT u.* FROM LabUser u, UserExperiment ue, Experiment e WHERE u.id=ue.labuser_id AND e.id=ue.experiment_id AND e.id=:exp_id');
        $query->execute(array('exp_id' => $exp_id));
        $rows = $query->fetchAll();
        $users = array();
        foreach($rows as $row){
            $users[] = new LabUser(array('id' => $row['id'],
                'name' => $row['name'],
                'username' => $row['username'],
                'password' => $row['password'],
                'email' => $row['email']));
        }
        return $users;
    }
}