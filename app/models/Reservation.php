<?php

class Reservation extends BaseModel {

	public $id, $email, $timeSlot_id, $startTime, $endTime;

	public function __construct($attributes) {
		parent::__construct($attributes);
    $this->validators = array('validateEmail');
	}

	public static function findALL() {
		$query = DB::connection()->prepare('SELECT * FROM Reservation');
		$query->execute();
		$rows = $query->fetchAll();
    	$reservations = array();
    	foreach($rows as $row){
      		$reservations[] = new Reservation(array(
        		'id' => $row['id'],
        		'email' => $row['email'],
            'timeslot_id' => $row['timeslot_id']));
    	}
    return $reservations;
	}

  public static function findByTimeSlot($id) {
    $query = DB::connection()->prepare('SELECT * FROM Reservation WHERE timeslot_id = :id');
    $query->execute(array('id' => $id));
    $rows = $query->fetchAll();
      $reservations = array();
      foreach($rows as $row){
          $reservations[] = new Reservation(array(
            'id' => $row['id'],
            'email' => $row['email'],
            'timeslot_id' => $row['timeslot_id']));
      }
    return $reservations;
  }

	// needed to list user's own upcoming reservations
  public static function findUpcomingByLabUser($labUser_id) {
  	$curDate = date('Y-m-d');
  	$query = DB::connection()->prepare('SELECT r.*, t.startTime, t.endTime FROM Reservation r, TimeSlot t, LabUser u WHERE u.id=t.labuser_id AND t.id=r.timeslot_id AND t.labuser_id = :id AND t.starttime >= :date');
		$query->execute(array('id' => $labUser_id, 'date' => $curDate));
		$rows = $query->fetchAll();
    $reservations = array();
    foreach($rows as $row){
      $reservations[] = new Reservation(array(
        'id' => $row['id'],
        'email' => $row['email'],
        'timeSlot_id' => $row['timeslot_id'],
        'startTime' => $row['starttime'],
        'endTime' => $row['endtime']));
    	}
    return $reservations;
  }

  public static function countReservationsForExperiment($exp_id) {
    $query = DB::connection()->prepare('SELECT t.experiment_id AS id, COUNT(t.experiment_id) AS n FROM Reservation r, TimeSlot t WHERE r.timeslot_id=t.id AND t.experiment_id=:id GROUP BY t.experiment_id');
    $query->bindParam('id', $exp_id);
    $query->execute();
    $row = $query->fetch();
    return $row['n'];
  }

  /* //this doesn't work.
  public static function countReservationsForExperiments($exp_id) {
    $query = DB::connection()->prepare('SELECT t.experiment_id AS id, COUNT(t.experiment_id) AS n FROM Reservation r, TimeSlot t WHERE r.timeslot_id=t.id AND t.experiment_id= ANY (:id) GROUP BY t.experiment_id');
    $param = "{".implode(', ',$exp_ids)."}";
    $query->bindParam('id', $param);
    $query->execute();
    $rows = $query->fetchAll();
    $resCounts = array();
    foreach($rows as $row){
      $resCounts[] = new ReservationCount(array(
      'experiment_id' => $row['id'],
      'rCount' => $row['n']));
    }
    return $resCounts;
  }*/

  public function createReservation() {
  	$dbh = DB::connection();
  	$dbh->beginTransaction();
  	$query = $dbh->prepare('INSERT INTO Reservation (email, timeslot_id) VALUES (:email, :timeslot_id) RETURNING id');
    $query->execute(array(
     	'email' => $this->email, 
     	'timeslot_id' => $this->timeSlot_id));
    $row = $query->fetch();
    $this->id = $row['id'];
    $query2 = $dbh->prepare('UPDATE TimeSlot SET freeslots=freeslots-1 WHERE id=:id');
    $query2->execute(array('id' => $this->timeSlot_id));
    $dbh->commit();
  }
}