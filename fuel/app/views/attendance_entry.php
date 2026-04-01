<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>遅刻・欠席登録 | 欠席管理</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css" />
    <link rel="stylesheet" href="/assets/css/attendance_entry.css" />
</head>
<body>
    <div class="header">
        <span class="dashboard-title">欠席管理</span>
    </div>
    <div class="nav">
        <a href="/dashboard">ダッシュボード</a>
        <a href="/members">部員一覧</a>
        <a href="/settings">設定</a>
        <span class="nav-user">
            <?php echo htmlspecialchars($login_user['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
        </span>
        <form action="/logout" method="post" id="logout-form" class="logout-form">
            <input type="hidden" name="fuel_csrf_token" value="<?php echo Security::fetch_token(); ?>">
            <input type="submit" value="ログアウト" class="logout-button">
        </form>
    </div>
    <div class="main attendance-main">
        <div class="attendance-card">
            <?php if (!empty($error)): ?>
                <div class="attendance-error">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
            <div class="attendance-title">遅刻・欠席登録</div>
            <div class="attendance-desc">遅刻または欠席情報を入力してください</div>
            <form method="post" action="">
                <input type="hidden" name="fuel_csrf_token" value="<?php echo Security::fetch_token(); ?>">
                <div class="attendance-field">
                    <label for="type" class="attendance-label">遅刻または欠席</label>
                    <select id="type" name="type" class="attendance-select" required>
                        <option value="">遅刻か欠席か選択してください</option>
                        <option value="late">遅刻</option>
                        <option value="absence">欠席</option>
                    </select>
                </div>
                <div class="attendance-field">
                    <label for="attendance_date" class="attendance-label">遅刻・欠席日</label>
                    <input type="date" id="attendance_date" name="attendance_date" class="attendance-date" required>
                </div>
                <div class="attendance-field-large">
                    <label for="reason" class="attendance-label">遅刻・欠席理由</label>
                    <textarea id="reason" name="reason" class="attendance-textarea" required placeholder="欠席理由を詳細に記入してください"></textarea>
                </div>
                <button type="submit" class="attendance-submit">遅刻・欠席登録</button>
            </form>
        </div>
    </div>
</body>
</html>
