<?php

namespace Drupal\badgr\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityStorageInterface;

/**
 * Defines the Issuer entity.
 *
 * @ingroup badgr
 *
 * @ContentEntityType(
 *   id = "issuer",
 *   label = @Translation("Issuer"),
 *   handlers = {
 *     "storage" = "Drupal\badgr\IssuerStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\badgr\IssuerListBuilder",
 *     "views_data" = "Drupal\badgr\Entity\ViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\badgr\Form\IssuerForm",
 *       "add" = "Drupal\badgr\Form\IssuerForm",
 *       "edit" = "Drupal\badgr\Form\IssuerForm",
 *       "delete" = "Drupal\badgr\Form\IssuerDeleteForm",
 *     },
 *     "access" = "Drupal\badgr\IssuerAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\badgr\IssuerHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "issuer",
 *   revision_table = "issuer_revision",
 *   revision_data_table = "issuer_field_revision",
 *   admin_permission = "administer issuer entities",
 *   translatableEntityKeys = {
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/admin/badgr/issuer/{issuer}",
 *     "add-form" = "/admin/badgr/issuer/add",
 *     "edit-form" = "/admin/badgr/issuer/{issuer}/edit",
 *     "delete-form" = "/admin/badgr/issuer/{issuer}/delete",
 *     "version-history" = "/admin/badgr/issuer/{issuer}/revisions",
 *     "revision" = "/admin/badgr/issuer/{issuer}/revisions/{issuer_revision}/view",
 *     "revision_revert" = "/admin/badgr/issuer/{issuer}/revisions/{issuer_revision}/revert",
 *     "revision_delete" = "/admin/badgr/issuer/{issuer}/revisions/{issuer_revision}/delete",
 *     "collection" = "/admin/badgr/issuer/collection",
 *   },
 *   field_ui_base_route = "issuer.settings"
 * )
 */
class Issuer extends BadgrEntityBase implements BadgrEntityInterface {

  /**
   * {@inheritdoc}
   *
   * @return $fields \Drupal\Core\Field\FieldDefinitionInterface[]
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['url'] = BaseFieldDefinition::create('uri')
      ->setLabel(t('Website URL'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'uri_link',
        'weight' => 14,
      ])
      ->setDisplayOptions('form', [
        'type' => 'uri',
        'weight' => 14,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['description'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Description'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'type' => 'basic_string',
        'weight' => 13,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textarea',
        'weight' => 13,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('image'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE)
      ->setSettings([
        'alt_field_required' => FALSE,
        'file_extensions' => 'png jpg jpeg',
        'weight' => 12,
      ])
      ->setDisplayOptions('view', [
        'type' => 'image',
        'weight' => 2,
        'label' => 'hidden',
        'settings' => [
          'image_style' => 'badgr_image',
        ],
      ])
      ->setDisplayOptions('form', [
        'type' => 'image_image',
        'weight' => -5,
        'settings' => [
          'preview_image_style' => 'badgr_image',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
