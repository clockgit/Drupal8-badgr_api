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
 * Defines the Badge class entity.
 *
 * @ingroup badgr
 *
 * @ContentEntityType(
 *   id = "badge_class",
 *   label = @Translation("Badge Class"),
 *   handlers = {
 *     "storage" = "Drupal\badgr\BadgeClassStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\badgr\BadgeClassListBuilder",
 *     "views_data" = "Drupal\badgr\Entity\BadgeClassViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\badgr\Form\BadgeClassForm",
 *       "add" = "Drupal\badgr\Form\BadgeClassForm",
 *       "edit" = "Drupal\badgr\Form\BadgeClassForm",
 *       "delete" = "Drupal\badgr\Form\BadgeClassDeleteForm",
 *     },
 *     "access" = "Drupal\badgr\BadgeClassAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\badgr\BadgeClassHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "badge_class",
 *   revision_table = "badge_class_revision",
 *   revision_data_table = "badge_class_field_revision",
 *   admin_permission = "administer badge class entities",
 *   translatableEntityKeys = {
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "image" = "image",
 *     "status" = "status"
 *   },
 *   links = {
 *     "resource" = "/badgr/badge_class/{badge_class}/resource",
 *     "instructor" =  "/badgr/badge_class/{badge_class}/instructor",
 *     "canonical" = "/badgr/badge_class/{badge_class}",
 *     "add-form" = "/badgr/badge_class/add",
 *     "edit-form" = "/badgr/badge_class/{badge_class}/edit",
 *     "delete-form" = "/badgr/badge_class/{badge_class}/delete",
 *     "version-history" = "/badgr/badge_class/{badge_class}/revisions",
 *     "revision" = "/badgr/badgclass/{badge_class}/revisions/{badge_class_revision}/view",
 *     "revision_revert" = "/badgr/badge_class/{badge_class}/revisions/{badge_class_revision}/revert",
 *     "revision_delete" = "/badgr/badge_class/{badge_class}/revisions/{badge_class_revision}/delete",
 *     "collection" = "/badgr/badge_class/collection",
 *   },
 *   field_ui_base_route = "badge_class.settings"
 * )
 */
