<?php

class TimeSlotController extends BaseController {

	public static function listExperimentTimeSlots($experiment_id) {
		$experiment = Experiment::findOne($experiment_id);
		$timeSlots = TimeSlot::findByExperiment($experiment_id);
		$labs = Laboratory::findAll();
		$nextSlot = new TimeSlot(array(
			'startTime' => date('Y-m-dTG:i'),
			'endTime' => date('Y-m-dTG:i'),
			'maxReservations' => 1));
		View::make('timeslot/timeslots.html', array('experiment' => $experiment,'timeSlots' => $timeSlots, 'labs' => $labs, 'nextSlot' => $nextSlot));
	}

	public static function createTimeSlotsToExperiment($experiment_id) {
		$experiment = Experiment::findOne($experiment_id);
		$params = $_POST;
		$startTime = $params['year'] . '-' . $params['month'] . '-' . $params['day'] . ' ' . $params['time'];
		$endTimestamp = strtotime($params['time'] . '+' . $params['duration']);
		$endTime = date('Y-m-d G:i', $endTimestamp);
		$slot = new TimeSlot(array(
			'startTime' => $startTime,
			'endTime' => $endTime,
			'maxReservations' => $params['maxReservations'],
			'freeSlots' => $params['maxReservations'],
			'labuser_id' => 1,
			'laboratory_id' => $params['laboratory_id'],
			'experiment_id' => $experiment_id));
		$slot->save();
		
		Redirect::to('/experiment/' . $experiment_id . '/timeslots', array('message' => 'TimeSlot added.'));
	}

}

