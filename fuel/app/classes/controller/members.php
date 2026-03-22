<?php

/**
 * メンバー管理（usersテーブル）のCRUDを担当するコントローラ
 */
class Controller_Members extends Controller_Base
{
	/**
	 * メンバー管理画面はログイン必須
	 */
	protected $require_login = true;

	/**
	 * メンバー一覧表示
	 */
	public function action_index()
	{
		// 表示用に users 一覧を取得
		$members = DB::select('id', 'username', 'grade', 'mail', 'created_at', 'updated_at')
			->from('users')
			->order_by('id', 'asc')
			->execute()
			->as_array();

		return Response::forge(View::forge('members/index', array(
			'members' => $members,
		)));
	}

	/**
	 * メンバー新規作成
	 */
	public function action_create()
	{
		// 初期表示/再表示用のフォームデータ
		$data = array(
			'error' => '',
			'mode' => 'create',
			'member_id' => null,
			'member' => array(
				'username' => '',
				'grade' => 1,
				'mail' => '',
			),
		);

		if (Input::method() === 'POST')
		{
			// 状態変更はCSRFトークン必須
			if ( ! Security::check_token())
			{
				$data['error'] = 'セッションが無効です。再度操作してください。';
				return Response::forge(View::forge('members/form', $data));
			}

			// 入力値を取得
			$username = trim((string) Input::post('username', ''));
			$password = (string) Input::post('password', '');
			$grade = (int) Input::post('grade', 1);
			$mail = trim((string) Input::post('mail', ''));

			$data['member'] = array(
				'username' => $username,
				'grade' => $grade,
				'mail' => $mail,
			);

			// 最小バリデーション
			$errors = array();
			empty($username) and $errors[] = 'ユーザー名は必須です。';
			empty($password) and $errors[] = 'パスワードは必須です。';
			empty($mail) and $errors[] = 'メールアドレスは必須です。';
			! in_array($grade, array(1, 2, 3), true) and $errors[] = '学年は1〜3を指定してください。';

			if (empty($errors))
			{
				$now = time();
				$hashed_password = $this->hash_password_for_storage($password, $errors);

				if (empty($errors))
				{
					try
					{
						DB::insert('users')->set(array(
							'username' => $username,
							'password' => $hashed_password,
							'grade' => $grade,
							'mail' => $mail,
							'created_at' => $now,
							'updated_at' => $now,
						))->execute();

						Response::redirect('members');
					}
					catch (PDOException $e)
					{
						// DBエラーをログに記録
						$this->log_error_with_context('DB error on member create', array(
							'error' => $e->getMessage(),
							'code' => $e->getCode(),
							'username' => $username,
						));

						// UNIQUE制約違反：SQLSTATE[23000]
						if ($e->getCode() == '23000')
						{
							$data['error'] = 'メンバー作成に失敗しました。ユーザー名またはメールアドレスが重複していないか確認してください。';
						}
						else
						{
							$data['error'] = 'メンバー作成に失敗しました。システム管理者に連絡してください。';
						}
					}
					catch (Exception $e)
					{
						// 予期しない例外をログに記録
						$this->log_error_with_context('Unexpected error on member create', array(
							'error' => $e->getMessage(),
							'class' => get_class($e),
						));

						$data['error'] = 'メンバー作成に失敗しました。システム管理者に連絡してください。';
					}
				}
				else
				{
					$data['error'] = implode(' ', $errors);
				}
			}
			else
			{
				$data['error'] = implode(' ', $errors);
			}
		}

		return Response::forge(View::forge('members/form', $data));
	}

