<?php

require_once __DIR__ . '/../bootstrap/app.php';

// Load routes
require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../routes/api.php';

// Run the application
$app->run();