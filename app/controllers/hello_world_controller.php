<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  View::make('suunnitelmat/index.html');
    }

    public static function experiment_reservation(){
   	  View::make('suunnitelmat/experiment.html');
    }

    public static function laboratories(){
      View::make('suunnitelmat/laboratories.html');
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      echo 'Hello World!';
    }
  }
