<?php

  class HelloWorldController extends BaseController{

    public static function sandbox(){
      $count = Reservation::countReservationsForExperiment(11);
      Kint::dump($count);
      if ($count == null) {
        $count = 0;
      }
      Kint::dump($count);
    }

}
