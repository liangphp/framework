<?php
namespace core\lib;

/**
 * 配置加载类
 */
class Config
{
	public static $cache = [];

	/**
	 * 获取配置项或读取整个配置文件
	 *
	 * get('database.host')
	 * get('database')
	 * 
	 * @return [type] [description]
	 */
	public static function get($fileOpt = '')
	{	
		$arr = explode('.', $fileOpt);

		if (count($arr) > 1) {
			// 读取配置项
			if (isset(self::$cache[$arr[0]][$arr[1]])) {
				return self::$cache[$arr[0]][$arr[1]];
			} else {
				$path = ROOT . '/config/' . $arr[0] . '.php';
				if (is_file($path)) {
					$conf = include $path;
					self::$cache[$arr[0]] = $conf;
					if (isset($conf[$arr[1]])) {
						return $conf[$arr[1]];
					} else {
						h('配置项不存在: ' . $arr[1]);	
					}
				} else {
					h('配置文件不存在: ' . $path);
				}
			}
		} else {
			// 读取配置文件
			if (isset(self::$cache[$arr[0]])) {
				return self::$cache[$arr[0]];
			} else {
				$path = ROOT . '/config/' . $arr[0] . '.php';
				if (is_file($path)) {
					$conf = include $path;
					self::$cache[$arr[0]] = $conf;
					return $conf;
				} else {
					h('配置文件不存在: ' . $path);
				}
			}
		}
	}
}