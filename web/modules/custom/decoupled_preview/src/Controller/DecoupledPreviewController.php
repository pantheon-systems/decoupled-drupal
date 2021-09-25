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

    $previewForm = $this->formBuilder()->getForm('Drupal\decoupled_preview\Form\EditPreviewForm', $node_preview, $alias, $node);
    $renderer = \Drupal::service('renderer');
    $previewFormHtml = $renderer->render($previewForm);
    $markup .= $previewFormHtml;

    $build['content'] = [
      '#type' => 'item',
      '#markup' => Markup::create($markup),
    ];

    return $build;
  }

}
