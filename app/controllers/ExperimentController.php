<?php

class ExperimentController extends BaseController {

	public static function availableExperiments() {
		//this needs to be changed to findAllActive when there are active experiments in the database
		$experiments = Experiment::findAll();
		View::make('experiment/subject_index.html', array('exps' => $experiments));
	}

	public static function createExperiment() {
		$params = $_POST;
    	
    	$experiment = new Experiment(array(
      		'name' => $params['name'],
      		'description' => $params['description'],
      		'maxSubjects' => $params['maxSubjects']));

    	$experiment->save();
    	if (array_key_exists('item', $params)) {
    		if (is_array($params['item'])) {
    			foreach ($params['item'] as $row) {
    				if (!$row.isEmpty()) {
    					$requiredInfo = new RequiredInfo(array(
    						'question' => $row,
    						'experiment_id' => $experiment->id));
    					$requiredInfo->save();
    				}
    			}
    		} else {
    			if (!$row.isEmpty()) {
    				$requiredInfo = new RequiredInfo(array(
    					'question' => $row,
    					'experiment_id' => $experiment->id));
    				$requiredInfo->save();
    			}
    		}
    	}
    	Redirect::to('/experiments/' . $experiment->id, array('message' => 'The  Experiment has been added.'));
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