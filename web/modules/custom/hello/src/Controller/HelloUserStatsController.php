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

    $rows = []; $nbLog = 0;
    foreach ($stats as $record) {
      if ($record->action == 1) $nbLog++;

      $rows[] = [
        $record->action == '1' ? $this->t('Login') : $this->t('Logout'),
        \Drupal::service('date.formatter')->format($record->time),
      ];
    }

    $table = [
      '#type' => 'table',
      '#header'=> [$this->t('Action'), $this->t('Times')],
      '#rows'=> $rows,
      '#empty'=> $this->t('No connections yet'),
    ];

    /*$myTemplate = [
      '#theme' => 'hello',
      '#data' => $this->t('The user %name has bin connected %nb times', [
        '%name'=> $user->getDisplayName(),
        '%nb' => $nbLog,
      ]),
    ];*/
    $myTemplate = [
      '#theme' => 'hello',
      '#userName'=> $user->getDisplayName(),
      '#countLog' => $nbLog,
    ];

    return [
      'myTemp' => $myTemplate,
      'table' => $table,
      '#cache' => ['max-age' => 0],
    ];
  }

}
