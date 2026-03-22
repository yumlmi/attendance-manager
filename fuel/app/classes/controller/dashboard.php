<?php

/**
 * ログイン後のトップ画面を扱うコントローラ
 */
class Controller_Dashboard extends Controller_Base
{
	/**
	 * ダッシュボードはログイン必須
	 */
	protected $require_login = true;

	/**
	 * ダッシュボード画面表示
	 */
	public function action_index()
	{
		return Response::forge(View::forge('dashboard/index'));
	}
}
