<?php


require_once __DIR__ . '/../bootstrap/app.php';

// Load routes
require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../routes/api.php';

// 全局异常捕获与日志记录
try {
	$app->run();
} catch (Throwable $e) {
	// 简单日志写入（可替换为更完善的日志服务）
	$logFile = __DIR__ . '/../storage/logs/error.log';
	$msg = '[' . date('Y-m-d H:i:s') . "] Uncaught Exception: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n" . $e->getTraceAsString() . "\n";
	file_put_contents($logFile, $msg, FILE_APPEND | LOCK_EX);
	http_response_code(500);
	echo 'Internal Server Error';
}