<?php

class Experiment extends BaseModel {

	public $id, $name, $description, $maxSubjects;

	public function __construct($attributes) {
		parent::__construct($attributes);
	}

	public static function findAll() {
		$query = DB::connection()->prepare('SELECT * FROM Experiment');
		$query->execute();
		$rows = $query->fetchAll();
    	$experiments = array();
    	foreach($rows as $row){
      		$experiments[] = new Experiment(array(
        		'id' => $row['id'],
        		'name' => $row['name'],
        		'description' => $row['description'],
        		'maxSubjects' => $row['maxsubjects']));
    	}
    	return $experiments;
	}

	public static function findOne($id) {
		$query = DB::connection()->prepare('SELECT * FROM Experiment WHERE id = :id LIMIT 1');
    	$query->execute(array('id' => $id));
    	$row = $query->fetch();

    	if($row) {
    		$experiment = new Experiment(array(
    			'id' => $row['id'],
        		'name' => $row['name'],
        		'description' => $row['description'],
        		'maxSubjects' => $row['maxsubjects']));
    	}
    	return $experiment;
	}

	// Find experiments that have timeslots for tomorrow ->
	public static function findAllActive() {
		$query = DB::connection()->prepare('SELECT DISTINCT e.* FROM Experiment e, TimeSlot ts WHERE e.id=ts.experiment_id AND ts.starttime > CURRENT_DATE+1');
		$query->execute();
		$rows = $query->fetchAll();
		$experiments[] = array();
		foreach($rows as $row){
      		$experiments[] = new Experiment(array(
        		'id' => $row['id'],
        		'name' => $row['name'],
        		'description' => $row['description'],
        		'maxSubjects' => $row['maxsubjects']));
    	}
    	return $experiments;

	}

  	public function save(){
    	$query = DB::connection()->prepare('INSERT INTO Experiment (name, description, maxsubjects) VALUES (:name, :description, :maxsubjects) RETURNING id');
    	$query->execute(array('name' => $this->name, 'description' => $this->description, 'maxsubjects' => $this->maxSubjects));
    	$row = $query->fetch();
    	$this->id = $row['id'];
  	}

}