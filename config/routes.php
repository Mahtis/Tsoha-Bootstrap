<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/experiment', function() {
    HelloWorldController::experiment_reservation();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });
