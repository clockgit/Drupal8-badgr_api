<?php

/**
 * @file
 * Contains badge_class.page.inc.
 *
 * Page callback for Badge class entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Badge class templates.
 *
 * Default template: badge_class.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_badge_class(array &$variables) {
  // Fetch BadgeClass Entity Object.
  $badge_class = $variables['elements']['#badge_class'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
