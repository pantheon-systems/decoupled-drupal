<?php

namespace Drupal\decoupled_preview\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Preview Site form.
 *
 * @property \Drupal\decoupled_preview\DpPreviewSiteInterface $entity
 */
class DpPreviewSiteForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {

    $form = parent::form($form, $form_state);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t('Label for the preview site.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\decoupled_preview\Entity\DpPreviewSite::load',
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

    $form['url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->get('url'),
      '#description' => $this->t('URL for the preview site.'),
      '#required' => TRUE,
    ];

    $form['secret'] = [
      '#type' => 'password',
      '#title' => $this->t('Secret'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->get('secret'),
      '#description' => $this->t('Shared secret for the preview site.'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);
    $message_args = ['%label' => $this->entity->label()];
    $message = $result == SAVED_NEW
      ? $this->t('Created new preview site %label.', $message_args)
      : $this->t('Updated preview site %label.', $message_args);
    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }
}
