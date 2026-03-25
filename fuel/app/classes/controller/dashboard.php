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
		// ひとまず静的HTML（Knockout.js + API連携）を表示する
		return Response::redirect('assets/dashboard.html');
	}
}
