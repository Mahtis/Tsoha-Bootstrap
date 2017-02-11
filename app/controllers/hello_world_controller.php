<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  View::make('suunnitelmat/index.html');
    }

    public static function experiment_reservation(){
   	  View::make('suunnitelmat/experiment.html');
    }

    public static function create_experiment(){
      View::make('suunnitelmat/create_experiment.html');
    }

    public static function laboratories(){
      View::make('suunnitelmat/laboratories.html');
    }

    public static function login(){
      View::make('suunnitelmat/login.html');
    }

    public static function add_timeslots(){
      View::make('suunnitelmat/timeslots.html');
    }

    public static function sandbox(){
      $doom = new TimeSlot(array(
        'startTime' => '2017-02-12 10:00',
        'endTime' => '2017-05-05 11:00',
        'maxReservations' => -1,
        'freeSlots' => -1,
        'labuser_id' => 1,
        'experiment_id' => 1,
        'laboratory_id' => 1,
        'experiment_id' => 1));
      $errors = $doom->errors();
      Kint::dump($errors);
    }
  }
