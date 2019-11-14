<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;


/**
 * Provides a hello block.
 *
 * @Block(
 *  id = "hello_block",
 *  admin_label = @Translation("Hello!")
 * )
 */
class HelloBlock extends BlockBase {

  /**
   * Implements Drupal\Core\Block\BlockBase::build().
   */
  public function build() {
    $dateFormatter = \Drupal::service('date.formatter');
    $time = \Drupal::service('datetime.time')->getCurrentTime();
    $user_name = \Drupal::currentUser()->getDisplayName();

    return array(
      '#markup' => $this->t(
        'Welcome %name !!! It is %time', [
          '%name' => $user_name,
          '%time' => $dateFormatter->format($time, 'custom', 'H:i:s'),
        ]
      ),
      '#cache' => [
        'max-age' => '0',
      ],
    );
  }

}
