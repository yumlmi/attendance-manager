
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

    // POST /api/attendanceentry/update
    public function post_update()
    {
        try {
            // CSRFチェック
            if (!Security::check_token()) {
                return $this->response([
                    'error' => 'CSRFトークンが不正です',
                    'csrf_token' => Security::fetch_token(),
                ], 400);
            }

            $login_user = Session::get('login_user', []);
            if (empty($login_user['id'])) {
                return $this->response([
                    'error' => 'ログインが必要です',
                    'csrf_token' => Security::fetch_token(),
                ], 401);
            }

            $id = (int) \Input::post('id', 0);
            $reason = trim((string) \Input::post('reason', ''));

            if ($id <= 0) {
                return $this->response([
                    'error' => 'IDが不正です',
                    'csrf_token' => Security::fetch_token(),
                ], 400);
            }
            if ($reason === '') {
                return $this->response([
                    'error' => '理由を入力してください',
                    'csrf_token' => Security::fetch_token(),
                ], 400);
            }

            // attendancesテーブルから該当レコード取得（自分のデータのみ）
            $attendance = DB::select('*')
                ->from('attendances')
                ->where('id', '=', $id)
                ->where('user_id', '=', $login_user['id'])
                ->execute()
                ->current();
            if (!$attendance) {
                return $this->response([
                    'error' => '該当データがありません',
                    'csrf_token' => Security::fetch_token(),
                ], 404);
            }

            $now = time();
            $result = DB::update('attendances')
                ->set([
                    'reason' => $reason,
                    'updated_at' => $now,
                ])
                ->where('id', '=', $id)
                ->where('user_id', '=', $login_user['id'])
                ->execute();

            if ($result) {
                return $this->response([
                    'success' => true,
                    'csrf_token' => Security::fetch_token(),
                ]);
            } else {
                return $this->response([
                    'error' => '更新に失敗しました',
                    'csrf_token' => Security::fetch_token(),
                ], 500);
            }
        } catch (\Exception $e) {
            // 例外発生時はエラー内容をJSONで返す
            return $this->response([
                'error' => 'サーバーエラー: ' . $e->getMessage(),
                'csrf_token' => Security::fetch_token(),
            ], 500);
        }
    }

    // POST /api/attendances/create
    public function post_create()
    {
        try {
            // CSRFチェック
            if (!Security::check_token()) {
                return $this->response([
                    'error' => 'CSRFトークンが不正です',
                    'csrf_token' => Security::fetch_token(),
                ], 400);
            }

            $login_user = Session::get('login_user', []);
            if (empty($login_user['id'])) {
                return $this->response([
                    'error' => 'ログインが必要です',
                    'csrf_token' => Security::fetch_token(),
                ], 401);
            }

            $type = trim((string) \Input::post('type', ''));
            $attendance_date = trim((string) \Input::post('attendance_date', ''));
            $reason = trim((string) \Input::post('reason', ''));

            // バリデーション
            if (!in_array($type, ['absence', 'late'], true)) {
                return $this->response([
                    'error' => '種別が不正です',
                    'csrf_token' => Security::fetch_token(),
                ], 400);
            }
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $attendance_date)) {
                return $this->response([
                    'error' => '日付形式が不正です',
                    'csrf_token' => Security::fetch_token(),
                ], 400);
            }
            if ($reason === '') {
                return $this->response([
                    'error' => '理由を入力してください',
                    'csrf_token' => Security::fetch_token(),
                ], 400);
            }
            // ...existing code...
        } catch (\Exception $e) {
            return $this->response([
                'error' => 'サーバーエラー: ' . $e->getMessage(),
                'csrf_token' => Security::fetch_token(),
            ], 500);
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