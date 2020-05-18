<?php

namespace Drupal\badgr;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface AssertionStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Assertion revision IDs for a specific Assertion.
   *
   * @param \Drupal\badgr\Entity\BadgrEntityInterface $entity
   *   The Assertion entity.
   *
   * @return int[]
   *   Assertion revision IDs (in ascending order).
   */
  public function revisionIds(BadgrEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Assertion author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Assertion revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\badgr\Entity\BadgrEntityInterface $entity
   *   The Assertion entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(BadgrEntityInterface $entity);

  /**
   * Unsets the language for all Assertion with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
