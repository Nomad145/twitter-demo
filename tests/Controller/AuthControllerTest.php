<?php

namespace App\Test\Controller;

use App\Test\ApplicationTestCase;

/**
 * Class AuthControllerTest
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class AuthControllerTest extends ApplicationTestCase
{
    public function testUnauthenticatedHomePage()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/login'));
    }

    public function testLoginPage()
    {
        $this->client->followRedirects(true);
        $crawler = $this->client->request('GET', '/login');

        $this->assertSame('Login - Twitter Demo', $crawler->filter('title')->text());
    }

    public function testRegisterPage()
    {
        $this->client->followRedirects(true);
        $crawler = $this->client->request('GET', '/register');

        $this->assertSame('Register - Twitter Demo', $crawler->filter('title')->text());
    }
}
