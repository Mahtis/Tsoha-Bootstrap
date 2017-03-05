<?php

class TimeSlotController extends BaseController {

	// lists all timeslots associated with the experiment and also their reservations. 
	public static function listExperimentTimeSlots($experiment_id) {
		$experiment = Experiment::findOne($experiment_id);
		$timeSlots = TimeSlot::findByExperiment($experiment_id);
		$labs = Laboratory::findAll();
		$users = LabUser::findByExperiment($experiment_id);
		$reservations = array();
		$count = Reservation::countReservationsForExperiment($experiment_id);
		foreach($timeSlots as $slot) {
			$reservations[$slot->id] = Reservation::findByTimeSlot($slot->id);
		}
		$nextSlot = new TimeSlot(array(
			'startTime' => date('Y-m-dTG:i'),
			'endTime' => date('Y-m-dTG:i'),
			'maxReservations' => 1));
		View::make('timeslot/timeslots.html', array('experiment' => $experiment,'timeSlots' => $timeSlots, 'labs' => $labs, 'nextSlot' => $nextSlot, 'users' => $users, 'reservations' => $reservations, 'count' => $count));
	}

	// When creating new TimeSlot, need to do additional checks to check that the date is correct and that the lab is noot booked at the wanted time
	public static function createTimeSlotsToLab($laboratory_id) {
		$user = parent::get_user_logged_in();
		$params = $_POST;
		$startTime = $params['year'] . '-' . $params['month'] . '-' . $params['day'] . ' ' . $params['time'];
		$endTimestamp = strtotime($startTime . '+' . $params['duration']);
		$endTime = date('Y-m-d G:i', $endTimestamp);
		$experiment = Experiment::findOne($params['experiment_id']);
		$slot = new TimeSlot(array(
			'startTime' => $startTime,
			'endTime' => $endTime,
			'maxReservations' => $params['maxReservations'],
			'freeSlots' => $params['maxReservations'],
			'labuser_id' => $user->id,
			'laboratory_id' => $laboratory_id,
			'experiment_id' => $experiment->id));

		$errors = $slot->errors();
		
		$time = date('G:i', $endTimestamp);
		$nextSlot = new SlotInfo($params['day'], $params['month'], $params['year'], $time, $params['duration'], $experiment->id, $params['maxReservations']);

		// need to check if the date is valid before moving on and redirect if so, because the labcheck will crash the system if the date is incorrect.
		if(!checkdate($params['month'], $params['day'], $params['year'])) {
			$errors[] = 'Not a valid date.';
			Redirect::to('/labs/' . $laboratory_id, array('errors' => $errors, 'slot' => $nextSlot));
		}

		// check here if the lab is already booked at wanted time.
		if (Laboratory::isLabBooked($laboratory_id, $startTime, $endTime)) {
			$errors[] = 'The laboratory is already reserved at that time.';
		}
		if(count($errors) > 0) {
			Redirect::to('/labs/' . $laboratory_id, array('errors' => $errors, 'slot' => $nextSlot));
		} else {
			$slot->save();
			Redirect::to('/labs/' . $laboratory_id, array('message' => 'Time slot added: ' . $params['day'] . '.' . $params['month'] . ' ' . $params['time'] . ', to experiment: ' . $experiment->name, 'slot' => $nextSlot));
		}
		
	}

	public static function editPage($id) {
		$timeSlot = TimeSlot::findOne($id);
    	$day = date('d', strtotime($timeSlot->startTime));
    	$month = date('m', strtotime($timeSlot->startTime));
    	$year = date('Y', strtotime($timeSlot->startTime));
    	$time = date('G:i', strtotime($timeSlot->startTime));
    	$duration = '1 hours 00 minutes';
    	$experiment_id = $timeSlot->experiment_id;
    	$maxReservations = $timeSlot->maxReservations;
    	$oldSlot = new SlotInfo($day, $month, $year, $time, $duration, $experiment_id, $maxReservations);

		$labs = Laboratory::findAll();
		$experiments = Experiment::findAll();
		View::make('timeslot/edit.html', array('timeSlot' => $timeSlot, 'labs' => $labs, 'experiments' => $experiments, 'slot' => $oldSlot));
	}

	// Similar to creating a TimeSlot, need to check that the date is valid and lab is not booked.
	public static function update($id) {
		$user = parent::get_user_logged_in();
		$params = $_POST;
		$startTime = $params['year'] . '-' . $params['month'] . '-' . $params['day'] . ' ' . $params['time'];
		$endTimestamp = strtotime($startTime . '+' . $params['duration']);
		$endTime = date('Y-m-d G:i', $endTimestamp);
		$attributes = array(
			'id' => $id,
			'startTime' => $startTime,
			'endTime' => $endTime,
			'maxReservations' => $params['maxReservations'],
			'freeSlots' => $params['maxReservations'],
			'labuser_id' => $user->id,
			'laboratory_id' => $params['laboratory_id'],
			'experiment_id' => $params['experiment_id']);
		$slot = new TimeSlot($attributes);
		$errors = $slot->errors();

		// check if the date is valid.
		if(!checkdate($params['month'], $params['day'], $params['year'])) {
			$errors[] = 'Not a valid date.';
			Redirect::to('/timeslots/' . $slot->id, array('errors' => $errors, 'timeSlot' => $attributes));
		}

		// check here if the lab is already booked at wanted time.
		if (Laboratory::isLabBooked($params['laboratory_id'], $startTime, $endTime)) {
			$errors[] = 'The laboratory is already reserved at that time.';
		}

		if(count($errors) > 0) {
			Redirect::to('/timeslots/' . $slot->id, array('errors' => $errors, 'timeSlot' => $attributes));
		} else {
			$slot->update();
			Redirect::to('/timeslots/' . $slot->id, array('message' => 'time slot successfully updated.'));
		}
	}

	public static function delete($id) {
		$slot = TimeSlot::findOne($id);
		$experiment_id = $slot->experiment_id;
		$slot->delete();
		Redirect::to('/experiment/' . $experiment_id . '/timeslots', array('message' => 'TimeSlot deleted.'));
	}

}

