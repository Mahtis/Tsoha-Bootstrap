<?php

  class BaseModel{
    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null){
      // Käydään assosiaatiolistan avaimet läpi
      foreach($attributes as $attribute => $value){
        // Jos avaimen niminen attribuutti on olemassa...
        if(property_exists($this, $attribute)){
          // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
          $this->{$attribute} = $value;
        }
      }
    }

    public function errors(){
      // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
      $errors = array();

      foreach($this->validators as $validator){
        // Kutsu validointimetodia tässä ja lisää sen palauttamat virheet errors-taulukkoon
        $validatorErrors = $this->{$validator}();
        $errors = array_merge($errors, $validatorErrors);
      }

      return $errors;
    }

    public function validateName() {
      $errors = array();
      if($this->name == '' || $this->name == null) {
        $errors[] = 'Name cannot be empty.';
      }
      return $errors;
    }

    public function validateTime() {
      $errors = array();
      if($this->startTime < date('Y-m-d G:i')) {
        $errors[] = 'Cannot add slots to the past.';
      }
      if($this->endTime < $this->startTime) {
        $errors[] = 'Cannot end before starting.';
      }
      return $errors;
    }

      public function validateMaxReservations() {
        $errors = array();
        if($this->maxReservations < 0 || $this->maxReservations == null) {
          $errors[] = 'Max number has to be positive';
        }
        return $errors;
      }

  }
