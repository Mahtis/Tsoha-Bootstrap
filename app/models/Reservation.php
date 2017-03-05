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

  public static function findByLabAndTime($id, $start, $end) {
    $query = DB::connection()->prepare('SELECT t.* FROM Reservation r, TimeSlot t WHERE r.timeslot_id = t.id AND t.laboratory_id = :id AND t.starttime > :start AND t.endtime < :end');
    $query->execute(array('id' => $id, 'start' => $start, 'end' => $end));
    $rows = $query->fetchAll();
    $reservations = array();
    foreach($rows as $row){
      $startTime = date('G:i', strtotime($row['starttime']));
      $endTime = date('G:i', strtotime($row['endtime']));
      $user = LabUser::findOne($row['labuser_id']);
      $reservations[] = $startTime . '-' . $endTime . ' ' . $user->name;
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
    if ($row['n'] == null) {
      return 0;
    } else {
      return $row['n'];
    }
  }

  // when creating a reservation, the corresponding timeslots freeslots need to be updated, but also all other overlapping timeslots need to be deleted.
  public function createReservation() {
  	$dbh = DB::connection();
  	$dbh->beginTransaction();
    $timeSlot = TimeSlot::findOne($this->timeSlot_id);
    // check if timeslot is reservable
    if ($timeSlot->freeSlots == 0) {
      return null;
    }
  	$insertQuery = $dbh->prepare('INSERT INTO Reservation (email, timeslot_id) VALUES (:email, :timeslot_id) RETURNING id');
    $insertQuery->execute(array(
     	'email' => $this->email, 
     	'timeslot_id' => $timeSlot->id));
    $row = $insertQuery->fetch();
    $this->id = $row['id'];

    $updateQuery = $dbh->prepare('UPDATE TimeSlot SET freeslots=freeslots-1 WHERE id=:id');
    $updateQuery->execute(array('id' => $timeSlot->id));

    $deleteQuery = $dbh->prepare('DELETE FROM TimeSlot WHERE laboratory_id = :lab_id AND starttime <= :end AND :start <= endtime AND id != :id');
    $deleteQuery->execute(array('id'=> $timeSlot->id, 'start' => $timeSlot->startTime, 'end' => $timeSlot->endTime, 'lab_id' => $timeSlot->laboratory_id));

    $dbh->commit();
  }
}