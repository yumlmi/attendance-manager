<?php
// fuel/app/classes/controller/attendanceentry.php
class Controller_AttendanceEntry extends Controller_Base
{
    protected $require_login = true;

    public function action_index()
    {
        $login_user = \Session::get($this->session_user_key, []);
        $error = '';

        if (\Input::method() === 'POST') {
            $type = trim((string) \Input::post('type', ''));
            $attendance_date = trim((string) \Input::post('attendance_date', ''));
            $reason = trim((string) \Input::post('reason', ''));
            $csrf_token = \Input::post('fuel_csrf_token', '');

            if (!\Security::check_token($csrf_token)) {
                $error = 'CSRFトークンが不正です';
            } elseif (!in_array($type, ['absence', 'late'], true)) {
                $error = '種別が不正です';
            } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $attendance_date)) {
                $error = '日付形式が不正です';
            } elseif ($reason === '') {
                $error = '理由を入力してください';
            } else {
                // 既存登録チェック
                $exists = \DB::select('id')
                    ->from('attendances')
                    ->where('user_id', '=', $login_user['id'])
                    ->where('attendance_date', '=', $attendance_date)
                    ->where('type', '=', $type)
                    ->execute()
                    ->current();
                if ($exists) {
                    $error = '同じ日付・種別で既に登録済みです';
                } else {
                    $now = time();
                    $result = \DB::insert('attendances')->set([
                        'user_id' => $login_user['id'],
                        'attendance_date' => $attendance_date,
                        'type' => $type,
                        'reason' => $reason,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])->execute();
                    if ($result) {
                        \Response::redirect('/dashboard');
                        return;
                    } else {
                        $error = '登録に失敗しました';
                    }
                }
            }
        }

        return \Response::forge(\View::forge('attendance_entry', [
            'login_user' => $login_user,
            'error' => $error,
        ]));
    }
}