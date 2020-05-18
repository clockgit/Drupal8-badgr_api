<?php

namespace Drupal\badgr\Service;

/**
 * Interface BadgrApiInterface.
 */
interface BadgrApiClientInterface {
  /**
   * @param $method
   * @param $endpoint
   * @param array $body
   * @return mixed
   */
  public function connect($method, $endpoint, array $body);
}
