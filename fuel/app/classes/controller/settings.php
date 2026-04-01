<?php
/**
 * 設定画面コントローラ
 */
class Controller_Settings extends Controller_Base
{
    public function action_index()
    {
        return Response::forge(View::forge('settings/index'));
    }

    public function action_update()
    {
        if (Input::method() !== 'POST') {
            return Response::redirect('settings');
        }

        // CSRFチェック
        if (!\Security::check_token()) {
            Session::set_flash('error', '不正なリクエストです。');
            return Response::redirect('settings');
        }

        $username = trim((string) Input::post('username', ''));
        $grade = (int) Input::post('grade', 0);
        $mail = trim((string) Input::post('mail', ''));

        $errors = array();
        empty($username) and $errors[] = '氏名は必須です。';
        empty($mail) and $errors[] = 'メールアドレスは必須です。';
        strlen($username) > 50 and $errors[] = '氏名は50文字以内で入力してください。';
        strlen($mail) > 255 and $errors[] = 'メールアドレスは255文字以内で入力してください。';
        ! filter_var($mail, FILTER_VALIDATE_EMAIL) and $errors[] = 'メールアドレスの形式が正しくありません。';
        ! in_array($grade, array(1, 2, 3), true) and $errors[] = '学年は1〜3を指定してください。';

        if (!empty($errors)) {
            Session::set_flash('error', $errors[0]);
            return Response::redirect('settings');
        }

        $login_user = Session::get($this->session_user_key, array());
        if (empty($login_user['id'])) {
            Session::set_flash('error', 'ユーザー情報が取得できません。');
            return Response::redirect('settings');
        }
        $user_id = (int) $login_user['id'];

        // ユーザー情報を更新
        $affected = DB::update('users')
            ->set([
                'username' => $username,
                'grade' => $grade,
                'mail' => $mail,
                'updated_at' => time(),
            ])
            ->where('id', $user_id)
            ->execute();

        if ($affected !== false) {
            if (!empty($login_user)) {
                $login_user['username'] = $username;
                $login_user['grade'] = $grade;
                $login_user['mail'] = $mail;
                Session::set($this->session_user_key, $login_user);
            }
            Session::set_flash('success', 'ユーザー情報を更新しました。');
        } else {
            Session::set_flash('error', 'ユーザー情報の更新に失敗しました。');
        }
        return Response::redirect('settings');
    }
}
