<?php

class SlotInfo {

	public $day, $month, $year, $time, $duration, $experiment_id, $maxReservations;

	public function __construct($day, $month, $year, $time, $duration, $experiment_id, $maxReservations) {
		$this->day = $day;
		$this->month = $month;
		$this->year = $year;
		$this->time = $time;
		$this->duration = $duration;
		$this->experiment_id = $experiment_id;
		$this->maxReservations = $maxReservations;
	}

}
