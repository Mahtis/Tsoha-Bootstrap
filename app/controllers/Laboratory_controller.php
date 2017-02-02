<?php

class LaboratoryController extends BaseController {

	public static function index() {
		$labs = Laboratory::findAll();
		View::make('laboratory/labs.html', array('labs' => $labs));
	}
}