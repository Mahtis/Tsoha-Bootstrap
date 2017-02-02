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

	public static function store() {
		// POST-pyynnön muuttujat sijaitsevat $_POST nimisessä assosiaatiolistassa
    	$params = $_POST;
    	// Alustetaan uusi olio käyttäjän syöttämillä arvoilla
    	$lab = new Laboratory(array(
      		'name' => $params['name'],
      		'location' => $params['location'],
      		'navigation' => $params['navigation'],
      		'equipment' => $params['equipment'],
      		'contactPerson' => $params['contactPerson']));

    	// Kutsutaan alustamamme olion save metodia, joka tallentaa olion tietokantaan
    	$lab->save();

    	// Ohjataan käyttäjä lisäyksen jälkeen pelin esittelysivulle
    	Redirect::to('/labs/' . $lab->id, array('message' => 'Peli on lisätty kirjastoosi!'));
	}
}