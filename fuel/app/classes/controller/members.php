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
				// PHP組み込み関数が使える環境ではハッシュ化して保存
				$hashed_password = function_exists('password_hash')
					? password_hash($password, PASSWORD_DEFAULT)
					: $password;

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
				catch (Exception $e)
				{
					// UNIQUE制約違反などを利用者向けメッセージに変換
					$data['error'] = 'メンバー作成に失敗しました。ユーザー名またはメールアドレスが重複していないか確認してください。';
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
					// パスワード入力がある場合のみ更新
					$update['password'] = function_exists('password_hash')
						? password_hash($password, PASSWORD_DEFAULT)
						: $password;
				}

				try
				{
					DB::update('users')
						->set($update)
						->where('id', '=', $id)
						->execute();

					Response::redirect('members');
				}
				catch (Exception $e)
				{
					// UNIQUE制約違反などを利用者向けメッセージに変換
					$data['error'] = 'メンバー更新に失敗しました。ユーザー名またはメールアドレスが重複していないか確認してください。';
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
			DB::delete('users')
				->where('id', '=', $id)
				->execute();
		}

		Response::redirect('members');
	}
}
