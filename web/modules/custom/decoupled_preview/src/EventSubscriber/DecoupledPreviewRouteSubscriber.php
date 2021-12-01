<?php

namespace Drupal\decoupled_preview\EventSubscriber;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Decoupled Preview route subscriber.
 */
class DecoupledPreviewRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('entity.node.version_history')) {
      $route->setDefault('_controller', '\Drupal\decoupled_preview\Controller\RevisionOverviewController::revisionOverview');
    }
  }

}
