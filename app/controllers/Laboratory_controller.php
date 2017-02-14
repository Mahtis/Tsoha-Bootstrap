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

    	// Ohjataan käyttäjä lisäyksen jälkeen laboratorioiden listaussivulle
    	Redirect::to('/labs/' . $lab->id, array('message' => 'A new Laboratory has been added.'));
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