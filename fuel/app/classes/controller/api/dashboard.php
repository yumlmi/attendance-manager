<?php
// fuel/app/classes/controller/api/dashboard.php
use Fuel\Core\Controller_Rest;
use Fuel\Core\Response;

class Controller_Api_Dashboard extends Controller_Rest
{
    protected $format = 'json';

    public function get_index()
    {
        // 仮データ（本番はDBから取得）
        $date = date('m/d');
        $month = date('m');
        $absent_count = 2;
        $all_count = 10;
        $attendance_rate = 80; // %
        $absent_members = [
            ['name' => '田中 太郎', 'reason' => 'あああああ'],
            ['name' => '山田 花子', 'reason' => 'いいいいい'],
        ];

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
