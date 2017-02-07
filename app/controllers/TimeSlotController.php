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
		$len = count($params['startTime']);
		for ($i=0; $i < $len; $i++) {
			$date = new DateTime($params['startTime'][$i]);
			$start = date_timestamp_get($date);
			$slot = new TimeSlot(array(
				'startTime' => $start,
				'endTime' => $start,
				'maxReservations' => $params['maxReservations'][$i],
				'freeSlots' => $params['maxReservations'][$i],
				'labuser_id' => 1,
				'laboratory_id' => 1,
				'experiment_id' => $experiment_id));
			$slot->save();
		}
		Redirect::to('/experiment/' . $experiment_id . '/timeslots', array('message' => 'TimeSlots added.'));
	}

}

