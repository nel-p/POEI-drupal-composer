<?php

namespace Drupal\hello\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


class AdminForm extends ConfigFormBase {

  /**
   *{@inheritdoc}.
   */
  public function getFormId() {
    return 'hello_adminForm';
  }

  protected function getEditableConfigNames() {
    return ['hello.settings'];
  }

  /**
   *{@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

   $form['purge_days_number'] = [
      '#type' => 'select',
      '#title' => $this->t('How long to keep user activity statistics'),
      '#options' => [
        '0' => $this->t('Never purge'),
        '1' => $this->t('1 day'),
        '2' => $this->t('2 days'),
        '7' => $this->t('7 days'),
        '14' => $this->t('14 days'),
        '30' => $this->t('30 days'),
      ],
      '#default_value' => $this->config('hello.settings')->get('purge_days_number'),
    ];

    /*$form['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save configuration'),
    ];

    return $form;*/
    return parent::buildForm($form, $form_state);
  }

  /**
   *{@inheritdoc}.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('hello.settings')
      ->set('purge_days_number', $form_state->getValue('purge_days_number'))
      ->save();

      parent::submitForm($form, $form_state);
  }

}
