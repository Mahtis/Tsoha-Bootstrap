<?php

class ExperimentController extends BaseController {

	public static function availableExperiments() {
		//this needs to be changed to findAllActive when there are active experiments in the database
		$experiments = Experiment::findAll();
		View::make('experiment/subject_index.html', array('exps' => $experiments));
	}

    public static function listFreeUpcomingExperimentSlots($experiment_id){
        $experiment = Experiment::findOne($experiment_id);
        $timeSlots = TimeSlot::findUpcomingByExperimentAndFreeslots($experiment_id);
        View::make('experiment/experiment_reservation.html', array('experiment' => $experiment, 'timeSlots' => $timeSlots));
    }

	public static function createExperiment() {
		$params = $_POST;
    	
    	$experiment = new Experiment(array(
      		'name' => $params['name'],
      		'description' => $params['description'],
      		'maxSubjects' => $params['maxSubjects']));

    	$experiment->save();
        if(!empty($params['requiredInfo'])) {
            $requiredInfo = new RequiredInfo(array(
                'question' => $params['requiredInfo'],
                'experiment_id' => $experiment->id));
            $requiredInfo->save();
        }
    	Redirect::to('/experiment/' . $experiment->id . '/timeslots', array('message' => 'The  Experiment has been added.'));
	}

	public static function experimentCreationPage(){
        View::make('experiment/create_experiment.html');
    }

    public static function viewExperiment($id) {
    	$experiment = Experiment::findOne($id);
    	$timeSlots[] = TimeSlot::findByExperiment($experiment->$id);
    	View::make('experiment/experiment.html', array('experiment' => $experiment, 'timeSlots' => $timeSlots));
    }

}