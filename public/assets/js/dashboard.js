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

    // 日付はYYYY-MM-DD形式で初期化
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    self.displayDate = ko.observable(`${yyyy}-${mm}-${dd}`);
        // 日付変更時の処理
        self.changeDate = function(_, e) {
            // 日付変更で再取得
            self.fetchDashboardData();
        };
    // 欠席人数・出席率関連は削除

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
            const tokenInput = document.querySelector('[name="fuel_csrf_token"]');
            const csrfToken = tokenInput ? tokenInput.value : '';

            fetch('/api/attendanceentry/update', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: new URLSearchParams({
                    fuel_csrf_token: csrfToken,
                    id: member.id,
                    reason: member.reason(),
                    type: member.type || 'absence'
                }).toString()
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    member.editing(false);
                } else {
                    alert(data.error || '保存に失敗しました');
                }
            })
            .catch(function(error) {
                alert('通信エラー: ' + error);
            });
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
        // 日付をクエリに付与
        const date = self.displayDate();
        fetch('/api/dashboard?date=' + encodeURIComponent(date))
            .then(function(response) { return response.json(); })
            .then(function(data) {
                // displayDateは上書きしない（ユーザー選択値を優先）
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
    // ログアウトボタンのクリック処理
    var logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            window.location.href = '/login';
        });
    }
});
