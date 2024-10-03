<?php

declare(strict_types=1);

namespace Drupal\Tests\oe_virtual_assistant\Functional;

use Drupal\Tests\BrowserTestBase;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Smoke test.
 */
class JwtTest extends BrowserTestBase {

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
   * Test token issuing.
   */
  public function testTokenIssuing(): void {
    // Assert that anonymous users cannot request a token.
    $session = $this->getSession();
    $session->visit('/jwt/token');
    $this->assertEquals(403, $session->getStatusCode());

    // Create a user and login.
    $user = $this->drupalCreateUser([], NULL, FALSE, [
      'mail' => 'user@email.com',
    ]);
    $this->drupalLogin($user);
    $session = $this->getSession();

    // Request a token and assert its content.
    $session->visit('/jwt/token');
    $this->assertEquals(200, $session->getStatusCode());
    $content = $session->getPage()->getContent();
    $data = JWT::decode(json_decode($content)->token, new Key('12345678123456781234567812345678', 'HS256'));
    $this->assertEquals('user@email.com', $data->sub, "The 'sub' claim does not match the expected value.");
    $this->assertEquals('http://localhost', $data->iss, "The 'iss' claim does not match the expected value.");
    $this->assertEquals(3600, $data->exp - $data->iat, "The difference between 'exp' and 'iat' is not 3600 seconds.");

    // Assert custom ISS value.
    $this->config('oe_virtual_assistant.settings')->set('token_issuer_url', 'http://example.com')->save();
    $session->visit('/jwt/token');
    $this->assertEquals(200, $session->getStatusCode());
    $content = $session->getPage()->getContent();
    $data = JWT::decode(json_decode($content)->token, new Key('12345678123456781234567812345678', 'HS256'));
    $this->assertEquals('http://example.com', $data->iss, "The 'iss' claim does not match the expected value.");
  }

}
