<?php
namespace core;

use bl\Db;
use core\lib\Config;
use core\lib\Log;
use core\lib\Exceptions;
use core\lib\exceptions\ControllerException;

class Start
{
	public static $classMap = [];

	// 启动框架
	public static function run()
	{
		// 设置数据库连接参数
		Db::setConfig(\core\lib\Config::get('database'));

		// 设置默认时区
		$timezone = \core\lib\Config::get('app.default_timezone');
		date_default_timezone_set($timezone);

		// 异常处理类初始化
		Exceptions::init();

		// 日志初始化
		Log::init();
		
		// p('框架启动成功');

		$route = new \core\lib\Route;

		$app    = $route->app;
		$ctrl   = $route->ctrl;
		$action = $route->action;
		$param  = $route->param;

		$routeConfig = \core\lib\Config::get('route');

		$path = APP . '/' . $app . '/controller/' . $ctrl . $routeConfig['controller_suffix'] . '.php';
		$class = '\\app\\' . $app . '\\controller\\' . $ctrl . $routeConfig['controller_suffix'];

		if (is_file($path)) {
			include $path;
			$obj = new $class;
			$controller_suffix = Config::get('route.controller_suffix');
			// 写入日志
			Log::log(date('Y-m-d H:i:s') . ' 调用' . $app . '应用' . $ctrl . $controller_suffix . '控制器'. $action . '方法');

			// 判断路由中是否有参数
			if (!empty($param)) {
				// 获取方法参数
				$data = (new \ReflectionMethod($class, $action))->getParameters();
				$methodParam = [];
				foreach ($data as $key => $value) {
				    $methodParam[] = $value->name;
				}
				foreach (array_keys($param) as $key => $value) {
					if ($value != $methodParam[$key]) {
						die('方法参数对应错误: ' . $value);
					}
				}
				// 重置数组的键名
				$new = array_values($param);
				$obj->$action(...$new);
			} else {
				$obj->$action();
			}
		} else {
			throw new ControllerException("控制器文件不存在: " . str_replace('\\', '/', $path));
		}
	}

	// 注册的自动加载函数
	public static function load($class)
	{
		if (isset(self::$classMap[$class])) {
			return true;
		} else {
			$className = str_replace('\\', '/', $class);
			$path = ROOT . '/' . $className . '.php';
			if (is_file($path)) {
				include $path;
				self::$classMap[$class] = $class;
			} else {
				h('核心类库文件不存在: ' . $path);
			}
		}
	}

}