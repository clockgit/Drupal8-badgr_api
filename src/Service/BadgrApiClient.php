<?php

namespace Drupal\badgr\Service;

use Drupal\Core\Config\ConfigFactory;
use GuzzleHttp\ClientInterface;
//use function GuzzleHttp\default_ca_bundle;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Messenger\MessengerInterface;
//use Drupal\badgr\Service\BadgrApiClientInterface;
use Drupal\image\Entity\ImageStyle;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Drupal\badgr\Entity\BadgrEntityInterface;

/**
 * Class BadgrApi.
 */
class BadgrApiClient implements BadgrApiClientInterface {
  /**
   * An http client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;
  /**
   * A configuration instance.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;
  /**
   * Badgr API Token.
   *
   * @var string
   */
  protected $token;
  /**
   * Badgr API user.
   *
   * @var string
   */
  protected $user;
  /**
   * Badgr API password.
   *
   * @var string
   */
  protected $pass;
  /**
   * Badgr API Base URI.
   *
   * @var string
   */
  protected $baseUri;
  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * @return string
   */
  public function getUser() {
    return $this->user;
  }

  /**
   * Constructs a new BadgrApi object.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   * @param $messenger
   * @throws \Exception
   */
  public function __construct(ClientInterface $http_client, ConfigFactory $config_factory, MessengerInterface $messenger) {
    $this->httpClient = $http_client;
    $this->messenger = $messenger;
    $config = $config_factory->get('badgr.settings');
    $this->baseUri = $config->get('base_uri');
    $this->user = $config->get('user');
    $this->pass = $config->get('pass');
    if( empty($this->baseUri) || empty($this->user) || empty($this->pass)) {
      throw new \Exception('Config values need to be set for Badgr');
    }
  }

  /**
   * Utilizes Drupal's httpClient to connect to Badgr APIl.
   *
   * API Docs: https://api.badgr.io/docs/v2/
   *
   * @param string $method
   *   get, post, patch, delete, etc. See Guzzle documentation.
   * @param string $endpoint
   *   The badgr API endpoint (ex. )
   *   TODO: enter api endpoint example.
   * @param array $body
   *   (converted to JSON)
   *
   * @return array
   * @throws \Exception
   */
  public function connect($method, $endpoint, array $body) {
    if (empty($this->token)) {
      $this->getToken();
    }
    try {
      $response = $this->httpClient->{$method}(
        $endpoint,
        [
          'base_uri' => $this->baseUri,
          'headers' => [
            'accept' => 'application/json',
            'Content-Type' => 'application/json',
            'authorization' => "Token $this->token",
          ],
          'json' => $body,
        ]
      );
    } catch (RequestException $exception) {
      $this->messenger->addError(t('Error: %error', ['%error' => $exception->getMessage()]));
      throw new \Exception('\badgr\Service\BadgrApiClient->connect(): ' . $exception->getMessage());
    }
    return $response;
  }


  /**
   * @param string $entityType
   * @param string $entityId
   * @return string
   * @throws \Exception
   */
  private function buildEndpoint($entityType, $entityId) {
    switch ($entityType) {
      case 'issuer':
        return empty($entityId) ? '/v2/issuers' : '/v2/issuers/' . $entityId;
      case 'badge_class':
        return empty($entityId) ? '/v2/badgeclasses' : '/v2/badgeclasses/' . $entityId;
      default:
        throw new \Exception('\badgr\Service\BadgrApiClient->buildEndpoint(): $entityType = ' . $entityType);
    }
  }

  private function getBase64Img($image) {
    //Save badgr_image styled image because it does not get created when called from the command line
    $image_uri = $image->getFileUri();
    $styles = ImageStyle::loadMultiple();
    foreach ($styles as $style) {
      $destination = $style->buildUri($image_uri);
      $style->createDerivative($image_uri, $destination);
    }

    $uri = ImageStyle::load('badgr_image')->buildUri($image_uri);
    $stream_wrapper_manager = \Drupal::service('stream_wrapper_manager')->getViaUri($uri);
    $uri = $stream_wrapper_manager->realpath();
    $im = file_get_contents($uri);
    return'data:image/png;base64,' . base64_encode($im);
  }

