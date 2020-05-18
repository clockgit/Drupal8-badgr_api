<?php

namespace Drupal\badgr;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Assertion evidence entity.
 *
 * @see \Drupal\badgr\Entity\AssertionEvidence.
 */
class AssertionEvidenceAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\badgr\Entity\AssertionEvidenceInterface $entity */
    switch ($operation) {
      case 'view':
        /*if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished assertion evidence entities');
        }*/
        return AccessResult::allowedIfHasPermission($account, 'view published assertion evidence entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit assertion evidence entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete assertion evidence entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add assertion evidence entities');
  }

}
