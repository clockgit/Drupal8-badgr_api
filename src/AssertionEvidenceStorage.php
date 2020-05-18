<?php

namespace Drupal\badgr;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\badgr\Entity\AssertionEvidenceInterface;

/**
 * Defines the storage handler class for Assertion evidence entities.
 *
 * This extends the base storage class, adding required special handling for
 * Assertion evidence entities.
 *
 * @ingroup badgr
 */
class AssertionEvidenceStorage extends SqlContentEntityStorage implements AssertionEvidenceStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(AssertionEvidenceInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {assertion_evidence_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {assertion_evidence_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(AssertionEvidenceInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {assertion_evidence_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('assertion_evidence_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
