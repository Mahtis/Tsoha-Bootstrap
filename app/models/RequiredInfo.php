<?php

class RequiredInfo extends BaseModel {

	public $id, $question, $experiment_id;

	public function __construct($attributes) {
		parent::__construct($attributes);
        $this->validators = array();
	}

    public static function findOne($id) {
        $query = DB::connection()->prepare('SELECT * FROM RequiredInfo WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if($row) {
            $requiredInfo = new Requiredinfo(array(
                'id' => $row['id'],
                'question' => $row['question'],
                'experiment_id' => $row['experiment_id']));
            return $requiredInfo;
        }
    }

    public static function findByExperiment($experiment_id) {
        $query = DB::connection()->prepare('SELECT * FROM RequiredInfo WHERE experiment_id = :experiment_id LIMIT 1');
        $query->execute(array('experiment_id' => $experiment_id));
        $row = $query->fetch();

        if($row) {
            $requiredInfo = new Requiredinfo(array(
                'id' => $row['id'],
                'question' => $row['question'],
                'experiment_id' => $experiment_id));
            return $requiredInfo;
        }
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
    	$query->execute(array('question' => $this->question, 'experiment_id' => $this->experiment_id));
    	$row = $query->fetch();
    	$this->id = $row['id'];
  	}

    public function update() {
        $query = DB::connection()->prepare('UPDATE RequiredInfo SET 
            question = :question, 
            experiment_id = :experiment_id WHERE id = :id');
        $query->execute(array(
            'id' => $this->id,
            'question' => $this->question, 
            'experiment_id' => $this->experiment_id));
    }

    public function delete() {
      $query = DB::connection()->prepare('DELETE FROM Requiredinfo WHERE id= :id');
      $query->execute(array('id' => $this->id));
    }

}
