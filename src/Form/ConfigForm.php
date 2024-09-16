<?php

namespace Drupal\flysystem_dropbox\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Stevenmaguire\OAuth2\Client\Provider\Dropbox;

/**
 * Module settings form.
 */
class ConfigForm extends ConfigFormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'flysystem_dropbox_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form = parent::buildForm($form, $form_state);

    // Default settings
    $config = $this->config('flysystem_dropbox.settings');

    // New Dropbox client so we can construct the authorization URL
    $authClient = new Dropbox([
      'clientId' => $config->get('flysystem_dropbox.client_id'),
      'clientSecret' => $config->get('flysystem_dropbox.client_secret'),
    ]);

    // Construct the authorization URL
    $authorization_url = $authClient->getAuthorizationUrl([
      'token_access_type' => 'offline',
      'scope' => 'account_info.read files.metadata.read files.content.read files.content.write',
    ]);

    // Help text
    $form['help'] = [
      '#type' => 'item',
      '#title' => t('Instructions'),
      '#markup' => $this->t('Enter your Dropbox ID and secret below. After saving the form, <a href="' . $authorization_url . '">click this link</a> to perform the one time refresh_token fetch with Dropbox. You will need to copy and paste the access code that Dropbox provides on the following screen to the access code field below.'),
    ];

    // Client ID field
    $form['client_id'] = [
      '#type' => 'textfield',
      '#title' => t('Client ID'),
      '#default_value' => $config->get('flysystem_dropbox.client_id'),
      '#description' => $this->t('Your Dropbox client ID.'),
    ];

    // Client Secret field
    $form['client_secret'] = [
      '#type' => 'textfield',
      '#title' => t('Client secret'),
      '#default_value' => $config->get('flysystem_dropbox.client_secret'),
      '#description' => $this->t('Your Dropbox client secret.'),
    ];

    // Access code field
    $form['access_code'] = [
      '#type' => 'textfield',
      '#title' => t('Access code'),
      '#default_value' => $config->get('flysystem_dropbox.access_code'),
      '#description' => $this->t('The access code you receive after providing the above details and clicking the link above.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('client_id') == NULL) {
      $form_state->setErrorByName('client_id', t('Please enter a valid client ID.'));
    }
    if ($form_state->getValue('client_secret') == NULL) {
      $form_state->setErrorByName('client_secret', t('Please enter a valid client secret.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('flysystem_dropbox.settings');
    $config->set('flysystem_dropbox.client_id', $form_state->getValue('client_id'));
    $config->set('flysystem_dropbox.client_secret', $form_state->getValue('client_secret'));
    $config->set('flysystem_dropbox.access_code', $form_state->getValue('access_code'));
    $config->save();
    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'flysystem_dropbox.settings',
    ];
  }
}
