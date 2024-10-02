<?php

namespace Drupal\oe_virtual_assistant\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure settings for the Virtual Assistant.
 */
class VirtualAssistantSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['oe_virtual_assistant.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'oe_virtual_assistant_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('oe_virtual_assistant.settings');

    $form['backend_service_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Backend Service URL'),
      '#default_value' => $config->get('backend_service_url'),
      '#description' => $this->t('The URL of the backend service for the virtual assistant.'),
      '#required' => TRUE,
    ];
    $form['token_issuer_url'] = [
      '#type' => 'url',
      '#title' => $this->t('JWT issuer URL'),
      '#default_value' => $config->get('token_issuer_url'),
      '#description' => $this->t('The URL of the JWT token issuer. Leave empty to use the current site URL.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable('oe_virtual_assistant.settings')
      ->set('backend_service_url', $form_state->getValue('backend_service_url'))
      ->set('token_issuer_url', $form_state->getValue('token_issuer_url'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
