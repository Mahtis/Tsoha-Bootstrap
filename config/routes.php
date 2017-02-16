<?php

  function check_logged_in() {
    BaseController::check_logged_in();
  }

  $routes->get('/', function() {
    ExperimentController::availableExperiments();
  });

  $routes->get('/create_experiment/','check_logged_in', function() {
    ExperimentController::experimentCreationPage();
  });

  $routes->post('/create_experiment/','check_logged_in', function() {
    ExperimentController::createExperiment();
  });

  $routes->get('/experiments/', function() {
    ExperimentController::availableExperiments();
  });

  $routes->get('/experiment/:id', function($id) {
    ExperimentController::listFreeUpcomingExperimentSlots($id);
  });

  $routes->get('/experiment/:id/edit', function($id) {
    ExperimentController::experimentUdpatePage($id);
  });

  $routes->post('/experiment/:id/edit', function($id) {
    ExperimentController::update($id);
  });

  $routes->get('/experiment/:experiment_id/reservation/:timeslot_id', function($experiment_id, $timeslot_id) {
    ReservationController::reservationPage($experiment_id, $timeslot_id);
  });

  $routes->post('/experiment/:experiment_id/reservation/:timeslot_id', function($experiment_id, $timeslot_id) {
    ReservationController::createReservation($experiment_id, $timeslot_id);
  });

  $routes->get('/experiment/:id/timeslots','check_logged_in', function($id) {
    TimeSlotController::listExperimentTimeSlots($id);
  });

  $routes->post('/experiment/:id/timeslots','check_logged_in', function($id) {
    TimeSlotController::createTimeSlotsToExperiment($id);
  });

  $routes->get('/labs','check_logged_in', function() {
    LaboratoryController::index();
  });

  $routes->post('/labs','check_logged_in', function() {
    LaboratoryController::store();
  });

  $routes->get('/labs/:id','check_logged_in', function($id) {
    LaboratoryController::getLab($id);
  });

  $routes->get('/labs/:id/edit','check_logged_in', function($id) {
    LaboratoryController::editPage($id);
  });

  $routes->post('/labs/:id/edit','check_logged_in', function($id) {
    LaboratoryController::update($id);
  });

  $routes->post('/labs/:id/delete','check_logged_in', function($id) {
    LaboratoryController::delete($id);
  });

  $routes->get('/login', function() {
    LabUserController::login();
  });

  $routes->post('/login', function() {
    LabUserController::handleLogin();
  });

  $routes->post('/logout', function() {
    LabUserController::logout();
  });

  $routes->get('/timeslots/:id','check_logged_in', function($id) {
    TimeSlotController::editPage($id);
  });

  $routes->post('/timeslots/:id','check_logged_in', function($id) {
    TimeSlotController::update($id);
  });

  $routes->post('/timeslots/:id/delete','check_logged_in', function($id) {
    TimeSlotController::delete($id);
  });

  $routes->get('/userpage','check_logged_in', function() {
    LabUserController::userpage();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
