<?php

namespace Drupal\decoupled_preview\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\Markup;
use Drupal\Core\Access\AccessResult;
use Drupal\node\Entity\Node;

/**
 * Returns responses for Decoupled Preview routes.
 */
class DecoupledPreviewController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build($node, $node_preview = FALSE) {
    $markup = '';

    $entityTypeManager = \Drupal::entityTypeManager();
    $alias = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $node);
    $storage = $entityTypeManager->getStorage('dp_preview_site');
    $ids = \Drupal::entityQuery('dp_preview_site')->execute();
    $sites = $storage->loadMultiple($ids);
    $nodeType = $entityTypeManager->getStorage('node')
      ->load($node)
      ->bundle();
    $enablePreview = FALSE;

    foreach ($sites as $site) {
      if ($site->checkEnabledContentType($nodeType)) {
        $enablePreview = TRUE;
      }
    }

    if ($enablePreview) {
      $previewForm = $this->formBuilder()->getForm('Drupal\decoupled_preview\Form\EditPreviewForm', $node_preview, $alias, $node);
      $renderer = \Drupal::service('renderer');
      $previewFormHtml = $renderer->render($previewForm);
      $markup .= $previewFormHtml;

      $build['content'] = [
        '#type' => 'item',
        '#markup' => Markup::create($markup),
      ];
    }
    else {
      $build['content'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('Decoupled Preview has not been configured for this content type.'),
      ];
    }
    return $build;

  }

  /**
   * Custom access check to determine if preview local task should display.
   *
   * @param $node
   *
   * @return Drupal\Core\Access\AccessResult
   *   Indicates access if at least one site is enabled for the current content
   *   type.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function isPreviewEnabled($node) {
    $entity = Node::load($node);
    $nodeType = $entity->getType();

    // A lot of this is copied from the build method. May be able to abstract
    // into a function.
    $entityTypeManager = \Drupal::entityTypeManager();
    $storage = $entityTypeManager->getStorage('dp_preview_site');
    $ids = \Drupal::entityQuery('dp_preview_site')->execute();
    $sites = $storage->loadMultiple($ids);
    $enablePreview = FALSE;

    foreach ($sites as $site) {
      if ($site->checkEnabledContentType($nodeType)) {
        $enablePreview = TRUE;
      }
    }
    return AccessResult::allowedIf($enablePreview);
  }

}
