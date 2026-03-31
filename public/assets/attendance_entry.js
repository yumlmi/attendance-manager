// public/assets/attendance_entry.js
// 遅刻・欠席登録フォームのAPI連携

document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('.form-section');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var type = form.querySelector('.form-select').value;
        var attendance_date = form.querySelector('.form-input').value;
        var reason = form.querySelector('.form-textarea').value;
        var csrf_token = getCsrfToken();

        fetch('/api/attendanceentry/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                type: type,
                attendance_date: attendance_date,
                reason: reason,
                fuel_csrf_token: csrf_token
            })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                alert('登録が完了しました');
                window.location.href = '/dashboard';
            } else {
                alert(data.error || '登録に失敗しました');
            }
        })
        .catch(function() {
            alert('通信エラーが発生しました');
        });
    });

    // CSRFトークン取得（cookieから取得 or hidden fieldから取得）
    function getCsrfToken() {
        // hiddenフィールド優先
        var hidden = document.querySelector('input[name="fuel_csrf_token"]');
        if (hidden) return hidden.value;
        // cookieから取得
        var m = document.cookie.match(/fuel_csrf_token=([^;]+)/);
        return m ? m[1] : '';
    }
});
