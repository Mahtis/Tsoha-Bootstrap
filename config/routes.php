<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/experiment', function() {
    HelloWorldController::experiment_reservation();
  });

  $routes->get('/laboratories', function() {
    HelloWorldController::laboratories();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
