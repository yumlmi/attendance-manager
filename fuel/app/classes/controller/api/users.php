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
        // usersテーブルから部員一覧を取得
        $users = DB::select('id', 'username', 'grade', 'mail')
            ->from('users')
            ->execute()
            ->as_array();

        return $this->response([
            'users' => $users
        ]);
    }
}
