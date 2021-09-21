<?php

namespace Drupal\decoupled_preview\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides a Decoupled Preview form.
 */
class EditPreviewForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'decoupled_preview_edit_preview';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $uuid = FALSE, $alias = NULL) {
    $storage = \Drupal::entityTypeManager()->getStorage('dp_preview_site');
    $ids = \Drupal::entityQuery('dp_preview_site')->execute();
    $sites = $storage->loadMultiple($ids);

    foreach ($sites as $site) {
      $title = $site->label();
      $url = $site->get('url');
      $secret = $site->get('secret');

      if ($uuid) {
        $options = [
          'query' => [
            'secret' => $secret,
            'slug' => $alias,
            'key' => \Drupal::currentUser()->id() . '_' . $uuid,
          ],
        ];
      }
      else {
        $options = [
          'query' => [
            'secret' => $secret,
            'slug' => $alias,
          ],
        ];
      }

      $url = Url::fromUri($url, $options)->toString();
      $view_mode_options[$url] = $title;
    }

    $form['preview_site'] = [
      '#type' => 'select',
      '#title' => $this->t('Preview Site'),
      '#options' => $view_mode_options,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Preview'),
    ];

    $form['#attributes']['target'] = '_blank';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $response = new RedirectResponse($form_state->getValue('preview_site'));

    $response->send();
  }

}
