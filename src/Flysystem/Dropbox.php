<?php

/**
 * @file
 * Contains \Drupal\flysystem_dropbox\Flysystem\Dropbox.
 */

namespace Drupal\flysystem_dropbox\Flysystem;

use Spatie\Dropbox\Client;
use Drupal\Core\Logger\RfcLogLevel;
use Drupal\flysystem\Flysystem\Adapter\MissingAdapter;
use Drupal\flysystem\Plugin\FlysystemPluginInterface;
use Drupal\flysystem\Plugin\FlysystemUrlTrait;
use Drupal\flysystem\Plugin\ImageStyleGenerationTrait;
use GuzzleHttp\Psr7\Uri;
use Spatie\FlysystemDropbox\DropboxAdapter;
use Drupal\flysystem_dropbox\Services\AutoRefreshingDropboxTokenService;
use Stevenmaguire\OAuth2\Client\Provider\Dropbox as DropboxProvider;

/**
 * Drupal plugin for the "Dropbox" Flysystem adapter.
 *
 * @Adapter(id = "dropbox")
 */
class Dropbox implements FlysystemPluginInterface {

  use FlysystemUrlTrait {
    getExternalUrl as getDownloadlUrl;
  }

  use ImageStyleGenerationTrait;

  /**
   * The Dropbox client.
   *
   * @var \Spatie\Dropbox\Client
   */
  protected $client;

  /**
   * The Dropbox client ID.
   *
   * @var string
   */
  protected $client_id;

  /**
   * The Dropbox client secret.
   *
   * @var string
   */
  protected $client_secret;

  /**
   * The path prefix inside the Dropbox folder.
   *
   * @var string
   */
  protected $prefix;

  /**
   * The Dropbox access code.
   *
   * @var string
   */
  protected $access_code;

  /**
   * Whether to serve files via Dropbox.
   *
   * @var bool
   */
  protected $usePublic;

  /**
   * Constructs a Dropbox object.
   *
   * @param array $configuration
   *   Plugin configuration array.
   */
  public function __construct(array $configuration) {
    $this->prefix = $configuration['prefix'] ?? '';
    $config = \Drupal::config('flysystem_dropbox.settings');
    $this->client_id = $config->get('flysystem_dropbox.client_id');
    $this->client_secret = $config->get('flysystem_dropbox.client_secret');
    $this->access_code = $config->get('flysystem_dropbox.access_code');
    $this->usePublic = !empty($configuration['public']);
  }

  /**
   * {@inheritdoc}
   */
  public function getAdapter() {
    try {
      $adapter = new DropboxAdapter($this->getClient(), $this->prefix);
    }

    catch (\Exception $e) {
      $adapter = new MissingAdapter();
    }

    return $adapter;
  }

  /**
   * {@inheritdoc}
   */
  public function getExternalUrl($uri) {
    if ($this->usePublic) {
      return $this->getPublicUrl($uri);
    }

    return $this->getDownloadlUrl($uri);
  }

  /**
   * {@inheritdoc}
   */
  public function ensure($force = FALSE) {
    try {
      $info = $this->getClient()->getAccountInfo();
    }
    catch (\Exception $e) {
      return [[
        'severity' => RfcLogLevel::ERROR,
        'message' => 'The Dropbox client failed with: %error.',
        'context' => ['%error' => $e->getMessage()],
      ]];
    }

    return [];
  }

  /**
   * Returns the public Dropbox URL.
   *
   * @param string $uri
   *   The file URI.
   *
   * @return string|false
   *   The public URL, or false on failure.
   */
  protected function getPublicUrl($uri) {
    $target = $this->getTarget($uri);

    // Quick exit for existing files.
    if ($link = $this->getSharableLink($target)) {
      return $link;
    }

    // Support image style generation.
    if ($this->generateImageStyle($target)) {
      return $this->getSharableLink($target);
    }

    return FALSE;
  }

  /**
   * Returns the Dropbox sharable link.
   *
   * @param string $target
   *   The file target.
   *
   * @return string|bool
   *   The sharable link, or false on failure.
   */
  protected function getSharableLink($target) {
    try {
      $link = $this->getClient()->createShareableLink('/' . $target);
    }
    catch (\Exception $e) {}

    if (empty($link)) {
      return FALSE;
    }

    $uri = (new Uri($link))->withHost('dl.dropboxusercontent.com');

    return (string) Uri::withoutQueryValue($uri, 'dl');
  }

  /**
   * Returns the Dropbox client.
   *
   * @return \Spatie\Dropbox\Client
   *   The Dropbox client.
   */
  protected function getClient() {
    if (!isset($this->client)) {
      $authClient = new DropboxProvider([
        'clientId' => $this->client_id,
        'clientSecret' => $this->client_secret,
      ]);

      $tokenProvider = new AutoRefreshingDropBoxTokenService($authClient);
      $token = $tokenProvider->getAccessToken();
      \Drupal::logger('flysystem_dropbox')->notice('Debug 4: ' . print_r($token,1));

      $this->client = new Client($token->getToken(), $authClient->getHttpClient());
    }

    return $this->client;
  }

}
