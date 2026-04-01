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
		   // FuelPHPのViewでダッシュボードを表示
		   $login_user = Session::get($this->session_user_key, []);
		   return Response::forge(View::forge('dashboard/index', [
			   'login_user' => $login_user
		   ]));
	}
}
