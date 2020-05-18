<?php

namespace Drupal\badgr;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface AssertionEvidenceStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Assertion evidence revision IDs for a specific Assertion evidence.
   *
   * @param \Drupal\badgr\Entity\AssertionEvidenceInterface $entity
   *   The Assertion evidence entity.
   *
   * @return int[]
   *   Assertion evidence revision IDs (in ascending order).
   */
  public function revisionIds(AssertionEvidenceInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Assertion evidence author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Assertion evidence revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\badgr\Entity\AssertionEvidenceInterface $entity
   *   The Assertion evidence entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(AssertionEvidenceInterface $entity);

  /**
   * Unsets the language for all Assertion evidence with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
