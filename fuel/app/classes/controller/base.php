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
	 * Cookieに保存するユーザーIDのキー
	 */
	protected $cookie_user_id_key = 'auth_user_id';

	/**
	 * Cookieに保存するログイン検証用キー
	 */
	protected $cookie_login_key = 'auth_login_key';

	/**
	 * 各アクション実行前の共通処理
	 */
	public function before()
	{
		parent::before();

		// セッションからログインユーザー情報を取得し、全Viewで参照できるようにする
		$login_user = Session::get($this->session_user_key, null);

		// セッションが無い場合はCookieから復元を試みる
		if (empty($login_user))
		{
			$login_user = $this->restore_login_user_from_cookie();
		}

		// FuelPHP 1.8 の View エスケープ処理で null を渡すと警告になるため配列で統一
		if (empty($login_user))
		{
			$login_user = array();
		}

		View::set_global('login_user', $login_user);

		// ログイン必須画面で未ログインの場合はログイン画面へ遷移
		if ($this->require_login and empty($login_user))
		{
			Response::redirect('auth/login');
		}
	}

	/**
	 * Cookie検証用のログインキーを生成
	 */
	protected function build_login_key(array $user)
	{
		return sha1($user['id'].'|'.$user['password']);
	}

	/**
	 * Cookieからログイン状態を復元
	 *
	 * @return array|null
	 */
	protected function restore_login_user_from_cookie()
	{
		// Cookieからユーザー識別情報を取得
		$user_id = Cookie::get($this->cookie_user_id_key);
		$login_key = Cookie::get($this->cookie_login_key);

		if (empty($user_id) or empty($login_key))
		{
			return null;
		}

		$user = DB::select('id', 'username', 'password', 'grade', 'mail')
			->from('users')
			->where('id', '=', (int) $user_id)
			->execute()
			->current();

		// 不正なCookieの場合は削除して復元を中止
		if (empty($user) or $this->build_login_key($user) !== $login_key)
		{
			Cookie::delete($this->cookie_user_id_key);
			Cookie::delete($this->cookie_login_key);
			return null;
		}

		$login_user = array(
			'id' => (int) $user['id'],
			'username' => $user['username'],
			'grade' => (int) $user['grade'],
			'mail' => $user['mail'],
		);

		// 正常なCookieであればSessionへ再設定
		Session::set($this->session_user_key, $login_user);

		return $login_user;
	}
}
