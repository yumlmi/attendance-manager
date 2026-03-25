<?php
return array(
	// ===== 基本 =====
	'_root_'  => 'auth/login',
	'_404_'   => 'welcome/404',    // 404 のルート

	// ===== 認証 =====
	'login'   => 'auth/login',
	'logout'  => 'auth/logout',
	'auth/register' => 'auth/register',

	// ===== ダッシュボード =====
	'dashboard' => 'dashboard/index',

	// ===== メンバー CRUD =====
	// R: 一覧
	'members'             => 'members/index',
	// C: 作成
	'members/create'      => 'members/create',
	// U: 更新
	'members/edit/:id'    => 'members/edit/$1',
	// D: 削除
	'members/delete/:id'  => 'members/delete/$1',

	// ===== 遅刻・欠席 CRUD =====
	// R: 一覧
	'attendances'            => 'attendances/index',
	// C: 作成
	'attendances/create'     => 'attendances/create',
	// U: 更新
	'attendances/edit/:id'   => 'attendances/edit/$1',
	// D: 削除
	'attendances/delete/:id' => 'attendances/delete/$1',

	// ===== 設定 =====
	'settings' => 'settings/index',

	// ===== API（AJAX/Knockout.js） =====
	'api/dashboard/summary'    => 'api/dashboard/summary',
	'api/attendances/list'     => 'api/attendances/list',
	'api/attendances/create'   => 'api/attendances/create',
	'api/attendances/update'   => 'api/attendances/update',
	'api/attendances/delete'   => 'api/attendances/delete',

	// ===== 既存サンプルルート =====
	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);
