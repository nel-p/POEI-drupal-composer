<?php

namespace Drupal\hello\Access;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Access\AccessCheckInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 *
 */
class HelloAccessCheck implements AccessCheckInterface {

  protected $time;

  public function __construct(TimeInterface $time) {
    $this->time = $time;
  }

  public function applies(Route $route) {
    return NULL;
  }

  public function access(Route $route, Request $request = NULL, AccountInterface $account) {
    $access = AccessResult::forbidden()->cachePerUser();

    if ($account->isAuthenticated()) {
      $createdDate = $account->getAccount()->created;
      // $currentDate = REQUEST_TIME;
      $currentDate = $this->time->getCurrentTime();

      if ( ($currentDate - $createdDate) > (48*3600) ) {
        $access = AccessResult::allowed()->cachePerUser();
      }
    }

    return $access;
  }
}
