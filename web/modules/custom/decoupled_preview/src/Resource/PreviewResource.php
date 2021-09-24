<?php

namespace Drupal\decoupled_preview\Resource;

use Drupal\jsonapi_resources\Resource\EntityResourceBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\jsonapi\CacheableResourceResponse;
use Drupal\Core\Cache\Cache;

/**
 * Processes a request for a collection containing a resource being edited.
 *
 * @internal
 */
class PreviewResource extends EntityResourceBase {

  /**
   * Process the resource request.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   * @param \Drupal\jsonapi\ResourceType\ResourceType[] $resource_types
   *   The route resource types.
   *
   * @return \Drupal\jsonapi\ResourceResponse
   *   The response.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function process(Request $request, array $resource_types) {
    // TODO - prevent this route from being cached
    $tempstore_key = $request->get('key');

    $form_state = \Drupal::service('tempstore.shared')->get('decoupled_preview')->get($tempstore_key);
    // Try / catch or better error handling.
    $entity = $form_state->getFormObject()->getEntity();
    $nid = $entity->id();

    /**
     * This is a pretty big hammer, and could make the entity less cachable for
     * other JSON:API endpoints.
     * TODO - refine this, ideally making t possible to invalidate using a cache
     * tag that will only be relevant to preview.
     */
    Cache::invalidateTags(["node:{$nid}"]);

    $data = $this->createIndividualDataFromEntity($entity);
    $response = $this->createJsonapiResponse($data, $request);

    // Add cache tag so that we can invalidate when preview data changes.
    if ($response instanceof CacheableResourceResponse) {
      $cache_tags[] = "decoupled_preview:{$tempstore_key}";
      $cacheability = (new CacheableMetadata())->addCacheTags($cache_tags);
      $response->addCacheableDependency($cacheability);
    }

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function getRouteResourceTypes(Route $route, string $route_name): array {
    return $this->getResourceTypesByEntityTypeId('node');

  }

}
