<?php
namespace app\index\controller;

use bl\Db;
use core\lib\View;
use app\index\model\User as UserModel;

class Index
{
	/**
	 * index/index/index
	 */
	public function index($id = 12, $age = 34)
	{
		
		$data = [
			['name' => 123],
			['name' => 456],
		];

		echo '添加数据条数: ' . Db::table('user')->insertAll($data);

		// 删除数据
		// $reusult = Db::table('user')->delete(true);
		// var_dump('删除数据条数: '  . $reusult);

		// var_dump('请求参数: id ' . $id . ' age ' . $age);

		// View::assign('data', 'hello');
		// View::assign('person', 'world!');
		// View::assign('arr1', ['张三','李四','王五']);
		// View::assign('arr2', ['abc', 'def', 'ghi']);
		// View::assign('a', '2');

		// View::fetch('index');


		// $userModel = new UserModel;

		// // 查询id为2的单条数据
		// $data = $userModel->find(2);

		// // 模板赋值
		// View::assign('id', $data['id']);
		// View::assign([
		// 	'name' => $data['name'],
		// 	'pass' => $data['pass'],
		// ]);

		// // 模板渲染
		// View::fetch();
	}
}