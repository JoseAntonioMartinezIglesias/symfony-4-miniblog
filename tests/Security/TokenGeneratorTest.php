<?php

namespace App\Tests\Security;

use App\Security\TokenGenerator;
use PHPUnit\Framework\TestCase;

class TokenGeneratorTest extends TestCase {

    public function testGetRandomSecureToken() {
        $tokenGenerator = new TokenGenerator();
        $this->assertNotEmpty($tokenGenerator->getRandomSecureToken(10));
        $this->assertNotEmpty($tokenGenerator->getRandomSecureToken(5));
    }

}
