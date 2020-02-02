<?php
namespace core\lib;

/**
 * 日志加载类
 */
class Log
{
	public static $class;

	/**
	 * 确定日志的存储方式
	 * 
	 * @return [type] [description]
	 */
	public static function init()
	{
		$drive = \core\lib\Config::get('log.drive');

		$class = '\\core\\lib\drive\\log\\' . $drive;

		if (!is_object($class)) self::$class = new $class;
	}

	/**
	 * 执行日志写入
	 * 
	 * @param  [type] $message [description]
	 * @return [type]          [description]
	 */
	public static function log($message)
	{
		self::$class->write($message);
	}
}