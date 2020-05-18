<?php

namespace Drupal\badgr\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\badgr\Entity\BadgrEntityInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\views\Views;

/**
 * Class BadgeClassController.
 *
 *  Returns responses for Badge class routes.
 */
class BadgeClassController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Display resources to assist completing the badge
   *
   * @param $badge_class
   *
   * @return array
   */
  public function resources(BadgrEntityInterface $badge_class, Request $request) {
    $arguments = [
      'badge_class' => $badge_class,
    ];
    $viewId = 'resources';
    $displayId = 'resources';
    $view = Views::getView($viewId);
    $view->setDisplay($displayId);
    $view->setArguments($arguments);
    $vr = $view->buildRenderable();
    //$view->execute();
//    ksm($view);
    //ksm($view->render());

    return [
      '#markup' => 'This will display resources for students',
      'children' =>[
        [$vr]
      ]
    ];
  }

  /**
   * Display instructor resources
   * @param $badge_class
   *
   * @return array
   */
  public function instructor(BadgrEntityInterface $badge_class, Request $request) {
    $arguments = [
      'badge_class' => $badge_class,
    ];
    $viewId = 'resources';
    $displayId = 'instructor';
    $view = Views::getView($viewId);
    $view->setDisplay($displayId);
    $view->setArguments($arguments);
    $view->execute();
    $vr = \Drupal::service('renderer')->render($view->render());
    //$vr = $view->buildRenderable();

    return [
      '#markup' => 'This will display resources Instructors can use to guide students working on the badge',
      'children' =>[
        ['#markup' => $vr]
      ],
    ];
  }

  /**
   * Display list of counselors for the badge
   *
   * @param $badge_class
   *
   * @return array
   */
  public function counselor($badge_class) {
    ksm($badge_class);
    return array(
      '#markup' => 'This will display a list of counselors',
    );
  }

  /**
   * Displays a Badge class  revision.
   *
   * @param int $badge_class_revision
   *   The Badge class  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($badge_class_revision) {
    $badge_class = $this->entityManager()->getStorage('badge_class')->loadRevision($badge_class_revision);
    $view_builder = $this->entityManager()->getViewBuilder('badge_class');

    return $view_builder->view($badge_class);
  }

  /**
   * Page title callback for a Badge class  revision.
   *
   * @param int $badge_class_revision
   *   The Badge class  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($badge_class_revision) {
    $badge_class = $this->entityManager()->getStorage('badge_class')->loadRevision($badge_class_revision);
    return $this->t('Revision of %title from %date', ['%title' => $badge_class->label(), '%date' => format_date($badge_class->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Badge class .
   *
   * @param \Drupal\badgr\Entity\BadgrEntityInterface $badge_class
   *   A Badge class  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(BadgrEntityInterface $badge_class) {
    $account = $this->currentUser();
    $langcode = $badge_class->language()->getId();
    $langname = $badge_class->language()->getName();
    $languages = $badge_class->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $badge_class_storage = $this->entityManager()->getStorage('badge_class');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $badge_class->label()]) : $this->t('Revisions for %title', ['%title' => $badge_class->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all badge class revisions") || $account->hasPermission('administer badge class entities')));
    $delete_permission = (($account->hasPermission("delete all badge class revisions") || $account->hasPermission('administer badge class entities')));

    $rows = [];

    $vids = $badge_class_storage->revisionIds($badge_class);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\badgr\BadgrEntityInterface $revision */
      $revision = $badge_class_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $badge_class->getRevisionId()) {
          $link = $this->l($date, new Url('entity.badge_class.revision', ['badge_class' => $badge_class->id(), 'badge_class_revision' => $vid]));
        }
        else {
          $link = $badge_class->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => Url::fromRoute('entity.badge_class.revision_revert', ['badge_class' => $badge_class->id(), 'badge_class_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.badge_class.revision_delete', ['badge_class' => $badge_class->id(), 'badge_class_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['badge_class_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
