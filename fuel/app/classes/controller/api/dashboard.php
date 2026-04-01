<?php
// fuel/app/classes/controller/api/dashboard.php

use Fuel\Core\Controller_Rest;
use Fuel\Core\Response;
use Fuel\Core\DB;
use Fuel\Core\Session;

class Controller_Api_Dashboard extends Controller_Rest
{
    protected $format = 'json';

    public function get_index()
    {
        // クエリで日付指定があればその日付、なければ今日
        $query_date = 
            isset($_GET['date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['date'])
            ? $_GET['date']
            : date('Y-m-d');
        $today = $query_date;
        $date = date('m/d', strtotime($today));
        $month = date('m', strtotime($today));

        // ログインユーザー取得
        $login_user = Session::get('login_user', []);
        $club_name = isset($login_user['club_name']) ? $login_user['club_name'] : null;

        $absent_members = [];
        $absent_count = 0;
        $all_count = 0;
        $attendance_rate = 0;

        if ($club_name) {
            // 部活メンバー全員数
            $all_count = DB::select('id')
                ->from('users')
                ->where('club_name', '=', $club_name)
                ->execute()
                ->count();

            // 指定日付の欠席・遅刻者（同じ部活のみ）
            $query = DB::select('a.id', 'u.username', 'a.reason', 'a.type')
                ->from(['attendances', 'a'])
                ->join(['users', 'u'], 'INNER')
                ->on('a.user_id', '=', 'u.id')
                ->where('a.attendance_date', '=', $today)
                ->where('u.club_name', '=', $club_name)
                ->where('a.type', 'in', ['absence', 'late']);

            $results = $query->execute()->as_array();
            foreach ($results as $row) {
                $absent_members[] = [
                    'id' => $row['id'],
                    'name' => $row['username'],
                    'reason' => $row['reason'],
                    'type' => $row['type'],
                ];
            }
            $absent_count = count($absent_members);
            // 出席率計算（全員数が0でない場合のみ）
            if ($all_count > 0) {
                $attendance_rate = round((($all_count - $absent_count) / $all_count) * 100);
            }
        }

        return $this->response([
            'date' => $date,
            'month' => $month,
            'absent_count' => $absent_count,
            'all_count' => $all_count,
            'attendance_rate' => $attendance_rate,
            'absent_members' => $absent_members,
        ]);
    }
}
