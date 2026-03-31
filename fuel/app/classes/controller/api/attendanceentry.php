
<?php
// fuel/app/classes/controller/api/attendanceentry.php
use Fuel\Core\Controller_Rest;
use Fuel\Core\Response;
use Fuel\Core\DB;
use Fuel\Core\Session;
use Fuel\Core\Security;

class Controller_Api_AttendanceEntry extends Controller_Rest
{
    protected $format = 'json';

    // POST /api/attendances/create
    public function post_create()
    {
        // CSRFチェック
        if (!Security::check_token()) {
            return $this->response(['error' => 'CSRFトークンが不正です'], 400);
        }

        $login_user = Session::get('login_user', []);
        if (empty($login_user['id'])) {
            return $this->response(['error' => 'ログインが必要です'], 401);
        }

        $type = trim((string) \Input::post('type', ''));
        $attendance_date = trim((string) \Input::post('attendance_date', ''));
        $reason = trim((string) \Input::post('reason', ''));

        // バリデーション
        if (!in_array($type, ['absence', 'late'], true)) {
            return $this->response(['error' => '種別が不正です'], 400);
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $attendance_date)) {
            return $this->response(['error' => '日付形式が不正です'], 400);
        }
        if ($reason === '') {
            return $this->response(['error' => '理由を入力してください'], 400);
        }

        // 既存登録チェック（同一ユーザー・同日・同種別）
        $exists = DB::select('id')
            ->from('attendances')
            ->where('user_id', '=', $login_user['id'])
            ->where('attendance_date', '=', $attendance_date)
            ->where('type', '=', $type)
            ->execute()
            ->current();
        if ($exists) {
            return $this->response(['error' => '同じ日付・種別で既に登録済みです'], 409);
        }

        // 登録
        $now = time();
        $result = DB::insert('attendances')->set([
            'user_id' => $login_user['id'],
            'attendance_date' => $attendance_date,
            'type' => $type,
            'reason' => $reason,
            'created_at' => $now,
            'updated_at' => $now,
        ])->execute();

        if ($result) {
            \Response::redirect('/dashboard');
            return; // 念のため
        } else {
            return $this->response(['error' => '登録に失敗しました'], 500);
        }
    }
}
