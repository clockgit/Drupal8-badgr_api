<?php

namespace Drupal\badgr\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Assertion entity.
 *
 * @ingroup badgr
 *
 * @ContentEntityType(
 *   id = "assertion",
 *   label = @Translation("Assertion"),
 *   handlers = {
 *     "storage" = "Drupal\badgr\AssertionStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\badgr\AssertionListBuilder",
 *     "views_data" = "Drupal\badgr\Entity\ViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\badgr\Form\AssertionForm",
 *       "add" = "Drupal\badgr\Form\AssertionForm",
 *       "edit" = "Drupal\badgr\Form\AssertionForm",
 *       "delete" = "Drupal\badgr\Form\AssertionDeleteForm",
 *     },
 *     "access" = "Drupal\badgr\AssertionAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\badgr\AssertionHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "assertion",
 *   revision_table = "assertion_revision",
 *   revision_data_table = "assertion_field_revision",
 *   admin_permission = "administer assertion entities",
 *   translatableEntityKeys = {
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status"
 *   },
 *   links = {
 *     "canonical" = "/admin/badgr/assertion/{assertion}",
 *     "add-form" = "/admin/badgr/assertion/add",
 *     "edit-form" = "/admin/badgr/assertion/{assertion}/edit",
 *     "delete-form" = "/admin/badgr/assertion/{assertion}/delete",
 *     "version-history" = "/admin/badgr/assertion/{assertion}/revisions",
 *     "revision" = "/admin/badgr/assertion/{assertion}/revisions/{assertion_revision}/view",
 *     "revision_revert" = "/admin/badgr/assertion/{assertion}/revisions/{assertion_revision}/revert",
 *     "revision_delete" = "/admin/badgr/assertion/{assertion}/revisions/{assertion_revision}/delete",
 *     "collection" = "/admin/badgr/assertion/collection",
 *   },
 *   field_ui_base_route = "assertion.settings"
 * )
 */
class Assertion extends BadgrEntityBase {

  /**
   * {@inheritdoc}
   *
   * @return $fields \Drupal\Core\Field\FieldDefinitionInterface[]
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
  {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['badgeclass'] = BaseFieldDefinition::create('entity_reference')
      ->SetLabel( t('Badge Class') )
      ->SetDescription( t('The awarded badge') )
      ->SetDefaultValue('')
      ->SetRequired( TRUE )
      ->SetRevisionable( TRUE )
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        //'region' => 'published',//hidden or region id
        'type' => 'inline_entity_form_complex',//widget or formatter
        'settings' => [
          'allow_new' => false,
          'allow_existing' => true,
          'allow_duplicate' => false,
          'match_operator' => 'CONTAINS',
        ],//settings for the type plugin
        'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
        'weight' => 15,//float
      ])
      ->setDisplayOptions('view', [
        'label' => 'inline',//inline above hidden
        //'region' => 'published',//hidden or region id
        'type' => 'entity_reference_label',//widget or formatter
        'settings' => [
          'link' => true,
        ],//settings for the type plugin
        'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
        'weight' => 15,//float
      ])
      ->setSettings([
        'target_type' => 'badge_class',
        'handler' => 'default',
        'handler_settings' => [
          'target_bundles' => NULL,
          'sort' => [
            'field' => 'label',
            'direction' => 'ASC',
          ],
          'auto_create' => TRUE,
        ],
      ]);

    $fields['assertion_evidence'] = BaseFieldDefinition::create('entity_reference')
      ->SetLabel( t('Evidence') )
      ->SetDescription( t('Evidence of badge completion.') )
      ->SetDefaultValue('')
      ->SetRequired( TRUE )
      ->SetRevisionable( TRUE )
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        //'region' => 'published',//hidden or region id
        'type' => 'inline_entity_form_complex',//widget or formatter
        'settings' => [
          'allow_new' => true,
          'allow_existing' => false,
          'allow_duplicate' => false,
          'match_operator' => 'CONTAINS',
        ],//settings for the type plugin
        'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
        'weight' => 15,//float
      ])
      ->setDisplayOptions('view', [
        'label' => 'inline',//inline above hidden
        //'region' => 'published',//hidden or region id
        'type' => 'entity_reference_label',//widget or formatter
        'settings' => [
          'link' => true,
        ],//settings for the type plugin
        'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
        'weight' => 15,//float
      ])
      ->setSettings([
        'target_type' => 'assertion_evidence',
        'handler' => 'default',
        'handler_settings' => [
          'target_bundles' => NULL,
          'sort' => [
            'field' => 'label',
            'direction' => 'ASC',
          ],
          'auto_create' => TRUE,
        ],
      ]);

    /* get these values from badgeclass
     * $fields['badgeclassOpenBadgeId']
     * $fields['issuer']
     * $fields['issuerOpenBadgeId']
     * */

