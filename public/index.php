<?php

/**
 * 入口文件
 *
 * PHP >= 7.2.x
 */

require_once __DIR__ . '/../vendor/autoload.php';

define('ROOT', __DIR__ . '/../');

define('APP', ROOT . '/app');
define('CORE', ROOT . '/core');

define('DEBUG', true);

if (DEBUG) {
	ini_set('display_errors', 'On');
} else {
	ini_set('display_errors', 'Off');
}

include CORE . '/common/function.php';

include CORE . '/Start.php';

// 注册自动加载函数
spl_autoload_register('\core\Start::load');

// 启动框架
\core\Start::run();