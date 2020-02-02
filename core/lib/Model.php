<?php
namespace core\lib;

use core\lib\Config;

class Model extends \PDO
{
	protected $table = '';

	public function __construct()
	{
		// 读取数据库配置文件
		$dbConfig = Config::get('database');

		try {
			$dsn = $dbConfig['type'] . ':dbname=' . $dbConfig['database'] . ';host=' . $dbConfig['host'] . ';port=' . $dbConfig['port'];
			parent::__construct($dsn, $dbConfig['username'], $dbConfig['password']);
		} catch (\PDOException $e) {
			h($e->getMessage());
		}
	}

	/**
	 * 查询单条数据
	 * 
	 * @return [type] [description]
	 */
	public function find($id = '')
	{
		$sql = 'SELECT * FROM ' . $this->table;

		if (!empty($id)) $sql .= ' WHERE id=' . intval($id);

		$stmt = $this->query($sql);

		if ($stmt === false) {
			h($this->errorInfo());
		}

		$data = $stmt->fetch(self::FETCH_ASSOC);

		return $data;
	}

	/**
	 * 查询多条数据
	 * 
	 * @return [type] [description]
	 */
	public function select()
	{
		$stmt = $this->query('SELECT * FROM ' . $this->table);

		if ($stmt === false) {
			h($this->errorInfo());
		}

		$data = $stmt->fetchAll(self::FETCH_ASSOC);

		return $data;
	}


}