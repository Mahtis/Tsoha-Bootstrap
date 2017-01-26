<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/experiment/:id', function($id) {
    HelloWorldController::experiment_reservation();
  });

  $routes->get('/labs', function() {
    HelloWorldController::laboratories();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
