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
		$endTimestamp = strtotime($startTime . '+' . $params['duration']);
		$endTime = date('Y-m-d G:i', $endTimestamp);
		$slot = new TimeSlot(array(
			'startTime' => $startTime,
			'endTime' => $endTime,
			'maxReservations' => $params['maxReservations'],
			'freeSlots' => $params['maxReservations'],
			'labuser_id' => 1,
			'laboratory_id' => $params['laboratory_id'],
			'experiment_id' => $experiment_id));

		$errors = $slot->errors();

		if(count($errors) > 0) {
			Redirect::to('/experiment/' . $experiment_id . '/timeslots', array('errors' => $errors));
		} else {
			$slot->save();
			Redirect::to('/experiment/' . $experiment_id . '/timeslots', array('message' => 'TimeSlot added.'));
		}
		
	}

	public static function editPage($id) {
		$timeSlot = TimeSlot::findOne($id);
		$labs = Laboratory::findAll();
		$experiments = Experiment::findAll();
		View::make('timeslot/edit.html', array('timeSlot' => $timeSlot, 'labs' => $labs, 'experiments' => $experiments));
	}

	public static function update($id) {
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
			'labuser_id' => 1,
			'laboratory_id' => $params['laboratory_id'],
			'experiment_id' => $params['experiment_id']);
		$slot = new TimeSlot($attributes);
		$errors = $slot->errors();

		if(count($errors) > 0) {
			Redirect::to('/timeslots/' . $slot->id, array('errors' => $errors, 'attributes' => $attributes));
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

