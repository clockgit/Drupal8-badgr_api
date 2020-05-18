<?php
namespace Drupal\badgr\Plugin\Action;

use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;
//use Drupal\views_bulk_operations\Action\ViewsBulkOperationsPreconfigurationInterface;
//use Drupal\Core\Plugin\PluginFormInterface;
//use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

  /**
 * Action description.
 *
 * @Action(
 *   id = "badgr_vbo_api_delete",
 *   label = "Remove badgr.io",
 *   type = "",
 *   confirm = TRUE,
 *
 * )
 */
class vbo_badgr_api_delete extends ViewsBulkOperationsActionBase {

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
    if(empty($entity->get('entityId')->value)) {
      //ksm('not set can\'t delete');
    }else {
      try {
        $client = \Drupal::service('badgr.api');
        $client->apiCall($entity, 'delete');
        $entity->setEntityId(NULL);
        $entity->set('openBadgeId',NULL);
        $entity->save();
      } catch (\Exception $exception) {}
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