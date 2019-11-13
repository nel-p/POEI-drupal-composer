<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;


/**
 * Provides a hello block.
 *
 * @Block(
 *  id = "hello_session_block",
 *  admin_label = @Translation("Actives sessions")
 * )
 */
class SessionBlock extends BlockBase {

  /**
   * Implements Drupal\Core\Block\BlockBase::build().
   */
  public function build() {
    $database = \Drupal::database();
    $n = $database->select('sessions', 's')->countQuery()->execute()->fetchField();

    return [
      '#markup' => $this->t('They are %n actives session !!!', ['%n' => $n]),
      '#cache' => ['max-age' => '0'],
    ];
  }

  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access_hello');
  }

}
