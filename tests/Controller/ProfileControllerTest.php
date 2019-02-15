<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProfileControllerTest extends WebTestCase {

    /**
     * @dataProvider getUrlsForAnonymousUsers
     */
    public function testAccessDeniedForAnonymousUsers(string $httpMethod, string $url) {
        
        $client = static::createClient();
        $client->request($httpMethod, $url);

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertSame(
                'http://localhost/en/login', $response->getTargetUrl(), sprintf('The %s secure URL redirects to the login form.', $url)
        );
    }

    public function getUrlsForAnonymousUsers() {
        yield ['GET', '/en/profile/edit'];
        yield ['GET', '/en/profile/change-password'];
    }

    public function testEditUser() {
        $newUserEmail = 'admin_jane@symfony.com';

        $client = static::createClient([], [
                    'PHP_AUTH_USER' => 'jane_admin',
                    'PHP_AUTH_PW' => 'kitten',
        ]);
        $crawler = $client->request('GET', '/en/profile/edit');
        $form = $crawler->selectButton('Save changes')->form([
            'user[email]' => $newUserEmail,
        ]);
        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        /** @var User $user */
        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy([
            'email' => $newUserEmail,
        ]);
        $this->assertNotNull($user);
        $this->assertSame($newUserEmail, $user->getEmail());
    }

    public function testChangePassword() {
        $newUserPassword = 'new-password';

        $client = static::createClient([], [
                    'PHP_AUTH_USER' => 'jane_admin',
                    'PHP_AUTH_PW' => 'kitten',
        ]);
        $crawler = $client->request('GET', '/en/profile/change-password');
        $form = $crawler->selectButton('Save changes')->form([
            'change_password[currentPassword]' => 'kitten',
            'change_password[newPassword][first]' => $newUserPassword,
            'change_password[newPassword][second]' => $newUserPassword,
        ]);
        $client->submit($form);

        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertSame(
                '/en/logout', $response->getTargetUrl(), 'Changing password logout the user.'
        );
    }

}
