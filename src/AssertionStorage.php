<?php

namespace Drupal\badgr;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\badgr\Entity\BadgrEntityInterface;

/**
 * Defines the storage handler class for Assertion entities.
 *
 * This extends the base storage class, adding required special handling for
 * Assertion entities.
 *
 * @ingroup badgr
 */
class AssertionStorage extends SqlContentEntityStorage implements AssertionStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(BadgrEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {assertion_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {assertion_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(BadgrEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {assertion_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('assertion_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
