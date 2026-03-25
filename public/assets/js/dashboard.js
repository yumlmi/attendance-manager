// public/assets/js/dashboard.js
// Knockout.js ViewModel for ダッシュボード

/**
 * ダッシュボード画面用のViewModel
 * knockout.jsのobservableでデータと画面をバインディングする
 */

function DashboardViewModel() {
    var self = this;

    // 日付（例: "03/24"）
    self.displayDate = ko.observable('mm/dd');
    // 月（例: "03"）
    self.displayMonth = ko.observable('mm');

    // 欠席人数表示用テキスト（例: "2/10人"）
    self.absentCountText = ko.observable('n/all人');
    // 出席率表示用テキスト（例: "80%"）
    self.attendanceRateText = ko.observable('x%');

    // 欠席者リスト（{name, reason}の配列）
    self.absentMembers = ko.observableArray([
        { name: '田中 太郎', reason: 'あああああ' },
        { name: '山田 花子', reason: 'いいいいい' }
    ]);

    /**
     * 欠席登録ボタン押下時の処理
     * 本番では登録画面への遷移やモーダル表示などに置き換える
     */
    self.registerAbsence = function() {
        alert('欠席登録画面へ遷移（仮）');
    };

    /**
     * ダッシュボード情報をAPIから取得し、observableにセットする
     */
    self.fetchDashboardData = function() {
        fetch('/api/dashboard')
            .then(function(response) { return response.json(); })
            .then(function(data) {
                // APIから取得したデータをobservableにセット
                self.displayDate(data.date);
                self.displayMonth(data.month);
                self.absentCountText(data.absent_count + '/' + data.all_count + '人');
                self.attendanceRateText(data.attendance_rate + '%');
                self.absentMembers(data.absent_members);
            })
            .catch(function(error) {
                console.error('API取得エラー:', error);
            });
    };

    // 初期化時にAPIからデータ取得
    self.fetchDashboardData();
}

/**
 * ページロード時にViewModelをバインドする
 * knockout.jsのapplyBindingsでHTMLとViewModelを結びつける
 */
window.addEventListener('DOMContentLoaded', function() {
    ko.applyBindings(new DashboardViewModel());
});
