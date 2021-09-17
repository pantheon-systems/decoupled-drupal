<?php

namespace Drupal\decoupled_preview\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    // Alter the canonical preview route to our custom route.
    if ($route = $collection->get('entity.node.preview')) {
      $route->setPath('/node/{node_preview}/preview');
      $route->setDefault('_controller', '\Drupal\decoupled_preview\Controller\DecoupledPreviewController::build');
      // Confirm that this correctly enforces permission to view the node.
      $route->setRequirements(['_node_preview_access'], '{node_preview}');
    }
  }

}
