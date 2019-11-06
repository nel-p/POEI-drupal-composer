<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\user\UserInterface;

class HelloUserStatsController extends ControllerBase {

  public function content(UserInterface $user) {

    $stats = \Drupal::database()->select('hello_user_statistics','hus')
      ->fields('hus', ['action','time'])
      ->condition('uid', $user->id())
      ->execute();

    $rows = [];
    foreach ($stats as $record) {
      $rows[] = [
        $record->action == '1' ? $this->t('Login') : $this->t('Logout'),
        \Drupal::service('date.formatter')->format($record->time),
      ];
    }

    return [
      '#type' => 'table',
      '#header'=> [$this->t('Action'), $this->t('Times')],
      '#rows'=> $rows,
      '#empty'=> $this->t('No connections yet'),
    ];
  }

}
