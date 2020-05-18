<?php
namespace Drupal\badgr\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\user\PrivateTempStoreFactory;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "badgr_add_badge_resource",
 *   admin_label = @Translation("Add Badge Resource"),
 *   category = @Translation("Badge"),
 * )
 */
class AddResourceBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $parameters = \Drupal::routeMatch()->getParameters();
    if($parameters->has('display_id') &&
      ($parameters->get('display_id') === "resources" ||
        $parameters->get('display_id') ==="instructor")) {
      $tempStore = \Drupal::service('user.private_tempstore')->get('badgr');
      if(is_numeric($parameters->get('badge_class')))
        $tempStore->set('badge_class', $parameters->get('badge_class'));
      if($parameters->get('display_id') === "instructor") {
        $tempStore->set('display_id', 1);
      } else /*if($parameters->get('display_id') === "resources")*/ {
        $tempStore->set('display_id', 0);
      }
    }
    //ksm($tempStore->get('badge_class'), $parameters->get('display_id'));
    $build['markup'] = [
      '#markup' => '<a class="btn btn-primary btn-sm" href="/node/add/resource">Submit Resource</a>',
    ];
    $build['#cache'] =['max-age' => 0];
    return $build;
  }
}
