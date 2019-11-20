<?php

namespace Drupal\date_condition\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Date' condition to enable a condition based in module selected status.
 *
 * @Condition(
 *   id = "date_condition",
 *   label = @Translation("Date"),
 * )
 *
 */
class DateCondition extends ConditionPluginBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * Creates a new DateCondition object.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['date_start'] = [
      '#type' => 'date',
      '#title' => $this->t('Start display at :'),
      '#description' => $this->t('Date to start display block.'),
      '#default_value' => $this->configuration['date_start'],
    ];
    $form['date_end'] = [
      '#type' => 'date',
      '#title' => $this->t('End display at:'),
      '#description' => $this->t('Date to end display block.'),
      '#default_value' => $this->configuration['date_end'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['date_start'] = $form_state->getValue('date_start');
    $this->configuration['date_end'] = $form_state->getValue('date_end');
    parent::submitConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['date_start' => '', 'date_end' => ''] + parent::defaultConfiguration();
  }

  /**
   * Evaluates the condition and returns TRUE or FALSE accordingly.
   *
   * @return bool
   *   TRUE if the condition has been met, FALSE otherwise.
   */
  public function evaluate() {
    $today = new DrupalDateTime('today');

    $start = $this->configuration['date_start'] ? new DrupalDateTime($this->configuration['date_start']) : NULL;
    $end = $this->configuration['date_end'] ? new DrupalDateTime($this->configuration['date_end']) : NULL;

    return (!$start || ($start <= $today)) && (!$end || ($end >= $today));
  }

  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    if ( !empty($form_state->getValue(['date_start'])) && !empty($form_state->getValue(['date_end'])) ) {
      $start = new DrupalDateTime($form_state->getValue(['date_start']));
      $end = new DrupalDateTime($form_state->getValue(['date_end']));

      if ($end < $start) {
        $form_state->setErrorByName('date_end', $this->t('date error !!'));
      }
    }
  }

  /**
   * Provides a human readable summary of the condition's configuration.
   */
  public function summary() {
    $module = $this->getContextValue('date_condition');
    $modules = system_rebuild_module_data();

    $status = ($modules[$module]->status)?t('enabled'):t('disabled');

    return t('The module @module is @status.', ['@module' => $module, '@status' => $status]);
  }

}
