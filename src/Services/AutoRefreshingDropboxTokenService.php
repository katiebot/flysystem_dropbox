<?php

namespace Drupal\flysystem_dropbox\Services;

use Spatie\Dropbox\TokenProvider;
use Stevenmaguire\OAuth2\Client\Provider\Dropbox;
use Drupal\Core\State\StateInterface;

class AutoRefreshingDropboxTokenService {

  /**
   * The Dropbox access code.
   *
   * @var string
   */
  protected $access_code;

  /**
   * The path prefix inside the Dropbox folder.
   *
   * @var string
   */
  protected $prefix;

  protected $authClient;

  /**
   * State system.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Constructs a Dropbox object.
   */
  public function __construct($authClient) {
    $config = \Drupal::config('flysystem_dropbox.settings');
    $this->access_code = $config->get('flysystem_dropbox.access_code');
    $this->authClient = $authClient;
  }

  public function getAccessToken() {
    $token = $this->getToken();
    if (!$token) {
      $token = $this->setUp($this->access_code);
    }
    elseif ($token->hasExpired()) {
      $token = $this->refreshToken($token);
    }

    return $token;
  }

  public function setUp($access_code) {
    $token = $this->authClient->getAccessToken('authorization_code', ['code' => $access_code]);
    $this->storeToken($token);

    return $token;
  }
  public function refreshToken($token) {
    $token = $this->authClient->getAccessToken('refresh_token', ['refresh_token' => $token->getRefreshToken()]);
    $this->storeToken($token);
    \Drupal::logger('flysystem_dropbox')->notice('Refreshed Dropbox token: ' . $token->getToken());

    return $token;
  }


  public function storeToken($token) {
    \Drupal::state()->set('flysystem_dropbox_token', $token);
  }


  public function getToken() {
    return \Drupal::state()->get('flysystem_dropbox_token');
  }
}
