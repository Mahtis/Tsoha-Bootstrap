<?php

  $routes->get('/', function() {
    ExperimentController::availableExperiments();
  });

  $routes->get('/create_experiment/', function() {
    ExperimentController::experimentCreationPage();
  });

  $routes->post('/create_experiment/', function() {
    ExperimentController::createExperiment();
  });

  $routes->get('/experiments/', function() {
    ExperimentController::availableExperiments();
  });

  $routes->get('/experiment/:id', function($id) {
    HelloWorldController::experiment_reservation();
  });

  $routes->get('/experiment/:id/timeslots', function($id) {
    TimeSlotController::listExperimentTimeSlots($id);
  });

  $routes->post('/experiment/:id/timeslots', function($id) {
    TimeSlotController::createTimeSlotsToExperiment($id);
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
