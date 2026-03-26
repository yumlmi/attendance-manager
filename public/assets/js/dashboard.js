// public/assets/js/dashboard.js
// Knockout.js ViewModel for ダッシュボード

/**
 * ダッシュボード画面用のViewModel
 * knockout.jsのobservableでデータと画面をバインディングする
 */


function AbsenceMember(data) {
    this.id = data.id || null;
    this.name = ko.observable(data.name);
    this.reason = ko.observable(data.reason);
    this.editing = ko.observable(false);
}

function DashboardViewModel() {
    var self = this;

    self.displayDate = ko.observable('mm/dd');
    self.displayMonth = ko.observable('mm');
    self.absentCountText = ko.observable('n/all人');
    self.attendanceRateText = ko.observable('x%');

    self.absentMembers = ko.observableArray([
        new AbsenceMember({ name: '田中 太郎', reason: 'あああああ' }),
        new AbsenceMember({ name: '山田 花子', reason: 'いいいいい' })
    ]);

    self.editMember = function(member) {
        // すべての行の編集状態を解除
        self.absentMembers().forEach(function(m) { m.editing(false); });
        member.editing(true);
    };
    self.saveMember = function(member) {
        // 本来はAPIにPOSTする
        // $.post('/api/attendance/update', { id: member.id, reason: member.reason() }, ...)
        alert('保存しました: ' + member.name() + ' / ' + member.reason());
        member.editing(false);
    };
    self.deleteMember = function(member) {
        if (confirm('削除しますか？')) {
            // 本来はAPIにPOSTする
            // $.post('/api/attendance/delete', { id: member.id }, ...)
            self.absentMembers.remove(member);
        }
    };

    self.registerAbsence = function() {
        alert('欠席登録画面へ遷移（仮）');
    };

    self.fetchDashboardData = function() {
        fetch('/api/dashboard')
            .then(function(response) { return response.json(); })
            .then(function(data) {
                self.displayDate(data.date);
                self.displayMonth(data.month);
                self.absentCountText(data.absent_count + '/' + data.all_count + '人');
                self.attendanceRateText(data.attendance_rate + '%');
                // APIから取得したデータをAbsenceMemberでラップ
                var mapped = data.absent_members.map(function(m) { return new AbsenceMember(m); });
                self.absentMembers(mapped);
            })
            .catch(function(error) {
                console.error('API取得エラー:', error);
            });
    };

    self.fetchDashboardData();
}

/**
 * ページロード時にViewModelをバインドする
 * knockout.jsのapplyBindingsでHTMLとViewModelを結びつける
 */
window.addEventListener('DOMContentLoaded', function() {
    ko.applyBindings(new DashboardViewModel());
});
