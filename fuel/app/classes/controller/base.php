<?php

/**
 * 全コントローラ共通の基底クラス
 */
class Controller_Base extends Controller
{
	/**
	 * true の場合、未ログインユーザーをログイン画面へリダイレクトする
	 */
	protected $require_login = false;

	/**
	 * セッションに保存するログインユーザー情報のキー
	 */
	protected $session_user_key = 'login_user';

	/**
	 * 各アクション実行前の共通処理
	 */
	public function before()
	{
		parent::before();

		// セッションからログインユーザー情報を取得し、全Viewで参照できるようにする
		$login_user = Session::get($this->session_user_key, null);
		View::set_global('login_user', $login_user);

		// ログイン必須画面で未ログインの場合はログイン画面へ遷移
		if ($this->require_login and empty($login_user))
		{
			Response::redirect('auth/login');
		}
	}
}
