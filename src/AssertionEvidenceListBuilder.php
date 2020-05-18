<?php

namespace Drupal\badgr;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Assertion evidence entities.
 *
 * @ingroup badgr
 */
class AssertionEvidenceListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Assertion evidence ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\badgr\Entity\AssertionEvidence */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.assertion_evidence.edit_form',
      ['assertion_evidence' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
