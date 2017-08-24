<?php

namespace App\Test\Controller;

use App\Test\ApplicationTestCase;

/**
 * Class AuthControllerTest
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class AuthControllerTest extends ApplicationTestCase
{
    /**
     * @group functionaltest
     */
    public function testUnauthenticatedHomePage()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/login'));
    }

    /**
     * @group functionaltest
     */
    public function testLoginPage()
    {
        $this->client->followRedirects(true);
        $crawler = $this->client->request('GET', '/login');

        $this->assertSame('Login - Twitter Demo', $crawler->filter('title')->text());
    }

    /**
     * @group functionaltest
     */
    public function testRegisterPage()
    {
        $this->client->followRedirects(true);
        $crawler = $this->client->request('GET', '/register');

        $this->assertSame('Register - Twitter Demo', $crawler->filter('title')->text());
    }
}
