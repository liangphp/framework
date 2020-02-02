<?php
namespace app\index\controller;

use core\lib\View;
use app\index\model\User as UserModel;

class Index
{
	/**
	 * index/index/index
	 */
	public function index($id = 12, $age = 34)
	{
		var_dump('请求参数: id ' . $id . ' age ' . $age);

		View::assign('data', 'hello');
		View::assign('person', 'world!');
		View::assign('arr1', ['张三','李四','王五']);
		View::assign('arr2', ['abc', 'def', 'ghi']);
		View::assign('a', '2');

		View::fetch('index');


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