<?php

use Symfony\Component\Debug\Debug;

$loader = require_once __DIR__.'/../vendor/autoload.php';

Debug::enable();
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$app = require __DIR__.'/../src/app.php';
require __DIR__.'/../config/dev.php';
require __DIR__.'/../src/controllers.php';
$app->run();
