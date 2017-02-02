<?php

class Laboratory extends BaseModel {
	
	public $id, $name, $location, $navigation, $equipment, $contactPerson

	public function __construct($attributes)
	{
		parent::__construct($attributes);
	}

	public static function findAll() {
		// Alustetaan kysely tietokantayhteydellämme
    	$query = DB::connection()->prepare('SELECT * FROM Laboratory');
    	// Suoritetaan kysely
    	$query->execute();
    	// Haetaan kyselyn tuottamat rivit
    	$rows = $query->fetchAll();
    	$labs = array();

    	// Käydään kyselyn tuottamat rivit läpi
    	foreach($rows as $row){
      		// Tämä on PHP:n hassu syntaksi alkion lisäämiseksi taulukkoon
      		$labs[] = new Laboratory(array(
        		'id' => $row['id'],
        		'name' => $row['name'],
        		'location' => $row['location'],
        		'navigation' => $row['navigation'],
        		'equipment' => $row['equipment'],
        		'contactPerson' => $row['contactPerson']));
    	}
    return $labs;
	}

	public static function findOne($id) {
		$query = DB::connection()->prepare('SELECT * FROM Laboratory WHERE id = :id LIMIT 1');
    	$query->execute(array('id' => $id));
    	$row = $query->fetch();

    	if($row) {
    		$lab = new Laboratory(array(
    			'id' => $row['id'],
        		'name' => $row['name'],
        		'location' => $row['location'],
        		'navigation' => $row['navigation'],
        		'equipment' => $row['equipment'],
        		'contactPerson' => $row['contactPerson']));
    	}
    	return $lab;
	}

	

}