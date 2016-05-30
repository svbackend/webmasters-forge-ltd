<?php

error_reporting(-1);

require_once __DIR__ . '/vendor/autoload.php';

use system\components\App;

$configuration = require_once 'system/config/app.php';

(new App($configuration))->run();
