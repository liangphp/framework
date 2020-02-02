<?php
namespace core\lib;

class Route
{
	public $app;
	public $ctrl;
	public $action;
	public $param;

	/**
	 * 构造方法 路由初始化
	 */
	public function __construct()
	{
		// P('路由启动成功');

		$routeConfig = \core\lib\Config::get('route');

		if (isset($_SERVER['REQUEST_URI']) && trim($_SERVER['REQUEST_URI'], '/') != '') {
			$uri = $_SERVER['REQUEST_URI'];
			$arr = explode('/', trim($uri, '/'));
			$original = count($arr);

			$this->app = $arr[0];
			unset($arr[0]);

			if (isset($arr[1])) {
				$this->ctrl = $arr[1];
				unset($arr[1]);
			} else {
				$this->ctrl = $routeConfig['default_controller'];
			}
			if (isset($arr[2])) {
				$this->action = $arr[2];
				unset($arr[2]);
			} else {
				$this->action = $routeConfig['default_action'];
			}

			for ($i = 0, $k = $original - count($arr); $i < floor(count($arr) / 2); $i++, $k += 2) {
				$this->param[$arr[$k]] = $arr[$k + 1];
			}
		} else {
			// 默认应用、控制器、操作
			$this->app    = $routeConfig['default_app'];
			$this->ctrl   = $routeConfig['default_controller'];
			$this->action = $routeConfig['default_action'];
		}
	}

	/**
	 * 注册路由
	 * 
	 * @return [type] [description]
	 */
	public static function rule()
	{

	}
}