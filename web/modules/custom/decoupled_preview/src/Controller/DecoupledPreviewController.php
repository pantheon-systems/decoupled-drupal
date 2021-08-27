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
    $alias = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $node);

    $preview_url = \Drupal::config('decoupled_preview.settings')->get('preview_url');
    $preview_secret = \Drupal::config('decoupled_preview.settings')->get('preview_secret');

    $options = [
      'query' => [
        'secret' => $preview_secret,
        'slug' => $alias
      ],
      'attributes' => ['target' => '_blank'],
    ];

    $link = Link::fromTextAndUrl('Open in new tab', Url::fromUri($preview_url, $options));

    $build['content'] = [
      '#type' => 'item',
      '#markup' => "<p>Preview: {$link->toString()} </p>",
    ];

    return $build;
  }

}
