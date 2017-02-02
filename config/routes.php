<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/create_experiment/', function() {
    HelloWorldController::create_experiment();
  });

  $routes->get('/experiment/', function() {
    HelloWorldController::experiment_reservation();
  });

  $routes->get('/experiment/:id', function($id) {
    HelloWorldController::experiment_reservation();
  });

  $routes->get('/labs', function() {
    LaboratoryController::index();
  });

  $routes->post('/labs', function() {
    LaboratoryController::store();
  });

  $routes->get('/labs/:id', function($id) {
    LaboratoryController::getLab($id);
  });

  $routes->get('/login', function() {
    HelloWorldController::login();
  });

  $routes->get('/timeslots/experiment/1', function() {
    HelloWorldController::add_timeslots();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