    //save image from badgr.io
    $fields['image'] = BaseFieldDefinition::create('image')
      ->SetLabel( t('Baked Image') )
      ->SetDescription( t('Baked image') )
      ->SetDefaultValue('')
      ->SetRequired( TRUE )
      ->SetRevisionable( TRUE )
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [])
      ->setDisplayOptions('view', []);

    $fields['revoked'] = BaseFieldDefinition::create('boolean')
      ->SetLabel( t('Revoked') )
      ->SetDescription( t('Has the badge been revoked') )
      ->SetDefaultValue('')
      ->SetRequired( TRUE )
      ->SetRevisionable( TRUE )
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [])
      ->setDisplayOptions('view', []);

    $fields['revocationReason'] = BaseFieldDefinition::create('text')
      ->SetLabel( t('Revoked Reason') )
      ->SetDescription( t('Why was this badge revoked') )
      ->SetDefaultValue('')
      ->SetRequired( TRUE )
      ->SetRevisionable( TRUE )
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [])
      ->setDisplayOptions('view', []);

    $fields['expires'] = BaseFieldDefinition::create('timestamp')
      ->SetLabel( t('Expires') )
      ->SetDescription( t('When does this award expire') )
      ->SetDefaultValue('')
      ->SetRequired( TRUE )
      ->SetRevisionable( TRUE )
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [])
      ->setDisplayOptions('view', []);

    $fields['narrative'] = BaseFieldDefinition::create('text_with_summary')
      ->SetLabel( t('Narrative') )
      ->SetDescription( t('<a target="_blank" href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet">Markdown formatted</a> narrative of the achievement') )
      ->SetDefaultValue('')
      ->SetRequired( TRUE )
      ->SetRevisionable( TRUE )
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setSetting('allowed_formats', [
        'markdown' =>'markdown',
        'hide_help' => '1',
        'hide_guidelines' => '1'
      ])
      ->setDisplayOptions('form', [
        'region' => 'content',//hidden or region id
        'type' => 'text_textarea',//widget or formatter
        'settings' => [
          'rows' => 3,
        ],//settings for the type plugin
        //'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
        'weight' => 15,//float
      ])
      ->setDisplayOptions('view', [
        'label' => 'inline',//inline above hidden
        'region' => 'content',//hidden or region id
        //'type' => '',//widget or formatter
        'settings' => [],//settings for the type plugin
        //'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
        'weight' => 15,//float
      ]);

    $fields['recipient'] = BaseFieldDefinition::create('entity_reference')
      ->SetLabel( t('Recipient') )
      ->SetDescription( t('Recipient that was issued the Assertion') )
      ->setRevisionable(TRUE)
      ->setSettings([
        'target_type' => 'user',
        'handler' => 'default:user',
        'handler_settings' =>[
          'include_anonymous'=> true,
          'filter' => [
            'type'=> 'role',
            'role'=>[
              'k_12_student'=> 'k_12_student',
              'essdack_participant'=> 'essdack_participant',
              'administrator'=> '0',
              'teacher'=> '0',
              'school_admin'=> '0'
            ]
          ],
          'target_bundles'=> null,
          'sort'=>[
            'field'=> '_none',
          ],
          'auto_create' => false,
        ]
      ])
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 14,
        'region' => 'hidden',
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 14,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
