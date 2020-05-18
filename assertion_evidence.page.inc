<?php

/**
 * @file
 * Contains assertion_evidence.page.inc.
 *
 * Page callback for Assertion evidence entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Assertion evidence templates.
 *
 * Default template: assertion_evidence.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_assertion_evidence(array &$variables) {
  // Fetch AssertionEvidence Entity Object.
  $assertion_evidence = $variables['elements']['#assertion_evidence'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
