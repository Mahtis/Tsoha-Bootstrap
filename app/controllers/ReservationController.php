<?php

class ReservationController extends BaseController {

	public static function reservationPage($experiment_id, $timeSlot_id) {
		$timeSlot = TimeSlot::findOne($timeSlot_id);
		$experiment = Experiment::findOne($experiment_id);
		$lab = Laboratory::findOne($timeSlot->laboratory_id);
		$user = LabUser::findOne($timeSlot->labuser_id);
		$time = date('l d.m.Y H:i', strtotime($timeSlot->startTime));
		$end = date('H:i', strtotime($timeSlot->endTime));
		$req = RequiredInfo::findByExperiment($experiment_id);
		View::make('reservation/reserve.html', array('slot' => $timeSlot, 'exp' => $experiment, 'time' => $time, 'end' => $end, 'user' => $user, 'lab' => $lab, 'req' => $req));
	}
	
	public static function createReservation($experiment_id, $timeSlot_id) {
		$params = $_POST;

		$reservation = new Reservation(array(
			'email' => $params['email'],
			'timeSlot_id' => $timeSlot_id));

		// check for errors before saving.
		$errors = $reservation->errors();

		// if errors, then display them
		if(count($errors) > 0) {
			Redirect::to('/experiment/' . $experiment_id, array('errors' => $errors));
		} else {
			// if no errors, try saving
			$reservation->createReservation();
			// if the save doesn't succeed, report error.
			if($reservation->id == null) {
				Redirect::to('/experiment/' . $experiment_id, array('errors' => array('Varaus epäonnistui. Joku muu saattoi ehtiä ensin. Ole hyvä ja yritä uudestaan.')));
			}
			// only save a response if reservation was created and there was a question.
			if(array_key_exists('response', $params) && $reservation->id != null) {
				$info = new SubjectInfo(array(
					'response' => $params['response'],
					'reservation_id' => $reservation->id,
					'requiredInfo_id' => $params['requiredInfo']));
				$info->save();
			}

			Redirect::to('/', array('message' => 'Aika onnistuneesti varattu!'));
		}

		

	}
}