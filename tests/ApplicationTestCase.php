<?php

namespace App\Test;

use Silex\WebTestCase;

/**
 * Class ApplicationTestCase
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
class ApplicationTestCase extends WebTestCase
{
    protected $client;

    public function createApplication()
    {
        $app = require __DIR__.'/../src/app.php';
        require __DIR__.'/../config/test.php';
        require __DIR__.'/../src/controllers.php';
        $app['session.test'] = true;

        return $this->app = $app;
    }

    public function setUp()
    {
        parent::setUp();

        $this->client = $this->createClient();
    }
}
