<?php

namespace Drupal\oe_virtual_assistant\Controller;

use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Virtual assistant controller.
 */
class VirtualAssistantController extends ControllerBase {

  /**
   * Configuration.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected ImmutableConfig $config;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ImmutableConfig $config
   *   Module configuration.
   */
  public function __construct(ImmutableConfig $config) {
    $this->config = $config;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')->get('oe_virtual_assistant.settings')
    );
  }

  /**
   * Main callback.
   *
   * @return array
   *   Render array.
   */
  public function content() {
    return [
      '#markup' => '<div id="virtual-assistant"></div>',
      '#attached' => [
        'library' => [
          'oe_virtual_assistant/chat',
        ],
        'drupalSettings' => [
          'oe_virtual_assistant' => [
            'backend_service_url' => $this->config->get('backend_service_url'),
          ],
        ],

      ],
    ];
  }

}
