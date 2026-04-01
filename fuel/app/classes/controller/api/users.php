<?php
// fuel/app/classes/controller/api/users.php
use Fuel\Core\Controller_Rest;
use Fuel\Core\Response;
use Fuel\Core\DB;

class Controller_Api_Users extends Controller_Rest
{
    protected $format = 'json';

    public function get_index()
    {
        $login_user = \Session::get('login_user', []);
        if (empty($login_user['id'])) {
            return $this->response([
                'error' => 'ログインが必要です',
            ], 401);
        }

        $club_name = isset($login_user['club_name']) ? (string) $login_user['club_name'] : '';
        if ($club_name === '') {
            return $this->response([
                'users' => [],
            ]);
        }

        // usersテーブルから部員一覧を取得
        $users = DB::select('id', 'username', 'grade', 'mail')
            ->from('users')
            ->where('club_name', '=', $club_name)
            ->execute()
            ->as_array();

        return $this->response([
            'users' => $users
        ]);
    }
}
