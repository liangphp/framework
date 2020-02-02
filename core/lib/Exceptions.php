<?php
namespace core\lib;

/**
 * 异常处理类
 */
class Exceptions
{
	public static function init()
	{
		set_exception_handler(['core\lib\Exceptions', 'exception']);
	}

	/**
	 * 自定义异常处理函数
	 * 
	 * @param  [type] $e [description]
	 * @return [type]    [description]
	 */
	public static function exception($e)
	{
		if (method_exists($e, 'render')) {
			$e->render();
		} else {
			die($e->getMessage());
		}
	}
}