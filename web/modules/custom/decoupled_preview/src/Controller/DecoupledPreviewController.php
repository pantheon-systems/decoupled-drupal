<?php

namespace Drupal\decoupled_preview\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Returns responses for Decoupled Preview routes.
 */
class DecoupledPreviewController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build($node) {
    $markup = '';

    $is_preview = str_contains($node, '-');
    if ($is_preview) {
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

    $list = '';
    foreach ($links as $link) {
      $list .= '<li>' . $link . '</li>';
    }
    if ($list === '') {
      $list = '<li>' . $this->t('No preview sites available.') . '</li>';
    }

    $markup .= '<ul>' . $list . '</ul>';

    if ($is_preview) {
      $form_state = \Drupal::service('tempstore.private')->get('node_preview')->get($node);
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
      '#markup' => $markup,
    ];

    return $build;
  }

}
