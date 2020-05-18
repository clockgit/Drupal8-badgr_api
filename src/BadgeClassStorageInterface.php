<?php

namespace Drupal\badgr;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface BadgeClassStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Badge class revision IDs for a specific Badge class.
   *
   * @param \Drupal\badgr\Entity\BadgrEntityInterface $entity
   *   The Badge class entity.
   *
   * @return int[]
   *   Badge class revision IDs (in ascending order).
   */
  public function revisionIds(BadgrEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Badge class author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Badge class revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\badgr\Entity\BadgrEntityInterface $entity
   *   The Badge class entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(BadgrEntityInterface $entity);

  /**
   * Unsets the language for all Badge class with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
