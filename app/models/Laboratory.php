<?php

class Laboratory extends BaseModel {
	
	public $id, $name, $location, $navigation, $equipment, $contactPerson;

	public function __construct($attributes) {
		parent::__construct($attributes);
        $this->validators = array('validateName', 'validateContactPerson');
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
        		'contactPerson' => $row['contactperson']));
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
        		'contactPerson' => $row['contactperson']));
    	}
    	return $lab;
	}

    // It would probably be more consistent to have this in TimeSlot.php, but since this function is strictly related to a specific lab, let's keep it here for now.
    public static function isLabBooked($id, $start, $end) {
        $query = DB::connection()->prepare('SELECT * FROM TimeSlot WHERE (starttime <= :end AND :start <= endtime) AND (freeslots < maxreservations OR maxreservations = 0) AND laboratory_id = :id');
        $query->execute(array('id' => $id, 'start' => $start, 'end' => $end));
        $rows = $query->fetchAll();
        if (empty($rows)) {
            return false;
        } else {
            return true;
        }
    }

	// Huomaathan, että save-metodi ei ole staattinen!
  	public function save(){
    	// Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
    	$query = DB::connection()->prepare('INSERT INTO Laboratory (name, location, navigation, equipment, contactperson) VALUES (:name, :location, :navigation, :equipment, :contactperson) RETURNING id');
    	// Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
    	$query->execute(array('name' => $this->name, 'location' => $this->location, 'navigation' => $this->navigation, 'equipment' => $this->equipment, 'contactperson' => $this->contactPerson));
    	// Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
    	$row = $query->fetch();
    	// Asetetaan lisätyn rivin id-sarakkeen arvo oliomme id-attribuutin arvoksi
    	$this->id = $row['id'];
  	}

    public function delete() {
        $query = DB::connection()->prepare('DELETE FROM Laboratory WHERE id= :id');
        $query->execute(array('id' => $this->id));
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Laboratory SET 
            name = :name, 
            location = :location, 
            navigation = :navigation,
            equipment = :equipment,
            contactperson = :contactperson WHERE id= :id');
        $query->execute(array('id' => $this->id,
            'name' => $this->name, 
            'location' => $this->location,
            'navigation' => $this->navigation,
            'equipment' => $this->equipment,
            'contactperson' => $this->contactPerson));

    }

}