<?php

namespace Drupal\badgr;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\badgr\Entity\BadgrEntityInterface;

/**
 * Defines the storage handler class for Badge class entities.
 *
 * This extends the base storage class, adding required special handling for
 * Badge class entities.
 *
 * @ingroup badgr
 */
class BadgeClassStorage extends SqlContentEntityStorage implements BadgeClassStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(BadgrEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {badge_class_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {badge_class_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(BadgrEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {badge_class_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('badge_class_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