  /**
   * Sets the http request type
   *
   * @param string $type
   * @param string $entityId
   * @return string
   * @throws \Exception
   */
  private function buildMethod($type, $entityId) {
    switch ($type) {
      case 'save':
        return empty($entityId) ? 'post' : 'put';
      case 'delete':
        if (!empty($entityId)) {
          return 'delete';
        }
        else {
          throw new \Exception('$entityId empty with $type == delete in \badgr\Service\BadgrApiClient->buildMethod()');
        }
      case  'import':
        return 'get';
      default:
        throw new \Exception('Unknown $type = ' . $type . ' in badgr\Service\BadgrApiClient->buildMethod()');
    }
  }

  /**
   * @param \Drupal\badgr\Entity\BadgrEntityInterface $entity
   *
   * @return array
   * @throws \Exception
   */
  function badgerValues(BadgrEntityInterface $entity) {
    $return = [
      'entityId' => $entity->get('entityId')->value,
      //'openBadgeID => '',//TODO should we set this if we have it???
      'createdAt' => $entity->get('createdAt')->value,
      //'createdBy => '',//defaults to account owner
      'name' => $entity->get('label')->value,
      'image' => $this->getBase64Img($entity->get('image')->referencedEntities()[0]),
      'description' => $entity->get('description')->value,
    ];
    switch($entity->getEntityType()->get('id')) {
      case 'issuer':
        $return['entityType'] = 'Issuer';
        $return['url'] = $entity->get('url')->value;
        $return['email'] = $this->getUser();
        break;
      case 'badge_class':
        $return['entityType'] = 'BadgeClass';
        $return['issuer'] = $entity->get('issuer')->referencedEntities()[0]->get('entityId')->value;
        $return['issuerOpenBadgeId'] = $entity->get('issuer')->referencedEntities()[0]->get('openBadgeId')->value;
        //changed url to point to entity view page
        $return['criteriaUrl'] = $entity->url('canonical',['absolute'=>TRUE]);//$entity->get('criteriaUrl')->value;
        //leave Narrative blank
        //$return['criteriaNarrative'] = $entity->get('criteriaNarrative')->value;
        //TODO Add Alignments for Badge Class
        //$return['alignments'] = [];
        //TODO Aggregate All Tag Fields Values
        //$return['tags'] = [];
        //ksm($return);
        break;
      default:
        throw new \Exception('Unknown $entity->get(\'entityId\')->value = ' . $entity->get('entityId')->value . ' in badgr\Service\BadgrApiClient->badgrValues()');
    }
    return $return;
  }

  /**
   * @param \Drupal\badgr\Entity\BadgrEntityInterface $entity
   * @param string $type
   * @return array
   * @throws \Exception
   */
  public function apiCall(BadgrEntityInterface $entity, $type = 'save') {
    //check settings



    $entityId = $entity->get('entityId')->value;
    $method = $this->buildMethod($type, $entityId);
    $endpoint = $this->buildEndpoint($entity->getEntityType()->get('id'), $entityId);
    $badgrValues = $this->badgerValues($entity);
    $response = $this->connect($method, $endpoint, $badgrValues);
    $body = json_decode($response->getBody()->getContents(), TRUE);
    $success = [200, 201, 204];
    if( !in_array($response->getStatusCode(), $success) ){
      throw new \Exception('API responded with: ' . $response->getStatusCode());
    }
    if($type === 'save') {
      return $body;
    } else {
      return $response;
    }
  }


  public function import() {
    //TODO create form, route, and code to import from Badgr
    /*
    Use ...........https://api.drupal.org/api/drupal/core%21includes%21entity.inc/function/entity_create/8.6.x
    Entity::create()
    or maybe
    \Drupal::entityTypeManager()
      ->getStorage($entity_type)
      ->create($values);
    */
  }

  /**
   * Generate authentication token.
   */
  private function getToken() {
    $body['username'] = $this->user;
    $body['password'] = $this->pass;
    $response = $this->httpClient->post('api-auth/token', ['base_uri' => $this->baseUri, 'json' => $body]);
    $response = json_decode($response->getBody()->getContents(), TRUE);
    $this->token = $response['token'];
  }
}
