<?php

class ReservationCount extends BaseModel {

	public $experiment_id, $rCount;

	public function __construct($attributes) {
		parent::__construct($attributes);
	}

}