<?php

namespace Drupal\decoupled_preview\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides a Decoupled Preview form.
 */
class EditPreviewForm extends FormBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Stores the state storage service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Constructor for EditPreviewForm.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state key value store.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, StateInterface $state) {
    $this->entityTypeManager = $entity_type_manager;
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'decoupled_preview_edit_preview';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $uuid = FALSE, $alias = NULL, $nid = FALSE, $resourceVersionId = FALSE) {
    $entityTypeManager = $this->entityTypeManager;
    $storage = $entityTypeManager->getStorage('dp_preview_site');
    $sites = $storage->loadMultiple();
    $nodeType = $entityTypeManager->getStorage('node')
      ->load($nid)
      ->bundle();

    $siteCount = 0;
    foreach ($sites as $site) {
      if ($site->checkEnabledContentType($nodeType)) {
        $title = $site->label();
        $url = $site->get('url');
        $secret = $site->get('secret');

        if ($uuid) {
          $options = [
            'attributes' => ['target' => '_blank'],
            'query' => [
              'secret' => $secret,
              'slug' => $alias,
              'key' => $this->currentUser()->id() . '_' . $uuid,
            ],
          ];
          if ($resourceVersionId) {
            $options['query']['resourceVersionId'] = $resourceVersionId;
          }
        }
        else {
          $options = [
            'attributes' => ['target' => '_blank'],
            'query' => [
              'secret' => $secret,
              'slug' => $alias,
              'key' => $nid,
            ],
          ];
        }
        $url = Url::fromUri($url, $options)->toString();
        $view_mode_options[$url] = $title;
        ++$siteCount;
      }
    }

    $sourceRoute = $this->state->get('decoupled_preview_source_route');
    $this->state->delete('decoupled_preview_source_route');
    if ($siteCount == 1 && $sourceRoute !== 'entity.node.edit_form') {
      $response = new RedirectResponse($url);
      $response->send();
    }
    if (isset($view_mode_options)) {
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
    }

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
