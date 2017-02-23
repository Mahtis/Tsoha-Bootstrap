<?php

class UserExperiment extends BaseModel {

	public $labuser_id, $experiment_id;

	public function __construct($attributes) {
		parent::__construct($attributes);
        $this->validators = array();
	}

	public static function findByUser($user_id) {
		$query = DB::connection()->prepare('SELECT * FROM UserExperiment WHERE labuser_id = :id');
		$query->execute(array('id' => $user_id));
		$rows = $query->fetchAll();
    	$userExperiments = array();
    	foreach($rows as $row){
      		$userExperiments[] = new UserExperiment(array(
        		'labuser_id' => $row['labuser_id'],
        		'experiment_id' => $row['experiment_id']));
    	}
    	return $userExperiments;
	}

	public static function findByExperiment($exp_id) {
		$query = DB::connection()->prepare('SELECT * FROM UserExperiment WHERE experiment_id = :id');
		$query->execute(array('id' => $exp_id));
		$rows = $query->fetchAll();
    	$userExperiments = array();
    	foreach($rows as $row){
      		$userExperiments[] = new UserExperiment(array(
        		'labuser_id' => $row['labuser_id'],
        		'experiment_id' => $row['experiment_id']));
    	}
    	return $userExperiments;
	}

	public static function findByUserAndExperiment($user_id, $exp_id) {
		$query = DB::connection()->prepare('SELECT * FROM UserExperiment WHERE experiment_id = :exp_id AND labuser_id = :user_id');
		$query->execute(array('exp_id' => $exp_id, 'user_id' => $user_id));
		$row = $query->fetch();
		if ($row) {
			$userExperiment = new UserExperiment(array(
				'labuser_id' => $row['labuser_id'],
				'experiment_id' => $row['experiment_id']));
			return $userExperiment;
		}
	}

	public function save(){
    	$query = DB::connection()->prepare('INSERT INTO UserExperiment (labuser_id, experiment_id) VALUES (:user_id, :exp_id)');
    	try {
    		$query->execute(array('user_id' => $this->labuser_id, 'exp_id' => $this->experiment_id));
    		return true;
    	} catch (PDOException $e) {
    		return false;
    	}
  	}
}
