<?php
namespace Drupal\badgr\Plugin\Action;

//use Drupal\facets\Exception\Exception;
use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;
//use Drupal\views_bulk_operations\Action\ViewsBulkOperationsPreconfigurationInterface;
//use Drupal\Core\Plugin\PluginFormInterface;
//use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

  /**
 * Action description.
 *
 * @Action(
 *   id = "badgr_vbo_api",
 *   label = "Add/Update badgr.io",
 *   type = "",
 *   confirm = TRUE,
 *
 * )
 */
class vbo_badgr_api extends ViewsBulkOperationsActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    /*
     * All config resides in $this->configuration.
     * Passed view rows will be available in $this->context.
     * Data about the view used to select results and optionally
     * the batch context are available in $this->context or externally
     * through the public getContext() method.
     * The entire ViewExecutable object  with selected result
     * rows is available in $this->view or externally through
     * the public getView() method.
     */

    //send to badgr.io api
    try{
      $client = \Drupal::service('badgr.api');
      $response = $client->apiCall($entity, 'save');

      if (empty($entity->get('entityId')->value)) {
        $entity->setEntityId($response['result'][0]['entityId']);
        $entity->set('openBadgeId', $response['result'][0]['openBadgeId']);
        $entity->save();
      }
      else {
        if ($entity->get('entityId')->value != $response['result'][0]['entityId']) {
          // They don't match, figure out what is wrong!!!
        }
      }
    }catch (\Exception $e){
      //TODO if config not set redirect to settings page
    }
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    if ($object->getEntityType()->id()==='badge_class') {
      return $object->access('update', $account);
    }

    // Other entity types may have different
    // access methods and properties.
    return FALSE;
  }
}