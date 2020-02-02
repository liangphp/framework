<?php
namespace core\lib\drive\log;

// 文件系统日志驱动
class File
{
	public $path;

	public function __construct()
	{
		$option = \core\lib\Config::get('log.option');

		$app = (new \core\lib\Route)->app;

		$this->path = $option['path'] . '/' . $app . '/log';
	}

	public function write($message)
	{
		!is_dir($this->path) && mkdir($this->path, 0777, true);

		file_put_contents($this->path . '/' . date('Y年m月d号 H时i分') . '.txt', $message . PHP_EOL, FILE_APPEND);
	}
}