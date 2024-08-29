<?php

namespace Drupal\oe_virtual_assistant\EventSubscriber;

use Drupal\Core\Session\AccountInterface;
use Drupal\jwt\Authentication\Event\JwtAuthEvents;
use Drupal\jwt\Authentication\Event\JwtAuthGenerateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * JWT Auth Issuer Subscriber set claims on a JWT being issued.
 */
class JwtAuthIssuerSubscriber implements EventSubscriberInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected AccountInterface $currentUser;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The current user.
   */
  public function __construct(AccountInterface $user) {
    $this->currentUser = $user;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = [];
    $events[JwtAuthEvents::GENERATE][] = ['setClaims', 98];
    return $events;
  }

  /**
   * Sets claims for a consumer on the JWT.
   *
   * @param \Drupal\jwt\Authentication\Event\JwtAuthGenerateEvent $event
   *   The event.
   */
  public function setClaims(JwtAuthGenerateEvent $event) {
    $event->addClaim(['user', 'email'], $this->currentUser->getEmail());
    $event->addClaim(['user', 'name'], $this->currentUser->getAccountName());
  }

}
