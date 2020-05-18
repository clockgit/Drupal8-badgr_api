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
 * Defines the Assertion evidence entity.
 *
 * @ingroup badgr
 *
 * @ContentEntityType(
 *   id = "assertion_evidence",
 *   label = @Translation("Assertion evidence"),
 *   handlers = {
 *     "storage" = "Drupal\badgr\AssertionEvidenceStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\badgr\AssertionEvidenceListBuilder",
 *     "views_data" = "Drupal\badgr\Entity\AssertionEvidenceViewsData",
 *     "translation" = "Drupal\badgr\AssertionEvidenceTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\badgr\Form\AssertionEvidenceForm",
 *       "add" = "Drupal\badgr\Form\AssertionEvidenceForm",
 *       "edit" = "Drupal\badgr\Form\AssertionEvidenceForm",
 *       "delete" = "Drupal\badgr\Form\AssertionEvidenceDeleteForm",
 *     },
 *     "access" = "Drupal\badgr\AssertionEvidenceAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\badgr\AssertionEvidenceHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "assertion_evidence",
 *   data_table = "assertion_evidence_field_data",
 *   revision_table = "assertion_evidence_revision",
 *   revision_data_table = "assertion_evidence_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer assertion evidence entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/admin/badgr/assertion_evidence/{assertion_evidence}",
 *     "add-form" = "/admin/badgr/assertion_evidence/add",
 *     "edit-form" = "/admin/badgr/assertion_evidence/{assertion_evidence}/edit",
 *     "delete-form" = "/admin/badgr/assertion_evidence/{assertion_evidence}/delete",
 *     "version-history" = "/admin/badgr/assertion_evidence/{assertion_evidence}/revisions",
 *     "revision" = "/admin/badgr/assertion_evidence/{assertion_evidence}/revisions/{assertion_evidence_revision}/view",
 *     "revision_revert" = "/admin/badgr/assertion_evidence/{assertion_evidence}/revisions/{assertion_evidence_revision}/revert",
 *     "revision_delete" = "/admin/badgr/assertion_evidence/{assertion_evidence}/revisions/{assertion_evidence_revision}/delete",
 *     "translation_revert" = "/admin/badgr/assertion_evidence/{assertion_evidence}/revisions/{assertion_evidence_revision}/revert/{langcode}",
 *     "collection" = "/admin/badgr/assertion_evidence",
 *   },
 *   field_ui_base_route = "assertion_evidence.settings"
 * )
 */
class AssertionEvidence extends RevisionableContentEntityBase implements AssertionEvidenceInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getLabel() {
    return $this->get('label')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setLabel($name) {
    $this->set('label', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];

  }

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the assertion_evidence owner the
    // revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('description')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('description', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Assertion evidence entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Descriptive Title'))
      ->setDescription(t('Description of the Assertion evidence.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['url'] = BaseFieldDefinition::create('uri')
      ->SetLabel( t('Criteria URL') )
      ->setDescription(t('URL of a web page presenting evidence of the achievement'))
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
      ]);

    $fields['narrative'] = BaseFieldDefinition::create('text_long')
      ->SetLabel( t('Evidence Narrative') )
      ->setDescription(t('<a target="_blank" href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet">Markdown formatted</a> narrative that describes the achievement'))
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

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    //TODO add uri and long text
    return $fields;
  }

}
