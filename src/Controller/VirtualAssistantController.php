<?php

namespace Drupal\oe_virtual_assistant\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Virtual assistant controller.
 */
class VirtualAssistantController extends ControllerBase {

  /**
   * Current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   Current user.
   */
  public function __construct(AccountInterface $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')
    );
  }

  /**
   * Main callback.
   *
   * @return array
   *   Render array.
   */
  public function content() {
    $config = \Drupal::config('oe_virtual_assistant.settings');
    $build = [
      '#markup' => '<div id="virtual-assistant"></div>',
      '#attached' => [
        'library' => [
          'oe_virtual_assistant/chat',
        ],
        'drupalSettings' => [
          'oe_virtual_assistant' => [
            'backend_service_url' => $config->get('backend_service_url'),
          ],
        ],

      ],
    ];
    return $build;
  }

  /**
   * Access callback.
   *
   * @return \Drupal\Core\Access\AccessResult|\Drupal\Core\Access\AccessResultAllowed|\Drupal\Core\Access\AccessResultNeutral
   *   Access object.
   */
  public function access() {
    return AccessResult::allowedIf($this->currentUser->hasPermission('access virtual assistant chat'));
  }

}
