<?php

namespace Drupal\oe_virtual_assistant\EventSubscriber;

use Drupal\Core\Session\AccountInterface;
use Drupal\jwt\Authentication\Event\JwtAuthEvents;
use Drupal\jwt\Authentication\Event\JwtAuthGenerateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

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
   * The current request.
   *
   * @var Symfony\Component\HttpFoundation\RequestStack
   */
  protected RequestStack $requestStack;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack service.
   */
  public function __construct(AccountInterface $current_user, RequestStack $request_stack) {
    $this->currentUser = $current_user;
    $this->requestStack = $request_stack;
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
    $event->addClaim('name', (string) $this->currentUser->getDisplayName());
    $event->addClaim('sub', $this->currentUser->getEmail());
    $event->addClaim('iss', $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost());
    // Remove custom Drupal claims.
    $event->removeClaim('drupal');
  }

}
