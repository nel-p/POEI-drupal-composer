<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloController extends ControllerBase {

  public function content() {
    $name = $this->currentUser()->getDisplayName();

    return ['#markup' => t('Hello %name !!!', ['%name' => $name])];
  }

}
