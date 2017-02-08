<?php

class TimeSlotController extends BaseController {

	public static function listExperimentTimeSlots($experiment_id) {
		$experiment = Experiment::findOne($experiment_id);
		$timeSlots = TimeSlot::findByExperiment($experiment_id);
		$labs = Laboratory::findAll();
		View::make('timeslot/timeslots.html', array('experiment' => $experiment,'timeSlots' => $timeSlots, 'labs' => $labs));
	}

	public static function createTimeSlotsToExperiment($experiment_id) {
		$experiment = Experiment::findOne($experiment_id);
		$params = $_POST;
		$maxReservations = $params['maxReservations'];
		$len = count($maxReservations);
		for ($i=0; $i < $len; $i++) {
			$start = date('Y-m-d G:i', strtotime($params['startTime'][$i]));
			$end = date('Y-m-d G:i', strtotime($params['endTime'][$i]));
			$slot = new TimeSlot(array(
				'startTime' => $start,
				'endTime' => $end,
				'maxReservations' => $maxReservations[$i],
				'freeSlots' => $params['maxReservations'][$i],
				'labuser_id' => 1,
				'laboratory_id' => $params['laboratory_id'],
				'experiment_id' => $experiment_id));
			$slot->save();
		}
		Redirect::to('/experiment/' . $experiment_id . '/timeslots', array('message' => 'TimeSlots added.'));
	}

}

