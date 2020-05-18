<?php
namespace Drupal\badgr\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

class ConfigForm extends ConfigFormBase {

  const CONFIG_NAME = 'badgr.settings';

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return [self::CONFIG_NAME];
  }

  /**
   * Returns this modules configuration object.
   */
  protected function getConfig() {
    return $this->config(self::CONFIG_NAME);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->getConfig();
    $values = $form_state->getValues();
    foreach ($values as $key => $value) {
      $config->set($key, $value);
    }
    $config->save();
    parent::submitForm($form,$form_state);
  }

  /**
   * @return string
   */
  public function getFormId() {
    return 'badgr_settings_form';
  }


  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->getConfig();

    $form['user'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Badgr API e-Mail'),
      '#default_value' => $config->get('user'),
      '#description' => $this->t('Your Badgr API username.'),
    ];

    $form['pass'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Badgr API Password'),
      '#default_value' => $config->get('pass'),
      '#description' => $this->t('Your Badgr API password.'),
    ];

    $form['base_uri'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Badgr API Base URL'),
      '#default_value' => $config->get('base_uri'),
      '#description' => $this->t('Include trailing slash. "https://api.badgr.io/"'),
    ];

    return parent::buildForm($form, $form_state);
  }

}