	/**
	 * メンバー編集
	 */
	public function action_edit($id = null)
	{
		$id = (int) $id;

		// 対象ユーザーを取得
		$member = DB::select('id', 'username', 'grade', 'mail')
			->from('users')
			->where('id', '=', $id)
			->execute()
			->current();

		if (empty($member))
		{
			Response::redirect('members');
		}

		$data = array(
			'error' => '',
			'mode' => 'edit',
			'member_id' => $id,
			'member' => array(
				'username' => $member['username'],
				'grade' => (int) $member['grade'],
				'mail' => $member['mail'],
			),
		);

		if (Input::method() === 'POST')
		{
			// 状態変更はCSRFトークン必須
			if ( ! Security::check_token())
			{
				$data['error'] = 'セッションが無効です。再度操作してください。';
				return Response::forge(View::forge('members/form', $data));
			}

			// 入力値を取得
			$username = trim((string) Input::post('username', ''));
			$password = (string) Input::post('password', '');
			$grade = (int) Input::post('grade', 1);
			$mail = trim((string) Input::post('mail', ''));

			$data['member'] = array(
				'username' => $username,
				'grade' => $grade,
				'mail' => $mail,
			);

			$errors = array();
			empty($username) and $errors[] = 'ユーザー名は必須です。';
			empty($mail) and $errors[] = 'メールアドレスは必須です。';
			! in_array($grade, array(1, 2, 3), true) and $errors[] = '学年は1〜3を指定してください。';

			if (empty($errors))
			{
				$update = array(
					'username' => $username,
					'grade' => $grade,
					'mail' => $mail,
					'updated_at' => time(),
				);

				if ($password !== '')
				{
					$hashed_password = $this->hash_password_for_storage($password, $errors);
					if ($hashed_password !== null)
					{
						$update['password'] = $hashed_password;
					}
				}

				if (empty($errors))
				{
					try
					{
						DB::update('users')
							->set($update)
							->where('id', '=', $id)
							->execute();

						Response::redirect('members');
					}
					catch (PDOException $e)
					{
						// DBエラーをログに記録
						$this->log_error_with_context('DB error on member edit', array(
							'error' => $e->getMessage(),
							'code' => $e->getCode(),
							'member_id' => $id,
						));

						// UNIQUE制約違反：SQLSTATE[23000]
						if ($e->getCode() == '23000')
						{
							$data['error'] = 'メンバー更新に失敗しました。ユーザー名またはメールアドレスが重複していないか確認してください。';
						}
						else
						{
							$data['error'] = 'メンバー更新に失敗しました。システム管理者に連絡してください。';
						}
					}
					catch (Exception $e)
					{
						// 予期しない例外をログに記録
						$this->log_error_with_context('Unexpected error on member edit', array(
							'error' => $e->getMessage(),
							'class' => get_class($e),
							'member_id' => $id,
						));

						$data['error'] = 'メンバー更新に失敗しました。システム管理者に連絡してください。';
					}
				}
				else
				{
					$data['error'] = implode(' ', $errors);
				}
			}
			else
			{
				$data['error'] = implode(' ', $errors);
			}
		}

		return Response::forge(View::forge('members/form', $data));
	}

	/**
	 * メンバー削除
	 */
	public function action_delete($id = null)
	{
		// 削除はPOST + CSRF必須
		if (Input::method() !== 'POST' or ! Security::check_token())
		{
			Response::redirect('members');
		}

		$id = (int) $id;

		if ($id > 0)
		{
			try
			{
				DB::delete('users')
					->where('id', '=', $id)
					->execute();
			}
			catch (PDOException $e)
			{
				// DBエラーをログに記録
				$this->log_error_with_context('DB error on member delete', array(
					'error' => $e->getMessage(),
					'code' => $e->getCode(),
					'member_id' => $id,
				));
			}
			catch (Exception $e)
			{
				// 予期しない例外をログに記録
				$this->log_error_with_context('Unexpected error on member delete', array(
					'error' => $e->getMessage(),
					'class' => get_class($e),
					'member_id' => $id,
				));
			}
		}

		Response::redirect('members');
	}

	/**
	 * DB保存用パスワードを生成
	 *
	 * @param string $password
	 * @param array  $errors
	 * @return string|null
	 */
	protected function hash_password_for_storage($password, array &$errors)
	{
		if ( ! function_exists('password_hash'))
		{
			$this->log_error_with_context('password_hash is not available on this environment');
			$errors[] = 'パスワード暗号化機能が利用できません。システム管理者に連絡してください。';
			return null;
		}

		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		if ($hashed_password === false)
		{
			$this->log_error_with_context('password_hash failed when storing member password');
			$errors[] = 'パスワードのハッシュ化に失敗しました。別のパスワードをお試しください。';
			return null;
		}

		return $hashed_password;
	}

	/**
	 * FuelPHP 1.8 互換でコンテキスト付きエラーログを出力
	 *
	 * @param string $message
	 * @param array  $context
	 * @return void
	 */
	protected function log_error_with_context($message, array $context = array())
	{
		if (empty($context))
		{
			\Log::error($message);
			return;
		}

		$json_encode_options = 0;
		defined('JSON_UNESCAPED_UNICODE') and $json_encode_options |= JSON_UNESCAPED_UNICODE;
		defined('JSON_UNESCAPED_SLASHES') and $json_encode_options |= JSON_UNESCAPED_SLASHES;

		$encoded_context = json_encode($context, $json_encode_options);
		if ($encoded_context === false)
		{
			$encoded_context = '{"context_encode_error":true}';
		}

		\Log::error($message.' context='.$encoded_context);
	}
}
