<?php

mb_internal_encoding('UTF-8');

require_once(__DIR__ . '/../vendor/autoload.php');

$configProvider = require_once(__DIR__ . '/../config/config.php');

$application = new \nigma\component\application\WebApplication($configProvider);
\nigma\component\application\Application::setInstance($application);

$application->run();