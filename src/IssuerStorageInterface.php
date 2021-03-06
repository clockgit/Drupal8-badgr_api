<?php

namespace Drupal\badgr;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\badgr\Entity\BadgrEntityInterface;

/**
 * Defines the storage handler class for Issuer entities.
 *
 * This extends the base storage class, adding required special handling for
 * Issuer entities.
 *
 * @ingroup badgr
 */
interface IssuerStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Issuer revision IDs for a specific Issuer.
   *
   * @param \Drupal\badgr\Entity\BadgrEntityInterface $entity
   *   The Issuer entity.
   *
   * @return int[]
   *   Issuer revision IDs (in ascending order).
   */
  public function revisionIds(BadgrEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Issuer author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Issuer revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\badgr\Entity\BadgrEntityInterface $entity
   *   The Issuer entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(BadgrEntityInterface $entity);

  /**
   * Unsets the language for all Issuer with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
