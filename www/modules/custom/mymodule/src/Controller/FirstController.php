<?php

/**
 * @file
 * Controller is wired up via mymodule.routing.yml file.
 */

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;

class FirstController extends ControllerBase {

  public function simpleContent() {
    return [
      '#type' => 'markup',
      '#markup' => t('Hello from FirstController::simpleContent()'),
    ];
  }

  public function variableContent($name_1, $name_2) {
    return [
      '#type' => 'markup',
      '#markup' => t(
        'Hello @name_1 and @name_2, from FirstController::variableContent()', 
        [
        '@name_1' => $name_1,
        '@name_2' => $name_2,
      ]),
    ];
    
  }

}