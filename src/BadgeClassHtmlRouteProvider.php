<?php

namespace Drupal\badgr;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\AdminHtmlRouteProvider;
use Symfony\Component\Routing\Route;

/**
 * Provides routes for Badge class entities.
 *
 * @see \Drupal\Core\Entity\Routing\AdminHtmlRouteProvider
 * @see \Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider
 */
class BadgeClassHtmlRouteProvider extends AdminHtmlRouteProvider {
  /**
   * {@inheritdoc}
   */
  protected function getAddFormRoute(EntityTypeInterface $entity_type) {
    if ($route = parent::getAddFormRoute($entity_type)) {
      $route
        ->setOption('_admin_route', FALSE);
      return $route;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditFormRoute(EntityTypeInterface $entity_type) {
    if ($route = parent::getEditFormRoute($entity_type)) {
      $route
        ->setOption('_admin_route', FALSE);
      return $route;
    }
  }

//  protected function getResourceRoute(EntityTypeInterface $entity_type) {
//    if ($entity_type->hasLinkTemplate('resource')) {
//      $route = new Route($entity_type->getLinkTemplate('resource'));
//      $route
//        ->setDefaults([
//          '_controller' => '\Drupal\badgr\Controller\BadgeClassController::resources',
//        ])
//        ->setRequirement('_permission', 'access badge class revisions');
//        //->setOption('_admin_route', FALSE);
//
//      return $route;
//    }
//  }
//
//  protected function getInstructorRoute(EntityTypeInterface $entity_type) {
//    if ($entity_type->hasLinkTemplate('instructor')) {
//      $route = new Route($entity_type->getLinkTemplate('instructor'));
//      $route
//        ->setDefaults([
//          '_controller' => '\Drupal\badgr\Controller\BadgeClassController::instructor',
//        ])
//        ->setRequirement('_permission', 'access badge class revisions');
//        //->setOption('_admin_route', FALSE);
//
//      return $route;
//    }
//  }

  /**
   * {@inheritdoc}
   */
  public function getRoutes(EntityTypeInterface $entity_type) {
    $collection = parent::getRoutes($entity_type);

    $entity_type_id = $entity_type->id();

//    if ($resource_route = $this->getResourceRoute($entity_type)) {
//      $collection->add("entity.{$entity_type_id}.resource", $resource_route);
//    }
//
//    if ($instructor_route = $this->getInstructorRoute($entity_type)) {
//      $collection->add("entity.{$entity_type_id}.instructor", $instructor_route);
//    }

    if ($history_route = $this->getHistoryRoute($entity_type)) {
      $collection->add("entity.{$entity_type_id}.version_history", $history_route);
    }

    if ($revision_route = $this->getRevisionRoute($entity_type)) {
      $collection->add("entity.{$entity_type_id}.revision", $revision_route);
    }

    if ($revert_route = $this->getRevisionRevertRoute($entity_type)) {
      $collection->add("entity.{$entity_type_id}.revision_revert", $revert_route);
    }

    if ($delete_route = $this->getRevisionDeleteRoute($entity_type)) {
      $collection->add("entity.{$entity_type_id}.revision_delete", $delete_route);
    }

    if ($settings_form_route = $this->getSettingsFormRoute($entity_type)) {
      $collection->add("$entity_type_id.settings", $settings_form_route);
    }

    return $collection;
  }

  /**
   * Gets the version history route.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   *
   * @return \Symfony\Component\Routing\Route|null
   *   The generated route, if available.
   */
  protected function getHistoryRoute(EntityTypeInterface $entity_type) {
    if ($entity_type->hasLinkTemplate('version-history')) {
      $route = new Route($entity_type->getLinkTemplate('version-history'));
      $route
        ->setDefaults([
          '_title' => "{$entity_type->getLabel()} revisions",
          '_controller' => '\Drupal\badgr\Controller\BadgeClassController::revisionOverview',
        ])
        ->setRequirement('_permission', 'access badge class revisions')
        ->setOption('_admin_route', TRUE);

      return $route;
    }
  }

  /**
   * Gets the revision route.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   *
   * @return \Symfony\Component\Routing\Route|null
   *   The generated route, if available.
   */
  protected function getRevisionRoute(EntityTypeInterface $entity_type) {
    if ($entity_type->hasLinkTemplate('revision')) {
      $route = new Route($entity_type->getLinkTemplate('revision'));
      $route
        ->setDefaults([
          '_controller' => '\Drupal\badgr\Controller\BadgeClassController::revisionShow',
          '_title_callback' => '\Drupal\badgr\Controller\BadgeClassController::revisionPageTitle',
        ])
        ->setRequirement('_permission', 'access badge class revisions')
        ->setOption('_admin_route', TRUE);

      return $route;
    }
  }

  /**
   * Gets the revision revert route.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   *
   * @return \Symfony\Component\Routing\Route|null
   *   The generated route, if available.
   */
  protected function getRevisionRevertRoute(EntityTypeInterface $entity_type) {
    if ($entity_type->hasLinkTemplate('revision_revert')) {
      $route = new Route($entity_type->getLinkTemplate('revision_revert'));
      $route
        ->setDefaults([
          '_form' => '\Drupal\badgr\Form\BadgeClassRevisionRevertForm',
          '_title' => 'Revert to earlier revision',
        ])
        ->setRequirement('_permission', 'revert all badge class revisions')
        ->setOption('_admin_route', TRUE);

      return $route;
    }
  }

  /**
   * Gets the revision delete route.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   *
   * @return \Symfony\Component\Routing\Route|null
   *   The generated route, if available.
   */
  protected function getRevisionDeleteRoute(EntityTypeInterface $entity_type) {
    if ($entity_type->hasLinkTemplate('revision_delete')) {
      $route = new Route($entity_type->getLinkTemplate('revision_delete'));
      $route
        ->setDefaults([
          '_form' => '\Drupal\badgr\Form\BadgeClassRevisionDeleteForm',
          '_title' => 'Delete earlier revision',
        ])
        ->setRequirement('_permission', 'delete all badge class revisions')
        ->setOption('_admin_route', TRUE);

      return $route;
    }
  }

  /**
   * Gets the settings form route.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   *
   * @return \Symfony\Component\Routing\Route|null
   *   The generated route, if available.
   */
  protected function getSettingsFormRoute(EntityTypeInterface $entity_type) {
    if (!$entity_type->getBundleEntityType()) {
      $route = new Route("/admin/badgr/{$entity_type->id()}/settings");
      $route
        ->setDefaults([
          '_form' => 'Drupal\badgr\Form\BadgeClassSettingsForm',
          '_title' => "{$entity_type->getLabel()} settings",
        ])
        ->setRequirement('_permission', $entity_type->getAdminPermission())
        ->setOption('_admin_route', TRUE);

      return $route;
    }
  }

}
