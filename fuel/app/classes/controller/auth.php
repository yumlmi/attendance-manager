<?php

/**
 * ログイン/ログアウトを扱う認証コントローラ
 */
class Controller_Auth extends Controller_Base
{
	/**
	 * 認証系ルートの事前処理
	 *
	 * 設定で有効な場合は HTTPS を強制する
	 */
	public function before()
	{
		// force_https が有効でHTTPアクセスされた場合は HTTPS にリダイレクト
		if ((bool) \Config::get('attendance.auth.force_https', false) and ! $this->is_https_request())
		{
			$host = Input::server('HTTP_HOST', 'localhost');
			$request_uri = Input::server('REQUEST_URI', '/');
			Response::redirect('https://'.$host.$request_uri);
		}

		parent::before();
	}

	/**
	 * ログイン画面表示・ログイン処理
	 */
	public function action_login()
	{
		// 既にログイン済みの場合はダッシュボードへ
		if (Session::get($this->session_user_key))
		{
			Response::redirect('dashboard');
		}

		$data = array(
			'error' => '',
			'username' => '',
		);

		if (Input::method() === 'POST')
		{
			// 入力値を取得
			$username = trim((string) Input::post('username', ''));
			$password = (string) Input::post('password', '');
			$remember = Input::post('remember', '') === '1';

			$data['username'] = $username;

			$user = DB::select('id', 'username', 'password', 'grade', 'mail')
				->from('users')
				->where('username', '=', $username)
				->execute()
				->current();

			// 認証失敗時はエラーを表示
			if (empty($user) or ! $this->verify_password($password, $user['password']))
			{
				$data['error'] = 'ユーザー名またはパスワードが正しくありません。';
			}
			else
			{
				// remember-me Cookie属性
				// secure は設定で制御し、http_only は常に有効化
				$cookie_secure = $this->is_secure_cookie_required();
				$cookie_http_only = true;

				// View共有用の最小ユーザー情報をSessionへ保存
				$login_user = array(
					'id' => (int) $user['id'],
					'username' => $user['username'],
					'grade' => (int) $user['grade'],
					'mail' => $user['mail'],
				);

				Session::set($this->session_user_key, $login_user);

				// ログイン情報設定後にセッションIDを再生成して固定化を防止
				Session::rotate();

				// 「ログイン状態を保持する」がONならCookieに保存（14日）
				// OFFの場合は既存Cookieを削除
				// Cookie値は before() 内で整合性チェックされる
				if ($remember)
				{
					$expire = 60 * 60 * 24 * 14;
					Cookie::set($this->cookie_user_id_key, $this->encode_remember_cookie_value((string) $user['id']), $expire, null, null, $cookie_secure, $cookie_http_only);
					Cookie::set($this->cookie_login_key, $this->encode_remember_cookie_value($this->build_login_key($user)), $expire, null, null, $cookie_secure, $cookie_http_only);
				}
				else
				{
					Cookie::delete($this->cookie_user_id_key, null, null, $cookie_secure, $cookie_http_only);
					Cookie::delete($this->cookie_login_key, null, null, $cookie_secure, $cookie_http_only);
				}

				Response::redirect('dashboard');
			}
		}

		return Response::forge(View::forge('auth/login', $data));
	}

	/**
	 * ログアウト処理
	 */
	public function action_logout()
	{
		// ログイン時と同じCookie属性で削除する
		$cookie_secure = $this->is_secure_cookie_required();
		$cookie_http_only = true;

		// Sessionを全体破棄してログイン画面へ戻す
		Session::destroy();
		Cookie::delete($this->cookie_user_id_key, null, null, $cookie_secure, $cookie_http_only);
		Cookie::delete($this->cookie_login_key, null, null, $cookie_secure, $cookie_http_only);

		Response::redirect('login');
	}

	/**
	 * パスワード照合
	 *
	 * ハッシュ形式が使われている場合は password_verify() を優先し、
	 * 平文データが残っている場合は互換のため直接比較を行う
	 */
	protected function verify_password($input_password, $stored_password)
	{
		if (empty($stored_password))
		{
			return false;
		}

		$info = password_get_info($stored_password);
		if ( ! empty($info['algo']))
		{
			return password_verify($input_password, $stored_password);
		}

		return hash_equals((string) $stored_password, (string) $input_password);
	}

}
