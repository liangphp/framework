<?php

// 模板引擎
return [
	// 模板引擎类型
	'type'               => 'BestLanguage',

	// 模板文件后缀名
	'suffix'             => 'html',
	// 模板文件目录
	'templateDir'        => 'view',
	// 编译文件目录
	'compileDir'         => 'compile',
	// 缓存文件后缀名
	'suffix_cache'       => 'html',
	// 多久自动更新 单位: 秒
	'cache_time'         => 7200,
	// 是否需要编译成静态的HTML文件
	'cache_html'         => false,
	// 是否支持原生php代码
	'php_turn'           => true,
	// 模板输出替换
	'tpl_replace_string' => [
		'__STATIC__' => '/stasstic',
		'__JS__'     => '/static/js',
	]
];