<?php

namespace Drupal\badgr\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Assertion entities.
 */
class BadgeClassViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    // Unset unusable view data handlers.
    /*
        $reference_field_name = 'tags_math';
        $entity_type = 'badge_class';//$this->entityType

        $handler_values = [
          'field' => "{$reference_field_name}_target_id",
          'table' => "{$entity_type}__{$reference_field_name}",
          'field_name' => $reference_field_name,
          'entity_type' => $entity_type,
          'additional fields' => [
            "field_{$reference_field_name}_target_id",
          ],
        ];
        $data["{$entity_type}__{$reference_field_name}"][$reference_field_name]['relationship']['relationship field'] = "{$reference_field_name}_target_id";
        $data["{$entity_type}__{$reference_field_name}"]["{$reference_field_name}_target_id"] = [
          'group' => $this->t('ITrack Notification'),
          'argument' => [
              'title' => $this->t('Receivers - has User ID'),
              'help' => $this->t('Holds the receiver User ID.'),
              'id' => 'numeric',
            ] + $handler_values,
        ];
    unset($data["{$entity_type}__{$reference_field_name}"][$reference_field_name]['argument']);
    */
    /*
    if ($field_storage
        ->getType() == 'entity_reference' && $field_storage
        ->getSetting('target_type') == 'taxonomy_term') {
      foreach ($data as $table_name => $table_data) {
        foreach ($table_data as $field_name => $field_data) {
          if (isset($field_data['filter']) && $field_name != 'delta') {
            $data[$table_name][$field_name]['filter']['id'] = 'taxonomy_index_tid';
          }
        }
      }
    }
    */
    $data['badge_class__tags_math']['tags_math_target_id']['filter']['id'] = 'taxonomy_index_tid';
    $data['badge_class__alignments']['alignments_target_id']['filter']['id'] = 'taxonomy_index_tid';
    $data['badge_class__tags_ela']['tags_ela_target_id']['filter']['id'] = 'taxonomy_index_tid';
    $data['badge_class__tags_science']['tags_science_target_id']['filter']['id'] = 'taxonomy_index_tid';
    $data['badge_class__tags_social_studies']['tags_social_studies_target_id']['filter']['id'] = 'taxonomy_index_tid';
    $data['badge_class__physical_education']['physical_education_target_id']['filter']['id'] = 'taxonomy_index_tid';
    $data['badge_class__fine_arts']['fine_arts_target_id']['filter']['id'] = 'taxonomy_index_tid';
    return $data;
  }

}
