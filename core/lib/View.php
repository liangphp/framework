<?php
namespace core\lib;

use core\lib\Route;
use core\lib\Config;
use core\lib\Compile;

class View
{
	// 配置
	private static $config = [];
	// 值栈
	private static $assign = [];
	// 模板文件
	public static $templateFile;
	// 缓存文件
	public static $cacheFile;
	// 编译文件
	public static $compileFile;

	/**
	 * 配置初始化
	 * 
	 * @return [type] [description]
	 */
	private static function initialize()
	{
		// 读取配置文件
		self::$config = Config::get('view');
		
		$route = new Route;
		
		$appView = ROOT . '/app/' . $route->app . '/' . self::$config['templateDir'];


		if (is_dir($appView)) {
			self::$config['templateDir'] = $appView;
		} else {
			self::$config['templateDir'] = ROOT . '/' . self::$config['templateDir'];	
		}
		
		self::$config['compileDir']  = ROOT . '/runtime/' . $route->app . '/' . self::$config['compileDir'];

		// 反斜线转为政协向
		$compileDir  = str_replace('\\', '/', str_replace('\\', '/', self::$config['compileDir']));
		$templateDir = str_replace('\\', '/', str_replace('\\', '/', self::$config['compileDir']));

		// 目录不存在则自动创建
		!is_dir($compileDir) && mkdir($compileDir, 0777, true);
		!is_dir(self::$config['templateDir']) && mkdir(self::$config['templateDir'], 0777, true);
	}


	/**
	 * 模板赋值
	 * 
	 * @return [type] [description]
	 */
	public static function assign($name = '', $value = '')
	{
		// 单个赋值
		if (is_string($name)) {
			self::$assign[$name] = $value;
		}

		// 批量赋值
		if (is_array($name)) {
			foreach ($name as $k => $v) {
				self::$assign[$k] = $v;
			}
		}
	}

	/**
	 * 模板渲染
	 * 
	 * @return [type] [description]
	 */
	public static function fetch($file = '')
	{

		self::initialize();

		// 文件路径初始化
		self::$cacheFile    = self::$config['compileDir'] . '/' . md5($file) . '.' . self::$config['suffix_cache'];
		self::$compileFile  = self::$config['compileDir'] . '/' . md5($file) . '.php';

		###########
		$route = new Route;
		$app    = $route->app;
		$ctrl   = $route->ctrl;
		$action = $route->action;
		if (strpos($file, '.') !== false) {
			$arr = explode('.', $file);

			if (count($arr) == 2) {
				$ctrl   = $arr[0];
				$action = $arr[1];
			}

			if (count($arr) == 3) {
				$app    = $arr[0];
				$ctrl   = $arr[1];
				$action = $arr[2];
			}
		} else if ($file != '') {
			$action = $file;
		}
		self::$templateFile = self::$config['templateDir'] . '/' . $app . '/' . $ctrl . '/' . $action . '.' . self::$config['suffix'];
		###########

		// 判断模板文件是否存在
		!is_file(self::$templateFile) && exit('模板文件不存在: ' . self::$templateFile);

		extract(self::$assign, EXTR_OVERWRITE);

		// 是否需要编译成静态的HTML文件
		if (self::$config['cache_html'] === true) {

		} else {
			// 编译文件不存在或
			// 编译文件的修改时间小于模板文件的修改时间
			// 则生成/重新成功编译文件
			if (!is_file(self::$compileFile) || filemtime(self::$compileFile) < filemtime(self::$templateFile)) {
                $compile = new Compile(self::$templateFile, self::$compileFile, self::$config);
                $compile->assign = self::$assign;
                $compile->compile();
                var_dump('生成了编译文件');
                include self::$compileFile;
            }else {
                include self::$compileFile;
            }
		}
	}
}