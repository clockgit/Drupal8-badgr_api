<?php

namespace Drupal\badgr\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\badgr\Entity\AssertionEvidenceInterface;

/**
 * Class AssertionEvidenceController.
 *
 *  Returns responses for Assertion evidence routes.
 */
class AssertionEvidenceController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Assertion evidence  revision.
   *
   * @param int $assertion_evidence_revision
   *   The Assertion evidence  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($assertion_evidence_revision) {
    $assertion_evidence = $this->entityManager()->getStorage('assertion_evidence')->loadRevision($assertion_evidence_revision);
    $view_builder = $this->entityManager()->getViewBuilder('assertion_evidence');

    return $view_builder->view($assertion_evidence);
  }

  /**
   * Page title callback for a Assertion evidence  revision.
   *
   * @param int $assertion_evidence_revision
   *   The Assertion evidence  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($assertion_evidence_revision) {
    $assertion_evidence = $this->entityManager()->getStorage('assertion_evidence')->loadRevision($assertion_evidence_revision);
    return $this->t('Revision of %title from %date', ['%title' => $assertion_evidence->label(), '%date' => format_date($assertion_evidence->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Assertion evidence .
   *
   * @param \Drupal\badgr\Entity\AssertionEvidenceInterface $assertion_evidence
   *   A Assertion evidence  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(AssertionEvidenceInterface $assertion_evidence) {
    $account = $this->currentUser();
    $langcode = $assertion_evidence->language()->getId();
    $langname = $assertion_evidence->language()->getName();
    $languages = $assertion_evidence->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $assertion_evidence_storage = $this->entityManager()->getStorage('assertion_evidence');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $assertion_evidence->label()]) : $this->t('Revisions for %title', ['%title' => $assertion_evidence->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all assertion evidence revisions") || $account->hasPermission('administer assertion evidence entities')));
    $delete_permission = (($account->hasPermission("delete all assertion evidence revisions") || $account->hasPermission('administer assertion evidence entities')));

    $rows = [];

    $vids = $assertion_evidence_storage->revisionIds($assertion_evidence);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\badgr\AssertionEvidenceInterface $revision */
      $revision = $assertion_evidence_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $assertion_evidence->getRevisionId()) {
          $link = $this->l($date, new Url('entity.assertion_evidence.revision', ['assertion_evidence' => $assertion_evidence->id(), 'assertion_evidence_revision' => $vid]));
        }
        else {
          $link = $assertion_evidence->link($date);
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
              'url' => $has_translations ?
              Url::fromRoute('entity.assertion_evidence.translation_revert', ['assertion_evidence' => $assertion_evidence->id(), 'assertion_evidence_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.assertion_evidence.revision_revert', ['assertion_evidence' => $assertion_evidence->id(), 'assertion_evidence_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.assertion_evidence.revision_delete', ['assertion_evidence' => $assertion_evidence->id(), 'assertion_evidence_revision' => $vid]),
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

    $build['assertion_evidence_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
