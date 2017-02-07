<?php

class RequiredInfo extends BaseModel {

	public $id, $question, $experiment_id;

	public function __construct($attributes) {
		parent::__construct($attributes);
	}

	public static function findAll() {
		$query = DB::connection()->prepare('SELECT * FROM Requiredinfo');
		$query->execute();
		$rows = $query->fetchAll();
    	$requiredInfos = array();
    	foreach($rows as $row){
      		$requiredInfos[] = new Experiment(array(
        		'id' => $row['id'],
        		'question' => $row['question'],
        		'experiment_id' => $row['experiment_id']));
    	}
    	return $requiredInfos;
	}

	public function save(){
    	$query = DB::connection()->prepare('INSERT INTO Requiredinfo (question, experiment_id) VALUES (:question, :experiment_id) RETURNING id');
    	$query->execute(array('question' => $this->quesion, 'experiment_id' => $this->experiment_id));
    	$row = $query->fetch();
    	$this->id = $row['id'];
  	}

}
