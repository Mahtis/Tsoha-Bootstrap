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
    Laboratory_controller::index();
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
