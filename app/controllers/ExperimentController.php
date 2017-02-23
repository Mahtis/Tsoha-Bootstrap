<?php

class ExperimentController extends BaseController {

	public static function availableExperiments() {
		//this needs to be changed to findAllActive when there are active experiments in the database
		$experiments = Experiment::findAllActive();
        $reserved = array();
        foreach($experiments as $exp) {
            $count = Reservation::countReservationsForExperiment($exp->id);
            if ($count != null) {
                $reserved[$exp->id] = $count;
            } else {
                $reserved[$exp->id] = 0;
            }
        }
		View::make('experiment/subject_index.html', array('exps' => $experiments, 'reserved' => $reserved));
	}

    public static function listAllExperiments() {
        $experiments = Experiment::findAll();
        View::make('experiment/list_experiments.html', array('exps' => $experiments));
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

        //check errors
        $errors = $experiment->errors();

        if(count($errors) > 0) {
            Redirect::to('/create_experiment/', array('errors' => $errors));
        } else {
            $experiment->save();
            //only if there are no errors, check if required info needs to be created.
            if(!empty($params['requiredInfo'])) {
                $requiredInfo = new RequiredInfo(array(
                    'question' => $params['requiredInfo'],
                    'experiment_id' => $experiment->id));
                $requiredInfo->save();
            }
            Redirect::to('/experiment/' . $experiment->id . '/timeslots', array('message' => 'The Experiment has been created.'));
        }
	}

	public static function experimentCreationPage(){
        View::make('experiment/create_experiment.html');
    }

    public static function viewExperiment($id) {
    	$experiment = Experiment::findOne($id);
    	$timeSlots[] = TimeSlot::findByExperiment($experiment->$id);
    	View::make('experiment/experiment.html', array('experiment' => $experiment, 'timeSlots' => $timeSlots));
    }

    public static function experimentUdpatePage($id) {
        $exp = Experiment::findOne($id);
        $req = RequiredInfo::findByExperiment($id);
        View::make('experiment/edit_experiment.html', array('exp' => $exp, 'req' => $req));
    }

    public static function update($id) {
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'maxSubjects' => $params['maxSubjects'],
            'description' => $params['description']);
        $exp = new Experiment($attributes);
        $errors = $exp->errors();
        $requiredInfo = null;
        if(!empty($params['requiredInfo'])) {
            $reqId = RequiredInfo::findByExperiment($id);
            $requiredInfo = new RequiredInfo(array(
                'id' => $reqId->id,
                'question' => $params['requiredInfo'],
                'experiment_id' => $id));
            $errors2 = $requiredInfo->errors();
            $errors = array_merge($errors, $errors2);
        }

        if(count($errors) > 0) {
            Redirect::to('/experiment/' . $id . '/edit', array('errors' => $errors, 'exp' => $attributes));
        } else {
            $exp->update();
            if($requiredInfo != null) {
                $requiredInfo->update();
            }
            Redirect::to('/experiment/' . $id . '/edit', array('message' => 'Experiment successfully updated.'));
        }
    }

    public static function addUserToExperiment() {
        $params = $_POST;
        $attributes = array(
            'labuser_id' => $params['user'],
            'experiment_id' => $params['exp']);
        $ue = new UserExperiment($attributes);
        if ($ue->save()){
            Redirect::to('/experiment/' . $ue->experiment_id . '/timeslots', array('message' => 'Experiment added to your list.'));
        } else {
            $errors = array();
            $errors[] = 'Experiment is already on your list.';
            Redirect::to('/experiment', array('errors' => $errors));
        }

        
    }

}