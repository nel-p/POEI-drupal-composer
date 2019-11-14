<?php

namespace Drupal\hello\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 *
 */
class HelloRouting extends RouteSubscriberBase {

  public function alterRoutes(RouteCollection $collection) {
    $route = $collection->get('entity.user.canonical');
    $route->setRequirements(['_access_hello' => '10']);
  }

}
