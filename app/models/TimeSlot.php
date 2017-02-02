<?php

class TimeSlot extends BaseModel {
	public $id, $startTime, $endTime, $maxReservations, $freeSlots;

	public function __construct($attributes) {
		parent::__construct($attributes);
	}

	public static function findAll() {
		$query = DB::connection()->prepare('SELECT * FROM TimeSlot');
		$query->execute();
		$rows = $query->fetchAll();
    	$timeSlots = array();
    	foreach($rows as $row){
      		$timeSlots[] = new TimeSlot(array(
        		'id' => $row['id'],
        		'startTime' => $row['starttime'],
        		'endTime' => $row['endtime'],
        		'maxReservations' => $row['maxreservations'],
        		'freeSlots' => $row['freeslots']));
    	}
    	return $timeSlots;
	}

	public static function findOne($id) {
		$query = DB::connection()->prepare('SELECT * FROM TimeSlot WHERE id = :id LIMIT 1');
    	$query->execute(array('id' => $id));
    	$row = $query->fetch();

    	if($row) {
    		$timeSlot = new TimeSlot(array(
    			'id' => $row['id'],
        		'startTime' => $row['starttime'],
        		'endTime' => $row['endtime'],
        		'maxReservations' => $row['maxreservations'],
        		'freeSlots' => $row['freeslots']));
    	}
    	return $timeSlot;
	}

  	public function save(){
    	$query = DB::connection()->prepare('INSERT INTO TimeSlot (starttime, endtime, maxreservations, freeslots) VALUES (:starttime, :endtime, :maxreservations, :freeslots) RETURNING id');
    	$query->execute(array('starttime' => $this->startTime, 'endtime' => $this->endTime, 'maxreservations' => $this->maxReservations, 'freeslots' => $this->$freeSlots));
    	$row = $query->fetch();
    	$this->id = $row['id'];
  	}


  	public function findByLabUser($labUser_id) {
  		$query = DB::connection()->prepare('SELECT * FROM TimeSlot t, LabUser u WHERE u.id=t.labuser_id AND t.labuser_id = :id');
		$query->execute(array('id' => $labUser_id));
		$rows = $query->fetchAll();
    	$timeSlots = array();
    	foreach($rows as $row){
      		$timeSlots[] = new TimeSlot(array(
        		'id' => $row['id'],
        		'startTime' => $row['starttime'],
        		'endTime' => $row['endtime'],
        		'maxReservations' => $row['maxreservations'],
        		'freeSlots' => $row['freeslots']));
    	}
    	return $timeSlots;
  	}

  	public function findUpcomingByLabUser($labUser_id) {
  		$curDate = date('Y-m-d');
  		$query = DB::connection()->prepare('SELECT * FROM TimeSlot t, LabUser u WHERE u.id=t.labuser_id AND t.labuser_id = :id AND t.starttime >= :date');
		$query->execute(array('id' => $labUser_id, 'date' => $curDate));
		$rows = $query->fetchAll();
    	$timeSlots = array();
    	foreach($rows as $row){
      		$timeSlots[] = new TimeSlot(array(
        		'id' => $row['id'],
        		'startTime' => $row['starttime'],
        		'endTime' => $row['endtime'],
        		'maxReservations' => $row['maxreservations'],
        		'freeSlots' => $row['freeslots']));
    	}
    	return $timeSlots;
  	}

  	public function findUpcomingByExperimentAndFreeslots() {
  		$curDate = date('Y-m-d');
  		$query = DB::connection()->prepare('SELECT * FROM TimeSlot t, Experiment e WHERE t.experiment_id=e.id AND t.experiment_id = :id AND t.starttime >= :date AND freeslots > 0');
		$query->execute(array('id' => $experiment_id, 'date' => $curDate));
		$rows = $query->fetchAll();
    	$timeSlots = array();
    	foreach($rows as $row){
      		$timeSlots[] = new TimeSlot(array(
        		'id' => $row['id'],
        		'startTime' => $row['starttime'],
        		'endTime' => $row['endtime'],
        		'maxReservations' => $row['maxreservations'],
        		'freeSlots' => $row['freeslots']));
    	}
    	return $timeSlots;
  	}

}