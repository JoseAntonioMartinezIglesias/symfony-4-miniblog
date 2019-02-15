<?php

namespace App\Tests\Security;

use App\Security\PasswordGenerator;
use PHPUnit\Framework\TestCase;

class PasswordGeneratorTest extends TestCase {

    public function testGetRandomPassword() {
        $passwordGenerator = new PasswordGenerator();
        $this->assertNotEmpty($passwordGenerator->getRandomPassword(2, 5));
        $this->assertNotEmpty($tokenGenerator->getRandomPassword(5,3));
    }

}
