<?php

class TimeSlot extends BaseModel {
	public $id, $startTime, $endTime, $maxReservations, $labuser_id, $experiment_id, $laboratory_id, $freeSlots;

	public function __construct($attributes) {
		parent::__construct($attributes);
    $this->validators = array('validateTime', 'validateMaxReservations');
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
        		'freeSlots' => $row['freeslots'],
            'labuser_id' => $row['labuser_id'],
            'experiment_id' => $row['experiment_id'],
            'laboratory_id' => $row['laboratory_id']));
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
        		'freeSlots' => $row['freeslots'],
            'labuser_id' => $row['labuser_id'],
            'experiment_id' => $row['experiment_id'],
            'laboratory_id' => $row['laboratory_id']));
    	}
    	return $timeSlot;
	}


  	// needed to find all timeslots for user
  	public static function findByLabUser($labUser_id) {
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
        		'freeSlots' => $row['freeslots'],
            'labuser_id' => $row['labuser_id'],
            'experiment_id' => $row['experiment_id'],
            'laboratory_id' => $row['laboratory_id']));
    	}
    	return $timeSlots;
  	}

    // find all timeslots for experiment
    public static function findByExperiment($experiment_id) {
      $query = DB::connection()->prepare('SELECT * FROM TimeSlot WHERE experiment_id = :id ORDER BY starttime DESC');
    $query->execute(array('id' => $experiment_id));
    $rows = $query->fetchAll();
      $timeSlots = array();
      foreach($rows as $row){
          $timeSlots[] = new TimeSlot(array(
            'id' => $row['id'],
            'startTime' => $row['starttime'],
            'endTime' => $row['endtime'],
            'maxReservations' => $row['maxreservations'],
            'freeSlots' => $row['freeslots'],
            'labuser_id' => $row['labuser_id'],
            'experiment_id' => $row['experiment_id'],
            'laboratory_id' => $row['laboratory_id']));
      }
      return $timeSlots;
    }

  	// needed to list user's own upcoming timeslots
  	// actually, what is needed are the upcoming reservations..
  	public static function findUpcomingByLabUser($labUser_id) {
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
        		'freeSlots' => $row['freeslots'],
            'labuser_id' => $row['labuser_id'],
            'experiment_id' => $row['experiment_id'],
            'laboratory_id' => $row['laboratory_id']));
    	}
    	return $timeSlots;
  	}

  	// needed to list free timeslots in a specific experiment for subjects
  	public static function findUpcomingByExperimentAndFreeslots($experiment_id) {
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
        		'freeSlots' => $row['freeslots'],
            'labuser_id' => $row['labuser_id'],
            'experiment_id' => $row['experiment_id'],
            'laboratory_id' => $row['laboratory_id']));
    	}
    	return $timeSlots;
  	}

    public function save(){
      $query = DB::connection()->prepare('INSERT INTO TimeSlot (starttime, endtime, maxreservations, freeslots, experiment_id, labuser_id, laboratory_id) VALUES (:starttime, :endtime, :maxreservations, :freeslots, :experiment_id, :labuser_id, :laboratory_id) RETURNING id');
      $query->execute(array(
        'starttime' => $this->startTime, 
        'endtime' => $this->endTime, 
        'maxreservations' => $this->maxReservations, 
        'freeslots' => $this->freeSlots, 
        'labuser_id' => $this->labuser_id, 
        'experiment_id' => $this->experiment_id, 
        'laboratory_id' => $this->laboratory_id));
      $row = $query->fetch();
      $this->id = $row['id'];
    }

    public function delete() {
      $query = DB::connection()->prepare('DELETE FROM TimeSlot WHERE id= :id');
      $query->execute(array('id' => $this->id));
    }

    public function update() {
      $query = DB::connection()->prepare('UPDATE TimeSlot SET 
        starttime = :starttime, 
        endtime = :endtime, 
        maxreservations = :maxreservations,
        freeslots = :freeslots,
        labuser_id = :labuser_id,
        experiment_id = :experiment_id,
        laboratory_id = :laboratory_id WHERE id = :id');
      $query->execute(array(
        'id' => $this->id,
        'starttime' => $this->startTime, 
        'endtime' => $this->endTime, 
        'maxreservations' => $this->maxReservations, 
        'freeslots' => $this->freeSlots, 
        'labuser_id' => $this->labuser_id, 
        'experiment_id' => $this->experiment_id, 
        'laboratory_id' => $this->laboratory_id));
    }

}