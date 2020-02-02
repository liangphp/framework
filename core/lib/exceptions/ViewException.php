<?php
namespace core\lib\exceptions;

/**
 * 视图异常处理类
 */
class ViewException extends \Exception
{
	public function render()
	{
		echo $this->getMessage();
	}
}