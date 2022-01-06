<?php

namespace Drupal\decoupled_preview\Controller;

use Drupal\Core\Url;
use Drupal\node\Controller\NodeController;
use Drupal\node\NodeInterface;

/**
 * Returns responses for Decoupled Preview routes.
 */
class RevisionOverviewController extends NodeController {

  /**
   * {@inheritDoc}
   */
  public function revisionOverview(NodeInterface $node) {
    $build = parent::revisionOverview($node);
    $ids = \Drupal::entityQuery('dp_preview_site')->execute();
    $previewSiteStorage = \Drupal::entityTypeManager()->getStorage('dp_preview_site');
    $sites = $previewSiteStorage->loadMultiple($ids);
    $enablePreview = FALSE;
    $nodeType = $node->bundle();
    foreach ($sites as $site) {
      if ($site->checkEnabledContentType($nodeType)) {
        $enablePreview = TRUE;
      }
    }
    if ($enablePreview) {
      $node_storage = $this->entityTypeManager()->getStorage('node');
      $langCode = $node->language()->getId();
      $vIds = [];
      foreach ($this->getRevisionIds($node, $node_storage) as $vid) {
        /** @var \Drupal\node\NodeInterface $revision */
        $revision = $node_storage->loadRevision($vid);
        $revision->hasTranslation($langCode) && $revision->getTranslation($langCode)->isRevisionTranslationAffected() &&
        $vIds[] = $vid;
      }
      foreach ($build['node_revisions_table']['#rows'] as $index => $row) {
        if (isset($row[1]) && in_array('data', array_keys($row[1])) && $row[1]['data']['#type'] === 'operations') {
          $links = $row[1]['data']['#links'];
          $url = Url::fromRoute(
            'decoupled_preview.preview',
            [
              'node' => $node->id(),
              'node_preview' => $node->uuid(),
            ],
            [
              'query' => [
                "resourceVersionId" => $vIds[$index],
              ],
            ],
          );
          if (count($ids) == 1) {
            $url->setOption('attributes', ['target' => '_blank']);
          }
          $links['decoupled_preview'] = [
            'title' => $this->t('Decoupled Preview'),
            'url' => $url,
          ];
          $row[1]['data']['#links'] = $links;
          $build['node_revisions_table']['#rows'][$index] = $row;
        }
      }
    }
    return $build;
  }

}
