<?php

namespace Drupal\annonce\EventSubscriber;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class AnnonceEventSubscriber implements EventSubscriberInterface {

  protected $currentUser;
  protected $currentRouteMatch;
  protected $_database;
  protected $_time;

  public function __construct(AccountProxyInterface $current_user, CurrentRouteMatch $current_route_match,
                              Connection $database, TimeInterface $time) {
    $this->currentUser = $current_user;
    $this->currentRouteMatch = $current_route_match;
    $this->_database = $database;
    $this->_time = $time;
  }

  static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('onRequest');
    return $events;
  }

  public function onRequest() {
    if ($this->currentRouteMatch->getRouteName() == 'entity.annonce.canonical') {
      $this->_database->insert('annonce_user_views')
        ->fields([
          'time' => $this->_time->getCurrentTime(),
          'uid' => $this->currentUser->id(),
          'aid' => $this->currentRouteMatch->getParameter('annonce')->id(),
        ])
        ->execute();
    }
  }

}
