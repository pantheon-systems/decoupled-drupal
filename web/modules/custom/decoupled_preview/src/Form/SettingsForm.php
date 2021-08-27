<?php

namespace Drupal\decoupled_preview\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Decoupled Preview settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'decoupled_preview_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['decoupled_preview.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['preview_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Preview URL'),
      '#default_value' => $this->config('decoupled_preview.settings')->get('preview_url'),
    ];
    // TODO - Prepopulate this with a randomly generated token
    $form['preview_secret'] = [
      '#type' => 'password',
      '#title' => $this->t('Preview Secret'),
      '#default_value' => $this->config('decoupled_preview.settings')->get('preview_secret'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  // TODOD - Add validation for URL
  // public function validateForm(array &$form, FormStateInterface $form_state) {
  //   if ($form_state->getValue('example') != 'example') {
  //     $form_state->setErrorByName('example', $this->t('The value is not correct.'));
  //   }
  //   parent::validateForm($form, $form_state);
  // }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('decoupled_preview.settings')
      ->set('preview_url', $form_state->getValue('preview_url'))
      ->set('preview_secret', $form_state->getValue('preview_secret'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
