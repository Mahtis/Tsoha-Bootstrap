<?php

class LaboratoryController extends BaseController {

	public static function index() {
		$labs = Laboratory::findAll();
		View::make('laboratory/index.html', array('labs' => $labs));
	}

	public static function getLab($id) {
    $lab = Laboratory::findOne($id);
    $year = date('Y');
    $week = date('W');
    $headers = array();
    $reservations = array();
    $userReservations = array();
    $experiments = Experiment::findALL();
    $day = date('d', strtotime('tomorrow'));
    $month = date('m', strtotime('tomorrow'));
    $year = date('Y', strtotime('tomorrow'));
    $time = '12:00';
    $duration = '1 hours 00 minutes';
    $experiment_id = $experiments[0]->id;
    $maxReservations = 1;
    $defaultSlot = new SlotInfo($day, $month, $year, $time, $duration, $experiment_id, $maxReservations);
    for($day=1; $day<=7; $day++) {
      $headers[] =  date('D d.m', strtotime($year."W".$week.$day));
      $start = date('Y-m-d', strtotime($year."W".$week.$day)) . ' 01:00';
      $end = date('Y-m-d', strtotime($year."W".$week.$day)) . ' 23:00';
      $reservations[] = Reservation::findByLabAndTime($lab->id, $start, $end);
      $userReservations[] = TimeSlot::findNotBookableByLab($id, $start, $end);
    }
		View::make('laboratory/lab.html', array('lab' => $lab, 'headers' => $headers, 'reservations' => $reservations, 'experiments' => $experiments, 'userReservations' => $userReservations, 'slot' => $defaultSlot));
	}

	public static function store() {
    	$params = $_POST;

    	$lab = new Laboratory(array(
      		'name' => $params['name'],
      		'location' => $params['location'],
      		'navigation' => $params['navigation'],
      		'equipment' => $params['equipment'],
      		'contactPerson' => $params['contactPerson']));

      // check for errors before saving.
      $errors = $lab->errors();
      if(count($errors) > 0) {
        Redirect::to('/labs', array('errors' => $errors));
      } else {
        // if no errors then save.
        $lab->save();
        Redirect::to('/labs/' . $lab->id, array('message' => 'A new Laboratory has been added.'));
      }
	}

  public static function delete($id) {
    $lab = Laboratory::findOne($id);
    $lab->delete();
    Redirect::to('/labs', array('message' => 'Laboratory deleted.'));
  }

  public static function editPage($id) {
    $lab = Laboratory::findOne($id);
    View::make('laboratory/edit.html', array('lab' => $lab));
  }

  public static function update($id) {
    $params = $_POST;
    $attributes = array('id' => $id,
      'name' => $params['name'],
      'location' => $params['location'],
      'navigation' => $params['navigation'],
      'equipment' => $params['equipment'],
      'contactPerson' => $params['contactPerson']);
    $lab = new Laboratory($attributes);
    $errors = $lab->errors();

    if(count($errors) > 0) {
      Redirect::to('/labs/' . $lab->id . '/edit', array('errors' => $errors, 'lab' => $attributes));
    } else {
      $lab->update();
      Redirect::to('/labs/' . $lab->id, array('message' => 'Laboratory successfully updated.'));
    }
  }


}