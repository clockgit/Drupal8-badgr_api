<?php

namespace Drupal\badgr\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Assertion evidence entities.
 *
 * @ingroup badgr
 */
interface AssertionEvidenceInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Assertion evidence name.
   *
   * @return string
   *   Name of the Assertion evidence.
   */
  public function getLabel();

  /**
   * Sets the Assertion evidence name.
   *
   * @param string $name
   *   The Assertion evidence name.
   *
   * @return \Drupal\badgr\Entity\AssertionEvidenceInterface
   *   The called Assertion evidence entity.
   */
  public function setLabel($name);


  /**
   * Gets the Assertion evidence creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Assertion evidence.
   */
  public function getCreatedTime();

  /**
   * Sets the Assertion evidence creation timestamp.
   *
   * @param int $timestamp
   *   The Assertion evidence creation timestamp.
   *
   * @return \Drupal\badgr\Entity\AssertionEvidenceInterface
   *   The called Assertion evidence entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Assertion evidence revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Assertion evidence revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\badgr\Entity\AssertionEvidenceInterface
   *   The called Assertion evidence entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Assertion evidence revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Assertion evidence revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\badgr\Entity\AssertionEvidenceInterface
   *   The called Assertion evidence entity.
   */
  public function setRevisionUserId($uid);

}
