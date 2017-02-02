<?php

class LaboratoryController extends BaseController {

	public static function index() {
		$labs = Laboratory::findAll();
		View::make('laboratory/index.html', array('labs' => $labs));
	}

	public static function getLab($id) {
		$lab = Laboratory::findOne($id);
		View::make('laboratory/lab.html', array('lab' => $lab));
	}
}