class BadgeClass extends BadgrEntityBase {
  /**
   * Builds a list of tags to send to badgr.
   *
   * @return string[]
   */
  public function getTags($field = 'all') {
    //TODO load all tag ref fields and build list
    return [''];
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
  {
    $fields = parent::baseFieldDefinitions($entity_type);


    /*
 * string($data:image/png;base64)
 */
    $fields['image'] = BaseFieldDefinition::create('image')
    ->SetLabel( t('Image') )
    ->SetDefaultValue('')
    ->SetRequired( FALSE )
    ->SetRevisionable( TRUE )
    ->setDisplayConfigurable('view', TRUE)
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayOptions('form', [
      'region' => 'content',//hidden or region id
      'type' => '',//widget or formatter
      'settings' => [],//settings for the type plugin
      'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
      'weight' => 12,//float
    ])
    ->setDisplayOptions('view', [
      'label' => 'inline',//inline above hidden
      'region' => 'content',//hidden or region id
      'type' => '',//widget or formatter
      'settings' => [],//settings for the type plugin
      'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
      'weight' => 12,//float
    ]);

    /*
    * string($string)
    */
    $fields['description'] = BaseFieldDefinition::create('string_long')
    ->SetLabel( t('Description') )
    ->SetDefaultValue('')
    ->SetRequired( FALSE )
    ->SetRevisionable( TRUE )
    ->setDisplayConfigurable('view', TRUE)
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayOptions('form', [
      'region' => 'content',//hidden or region id
      'type' => '',//widget or formatter
      'settings' => [],//settings for the type plugin
      'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
      'weight' => 13,//float
    ])
    ->setDisplayOptions('view', [
      'label' => 'inline',//inline above hidden
      'region' => 'content',//hidden or region id
      'type' => '',//widget or formatter
      'settings' => [],//settings for the type plugin
      'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
      'weight' => 13,//float
    ]);

    /*
   * string($URL)
   */
    /*$fields['criteriaUrl'] = BaseFieldDefinition::create('uri')
    ->SetLabel( t('Criteria URL') )

      ->SetDefaultValue('')
      ->SetRequired( FALSE )
      ->SetRevisionable( TRUE )
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
    ->setDisplayOptions('form', [
      'region' => 'content',//hidden or region id
      'type' => '',//widget or formatter
      'settings' => [],//settings for the type plugin
      'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
      'weight' => 14,//float
    ])
    ->setDisplayOptions('view', [
      'label' => 'inline',//inline above hidden
      'region' => 'content',//hidden or region id
      'type' => '',//widget or formatter
      'settings' => [],//settings for the type plugin
      'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
      'weight' => 14,//float
    ]);*/

    /*
    * $markdown
    */
    /*$fields['criteriaNarrative'] = BaseFieldDefinition::create('text_with_summary')
    ->SetLabel( t('Badge Completion Requirements') )
    ->setDescription('<a target="_blank" href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet">Markdown formatted</a> description of the criteria')
    ->SetDefaultValue('')
    ->SetRequired( FALSE )
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
      //'type' => '',//widget or formatter
      //'settings' => [],//settings for the type plugin
      //'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
      'weight' => 15,//float
    ])
    ->setDisplayOptions('view', [
      'label' => 'inline',//inline above hidden
      'region' => 'content',//hidden or region id
      //'type' => '',//widget or formatter
      //'settings' => [],//settings for the type plugin
      //'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
      'weight' => 15,//float
    ])
    ->setSettings([]);*/

    // 'Alignments' => [],
    $fields['alignments'] = BaseFieldDefinition::create('entity_reference')
      ->SetLabel( t('Graduation Requirements') )
      ->setDescription( 'List of objects describing objectives or educational standards' )
      ->SetDefaultValue('')
      ->SetRequired( FALSE )
      ->SetRevisionable( TRUE )
      ->setTranslatable(FALSE)
      ->setDefaultValue([])
      ->setCardinality(-1)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons',//widget or formatter
        'region' => 'right',//hidden or region id
        'settings' => [],//settings for the type plugin
        'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
        'weight' => 16,//float
      ])
      ->setDisplayOptions('view', [
        'label' => 'inline',//inline above hidden
        'region' => 'right',//hidden or region id
        'type' => 'entity_reference_label',//widget or formatter
        'weight' => 23,//float
        'settings' => [],//settings for the type plugin
        'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
      ])
      ->setSettings([
        'handler' => 'default:taxonomy_term',
        'handler_settings' => [
          'auto_create' => FALSE,
          'auto_create_bundle' => '',
          'target_bundles' => [
            'alignments' => "alignments",
          ],
          'sort' => [
            'field' => "name",
            'direction' => "asc",
          ],
        ],
        'target_type' => "taxonomy_term",
      ]);
    /*$fields['tags'] = BaseFieldDefinition::create('entity_reference')
      ->SetLabel( t('Tags') )
      ->setDescription( 'List of tags that describe the BadgeClass' )
      ->SetDefaultValue('')
      ->SetRequired( FALSE )
      ->SetRevisionable( TRUE )
      ->setTranslatable(FALSE)
      ->setDefaultValue([])
      ->setCardinality(-1)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'options_buttons',//widget or formatter
        'settings' => [],//settings for the type plugin
        'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
        'weight' => 16,//float
      ])
      ->setDisplayOptions('view', [
        'label' => 'inline',//inline above hidden
        //'region' => 'tags',//hidden or region id
        'type' => 'entity_reference_label',//widget or formatter
        'settings' => [],//settings for the type plugin
        'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
        'weight' => 16,//float
      ])
      ->setSettings([
        'handler' => 'default:taxonomy_term',
        'handler_settings' => [
          'auto_create' => FALSE,
          'auto_create_bundle' => '',
          'target_bundles' => [
            'tags' => "tags",
          ],
          'sort' => [
            'field' => "name",
            'direction' => "asc",
          ],
        ],
        'target_type' => "taxonomy_term",
      ]);*/


    /*
    * string($entityId)
    */
    $fields['issuer'] = BaseFieldDefinition::create('entity_reference')
      ->SetLabel( t('Issuer') )
      ->SetDescription( t('Who issued the badge') )
      ->SetRequired( TRUE )
      ->SetRevisionable( TRUE )
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        //'region' => 'published',//hidden or region id
        'type' => 'options_select',//widget or formatter
        /*'settings' => [//settings for the type plugin
          'allow_new' => false,
          'allow_existing' => true,
          'allow_duplicate' => false,
          'match_operator' => 'CONTAINS',
        ],*/
        'weight' => 11,//float
        'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
      ])
      ->setDisplayOptions('view', [
        'label' => 'inline',//inline above hidden
        //'region' => 'published',//hidden or region id
        'type' => 'entity_reference_label',//widget or formatter
        'settings' => [
          'link' => true,
        ],//settings for the type plugin
        'weight' => 11,//float
        'third_party_settings' => [],//Settings provided by other extensions through hook_field_formatter_third_party_settings_form()
      ])
      ->SetDefaultValue(2)
      //->getDefaultValueCallback('')
      ->setSettings([
        'target_type' => 'issuer',
        //'handler' => 'default:issuer',
        'handler' => 'views',
        'handler_settings' => [
          'view' =>[
            'view_name' => 'issuer_options',
            'display_name' => 'issuer_options_list',
            'argument' => [],
          ],
//          'target_bundles' => NULL,
//          'sort' => [
//            'field' => 'label',
//            'direction' => 'DESC',
//          ],
//          'auto_create' => TRUE,
        ],
      ]);

    return $fields;
  }
}
