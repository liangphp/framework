<?php
namespace core\lib\exceptions;

/**
 * 控制器异常处理类
 */
class ControllerException extends \Exception
{
	public function render()
	{
		$path = CORE . '/lib/views/controller_exception.html';

		if (is_file($path)) {
			include $path;
		} else {

		}
	}
}