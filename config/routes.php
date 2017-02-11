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
    LabUserController::login();
  });

  $routes->post('/login', function() {
    LabUserController::handleLogin();
  });

  $routes->get('/timeslots/:id', function($id) {
    TimeSlotController::editPage($id);
  });

  $routes->post('/timeslots/:id', function($id) {
    TimeSlotController::update($id);
  });

  $routes->post('/timeslots/:id/delete', function($id) {
    TimeSlotController::delete($id);
  });

  $routes->get('/userpage', function() {
    LabUserController::userpage();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
