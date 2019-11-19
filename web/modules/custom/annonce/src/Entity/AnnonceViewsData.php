<?php

namespace Drupal\annonce\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Annonce entities.
 */
class AnnonceViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be put here.

    $data['annonce_user_views']['table']['group'] = $this->t('Annonce history');
    $data['annonce_user_views']['table']['provider'] = 'annonce';
    $data['annonce_user_views']['table']['base'] = [
      'field' => 'id',
      'title' => $this->t('Annonce history'),
      'help' => $this->t('Annonce history contains historical datas and can be related to annonces.'),
    ];
    $data['annonce_user_views']['uid'] = [
      'title' => $this->t('Annonce view user id'),
      'help' => $this->t('Annonce view user id'),
      'field' => ['id' => 'numeric'],
      'sort' => ['id' => 'standard'],
      'filter' => ['id' => 'numeric'],
      'argument' => ['id' => 'numeric'],
      'relationship' => [
        'base' => 'users_field_data',
        'base field' => 'uid',
        'id' => 'standard',
        'label' => $this->t('Annonce history UID -> User ID'),
      ],
    ];
    $data['annonce_user_views']['aid'] = [
      'title' => $this->t('Annonce id'),
      'help' => $this->t('Annonce content id'),
      'field' => ['id' => 'numeric'],
      'sort' => ['id' => 'standard'],
      'filter' => ['id' => 'numeric'],
      'argument' => ['id' => 'numeric'],
      'relationship' => [
        'base' => 'annonce_field_data',
        'base field' => 'id',
        'id' => 'standard',
        'label' => $this->t('Annonce history AID -> Annonce ID'),
      ],
    ];
    $data['annonce_user_views']['time'] = [
      'title' => $this->t('Annonce view date'),
      'help' => $this->t('Date the annonce was viewed'),
      'field' => ['id' => 'date'],
      'sort' => ['id' => 'date'],
      'filter' => ['id' => 'date'],
    ];

    return $data;
  }

}
