<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_virtual_assistant\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Smoke test.
 */
class AccessTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'system',
    'oe_virtual_assistant',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Test access to virtual assistant page.
   */
  public function testAccess(): void {
    // Create a user that cannot access the virtual assistant.
    $user = $this->drupalCreateUser();

    $this->drupalLogin($user);
    $session = $this->getSession();
    $session->visit('/admin/virtual-assistant');
    $this->assertEquals(403, $session->getStatusCode());

    // Create a user that can access the virtual assistant.
    $user = $this->drupalCreateUser([
      'access virtual assistant chat',
    ]);

    $this->drupalLogin($user);
    $session = $this->getSession();
    $session->visit('/admin/virtual-assistant');
    $this->assertEquals(200, $session->getStatusCode());
  }

}
