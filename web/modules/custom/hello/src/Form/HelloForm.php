<?php

namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;


class HelloForm extends FormBase {

  /**
   *{@inheritdoc}.
   */
  public function getFormId() {
    return 'hello_form';
  }

  /**
   *{@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    if (isset($form_state->getRebuildInfo()['result'])) {
      $form['result'] = [
        '#type' => 'html_tag',
        '#tag' => 'h1',
        '#value' => $this->t('Result : '.$form_state->getRebuildInfo()['result']),
      ];
    }

    $form['first_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First value'),
      '#description' => $this->t('Enter first value'),
      '#ajax' => [
        'callback' => [$this, 'validateTextAjax'],
        'event'=> 'keyup',
      ],
      '#prefix' => '<span class="msg-first_value"></span>',
    ];

    $form['operation'] = [
      '#type' => 'radios',
      '#options' => [
        'add' => $this->t('Add'),
        'soustract' => $this->t('Soustract'),
        'multiply' => $this->t('Multiply'),
        'divide' => $this->t('Divide'),
      ],
      '#default_value' => 'add',
      '#description' => $this->t('Choose operation for processing'),
    ];

    $form['second_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Second value'),
      '#description' => $this->t('Enter second value'),
      '#ajax' => [
        'callback' => [$this, 'validateTextAjax'],
        'event'=> 'keyup',
      ],
      '#prefix' => '<span class="msg-second_value"></span>',
    ];

    $form['calculate'] = [
      '#type' => 'submit',
      '#value' => $this->t('Calculate'),
    ];

    return $form;
  }

  /**
   *{@inheritdoc}.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $value1 = $form_state->getValue('first_value');
    $value2 = $form_state->getValue('second_value');
    $operation = $form_state->getValue('operation');

    switch ($operation) {
      case 'add':
        $result = $value1 + $value2;
        break;
      case 'soustract':
        $result = $value1 - $value2;
        break;
      case 'multiply':
        $result = $value1 * $value2;
        break;
      case 'divide':
        $result = $value1 / $value2;
        break;
    }

    $form_state->addRebuildInfo('result', $result);
    $form_state->setRebuild();

    \Drupal::state()->set('hello_form_submission_time', REQUEST_TIME);
  }

  /**
   *{@inheritdoc}.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $value1 = $form_state->getValue('first_value');
    $value2 = $form_state->getValue('second_value');
    $operation = $form_state->getValue('operation');

    if (!is_numeric($value1)) {
      $form_state->setErrorByName('first_value', $this->t('First value must be numeric!!'));
    }

    if (!is_numeric($value2)) {
      $form_state->setErrorByName('second_value', $this->t('Second value must be numeric!!'));
    }

    if ($operation == 'divide' && $value2 == '0') {
      $form_state->setErrorByName('second_value', $this->t('Second value can\'t be 0!!'));
    }
  }

  public function validateTextAjax(array &$form, FormStateInterface $form_state) {
    $ajax = new AjaxResponse();
    $field = $form_state->getTriggeringElement()['#name'];

    if (!is_numeric($form_state->getValue($field))) {
      $css = ['border' => '2px solid red'];
      $msg = $this->t('must be numeric !!!');
    } else {
      $css = ['border' => '2px solid green'];
      $msg = $this->t('ok');
    }

    $ajax->addCommand(new CssCommand('#edit-'.str_replace('_', '-', $field), $css));
    $ajax->addCommand(new HtmlCommand('.msg-'.$field , $msg));

    return $ajax;
  }

}
