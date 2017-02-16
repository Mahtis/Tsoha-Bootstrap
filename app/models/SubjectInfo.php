<?php

class SubjectInfo extends BaseModel {

	public $id, $response, $reservation_id, $requiredInfo_id;

	public function __construct($attributes) {
		parent::__construct($attributes);
	}

	public static function findAll() {
		$query = DB::connection()->prepare('SELECT * FROM SubjectInfo');
		$query->execute();
		$rows = $query->fetchAll();
    	$infos = array();
    	foreach($rows as $row){
      		$infos[] = new SubjectInfo(array(
        		'id' => $row['id'],
        		'response' => $row['response'],
            	'reservation_id' => $row['reservation_id'],
            	'requiredInfo_id' => $row['requiredinfo_id']));
    	}
    	return $infos;
	}

	public function save() {
		$query = DB::connection()->prepare('INSERT INTO SubjectInfo (response, reservation_id, requiredinfo_id) VALUES (:response, :reservation_id, :requiredinfo_id) RETURNING id');
		$query->execute(array(
			'response' => $this->response,
			'reservation_id' => $this->reservation_id,
			'requiredinfo_id' => $this->requiredInfo_id));
		$row = $query->fetch();
		$this->id = $row['id'];
	}

}
