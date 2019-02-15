<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase {

    /**
     * @dataProvider getUrlsForAnonymousUsers
     */
    public function testAccessDeniedForAnonymousUsers(string $httpMethod, string $url) {
        
        $client = static::createClient();
        $client->request($httpMethod, $url);

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
        
    }

    public function getUrlsForAnonymousUsers() {
        yield ['GET', '/es/user'];
        yield ['GET', '/es/user/edit'];
        yield ['GET', '/es/user/add'];
    }

}
