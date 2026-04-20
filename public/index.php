<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/../app/bootstrap.php';

use App\Core\App;

$app = new App();
$app->run();
