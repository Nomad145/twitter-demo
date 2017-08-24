<?php

namespace App\Test\Controller;

use App\Test\ApplicationTestCase;

/**
 * Class TwitterControllerTest
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class TwitterControllerTest extends ApplicationTestCase
{
    public function testAuthenticatedHomePage()
    {
        $this->markTestIncomplete('This test needs fixtures.');

        $crawler = $this->client->request('GET', '/', [], [], [
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW' => 'foo',
        ]);

        $this->assertSame('Home - Twitter Demo', $crawler->filter('title')->text());
    }
}
