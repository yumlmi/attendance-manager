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
			Response::redirect('login');
		}
	}

	/**
	 * Cookie検証用のログインキーを生成
	 */
	protected function build_login_key(array $user)
	{
		// Config::get は未ロード設定を自動ロードしないため、ここで明示ロードする
		\Config::load('crypt', true);
		$secret = \Config::get('crypt.crypto_hmac');

		if (empty($secret))
		{
			throw new RuntimeException('Remember-me用の秘密鍵が未設定です。crypt.crypto_hmac を設定してください。');
		}

		$payload = $user['id'].'|'.$user['password'];

		return hash_hmac('sha256', $payload, $secret);
	}

	/**
	 * Cookieからログイン状態を復元
	 *
	 * @return array|null
	 */
	protected function restore_login_user_from_cookie()
	{
		// Cookieからユーザー識別情報を取得
		$encrypted_user_id = Cookie::get($this->cookie_user_id_key);
		$encrypted_login_key = Cookie::get($this->cookie_login_key);

		if (empty($encrypted_user_id) or empty($encrypted_login_key))
		{
			return null;
		}

		$user_id = $this->decode_remember_cookie_value($encrypted_user_id);
		$login_key = $this->decode_remember_cookie_value($encrypted_login_key);

		if (empty($user_id) or empty($login_key))
		{
			$this->clear_remember_cookies(true);
			return null;
		}

		$user = DB::select('id', 'username', 'password', 'grade', 'mail')
			->from('users')
			->where('id', '=', (int) $user_id)
			->execute()
			->current();

		// 不正なCookieの場合は削除して復元を中止
		if (empty($user) or ! hash_equals((string) $this->build_login_key($user), (string) $login_key))
		{
			$this->clear_remember_cookies(true);
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

		// Cookie復元で認証成立した場合もセッションIDを再生成して固定化を防止
		Session::rotate();

		return $login_user;
	}

	/**
	 * Remember-me Cookieに保存する値を暗号化
	 */
	protected function encode_remember_cookie_value($value)
	{
		return \Crypt::encode((string) $value);
	}

	/**
	 * Remember-me Cookieから取得した値を復号
	 *
	 * @return string|null
	 */
	protected function decode_remember_cookie_value($value)
	{
		$decoded = \Crypt::decode((string) $value);

		if ($decoded === false)
		{
			return null;
		}

		return (string) $decoded;
	}

	/**
	 * remember-me Cookieを削除
	 *
	 * secure=true/false の両方で削除して取りこぼしを防ぐ
	 */
	protected function clear_remember_cookies($http_only = true)
	{
		Cookie::delete($this->cookie_user_id_key, null, null, false, $http_only);
		Cookie::delete($this->cookie_login_key, null, null, false, $http_only);
		Cookie::delete($this->cookie_user_id_key, null, null, true, $http_only);
		Cookie::delete($this->cookie_login_key, null, null, true, $http_only);
	}

	/**
	 * HTTPSリクエストかどうかを判定
	 */
	protected function is_https_request()
	{
		if (Input::protocol() === 'https')
		{
			return true;
		}

		$forwarded_proto = strtolower((string) Input::server('HTTP_X_FORWARDED_PROTO', ''));
		if (strpos($forwarded_proto, 'https') !== false)
		{
			return true;
		}

		$https = strtolower((string) Input::server('HTTPS', ''));

		return ($https === 'on' or $https === '1');
	}

	/**
	 * remember-me Cookie に secure 属性を付与するか
	 */
	protected function is_secure_cookie_required()
	{
		return (bool) \Config::get('attendance.auth.remember_cookie_secure', true);
	}
}
