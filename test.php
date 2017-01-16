<?php

define('ROOT_PATH', dirname(realpath(__DIR__)));

$al = require __DIR__ . '/vendor/autoload.php';

// should be in FastFrame\App\Kernel
$env          = new \FastFrame\Kernel\Environment(__DIR__, $al);
$isProduction = (getenv('FASTFRAME_ENV') ?: 'development') === 'production';
if ($isProduction && file_exists($file = "{$env->rootPath()}/storage/cache/config.php")) {
	$env->load(require_once($file));
}
else {
	$env->load();
}

if (!$env->loaded()) {
	$loader = new \josegonzalez\Dotenv\Loader(__DIR__);
	$loader->parse();
	$env->load($loader->toArray());
}

$kernel = (new \FastFrame\Project\Kernel(ROOT_PATH, $al))->load(Console::class)->run();