<?php

namespace Drupal\decoupled_preview\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Render\Markup;

/**
 * Returns responses for Decoupled Preview routes.
 */
class DecoupledPreviewController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build($node, $node_preview = FALSE) {
    $markup = '';

    if ($node_preview) {
      $markup .= '<p>PREVIEW DURING EDIT MODE</p>';
    }

    $alias = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $node);
    $storage = \Drupal::entityTypeManager()->getStorage('dp_preview_site');
    $ids = \Drupal::entityQuery('dp_preview_site')->execute();
    $sites = $storage->loadMultiple($ids);

    $links = [];

    foreach ($sites as $site) {
      $title = $site->label();
      $url = $site->get('url');
      $secret = $site->get('secret');

      $options = [
        'query' => [
          'secret' => $secret,
          'slug' => $alias
        ],
        'attributes' => ['target' => '_blank'],
      ];

      $link = Link::fromTextAndUrl($title, Url::fromUri($url, $options));
      $links[] = $link->toString();
    }

    $previewForm = $this->formBuilder()->getForm('Drupal\decoupled_preview\Form\EditPreviewForm', $node, $alias);
    $renderer = \Drupal::service('renderer');
    $previewFormHtml = $renderer->render($previewForm);
    $markup .= $previewFormHtml;

    if ($node_preview) {
      $form_state = \Drupal::service('tempstore.private')->get('node_preview')->get($node_preview);
      $entity = $form_state->getFormObject()->getEntity();

      $body = $entity->get('body')->value;
      $markup .= "<p>Body: {$body}</p>";

      $serializer = \Drupal::service('serializer');
      $data = $serializer->serialize(
        $entity,
        'json',
        ['plugin_id' => 'entity']
      );
      $markup .= "<p>Serialized Data: {$data}</p>";
    }

    $build['content'] = [
      '#type' => 'item',
      '#markup' => Markup::create($markup),
    ];

    return $build;
  }

}
