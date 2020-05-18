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
 *   id = "publish_entity",
 *   label = "Publish entity",
 *   type = "",
 *   confirm = TRUE,
 *
 * )
 */
class vbo_publish extends ViewsBulkOperationsActionBase {

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
        $entity->setPublished(TRUE);
        $entity->save();

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