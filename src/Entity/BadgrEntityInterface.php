<?php

namespace Drupal\badgr\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining BadgrEntity entities.
 *
 * @ingroup badgr
 */
interface BadgrEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Badgr Entity type for the Badgr API
   *
   * @return string
   */
  public function getBadgrEntityType();

  /**
   * Gets the BadgrEntity label.
   *
   * @return string
   *   Label of the BadgrEntity.
   */
  public function getLabel();

  /**
   * Sets the BadgrEntity label.
   *
   * @param string $label
   *   The BadgrEntity label.
   *
   * @return \Drupal\badgr\Entity\BadgrEntityInterface
   *   The called BadgrEntity entity.
   */
  public function setLabel($label);

  /**
   * Gets the BadgrEntity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the BadgrEntity.
   */
  public function getCreatedTime();

  /**
   * Sets the BadgrEntity creation timestamp.
   *
   * @param int $timestamp
   *   The BadgrEntity creation timestamp.
   *
   * @return \Drupal\badgr\Entity\BadgrEntityInterface
   *   The called BadgrEntity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the BadgrEntity published status indicator.
   *
   * Unpublished BadgrEntity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the BadgrEntity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a BadgrEntity.
   *
   * @param bool $published
   *   TRUE to set this BadgrEntity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\badgr\Entity\BadgrEntityInterface
   *   The called BadgrEntity entity.
   */
  public function setPublished($published);

  /**
   * Gets the BadgrEntity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the BadgrEntity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\badgr\Entity\BadgrEntityInterface
   *   The called BadgrEntity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the BadgrEntity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the BadgrEntity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\badgr\Entity\BadgrEntityInterface
   *   The called BadgrEntity entity.
   */
  public function setRevisionUserId($uid);

}
