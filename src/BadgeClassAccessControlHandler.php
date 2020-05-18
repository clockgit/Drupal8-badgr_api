<?php

namespace Drupal\badgr;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Badge class entity.
 *
 * @see \Drupal\badgr\Entity\BadgeClass.
 */
class BadgeClassAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\badgr\Entity\BadgrEntityInterface $entity */
    switch ($operation) {
      case 'view':
        /*if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished badge class entities');
        }*/
        return AccessResult::allowedIfHasPermission($account, 'view published badge class entities');

      case 'update':
        if($entity->getOwnerId() == $account->id()) {
          //its the owner, let them in
          return AccessResult::allowedIfHasPermission($account, 'edit own badge class entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'edit badge class entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete badge class entities');
      case 'resource':
        return AccessResult::allowedIfHasPermission($account, 'delete badge class entities');
      case 'instructor':
        return AccessResult::allowedIfHasPermission($account, 'delete badge class entities');
      case 'counselor':
        return AccessResult::allowedIfHasPermission($account, 'delete badge class entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add badge class entities');
  }

}
