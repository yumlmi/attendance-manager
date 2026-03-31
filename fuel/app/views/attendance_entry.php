<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>遅刻・欠席登録 | 欠席管理</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css" />
</head>
<body>
    <div class="header">
        <span class="dashboard-title">欠席管理</span>
    </div>
    <div class="nav">
        <a href="/dashboard">ダッシュボード</a>
        <a href="/members">部員一覧</a>
        <a href="/settings">設定</a>
        <span style="margin-left:auto;">
            <?php echo htmlspecialchars($login_user['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
        </span>
        <form action="/logout" method="post" id="logout-form" style="display:inline; margin:0;">
            <input type="hidden" name="fuel_csrf_token" value="<?php echo Security::fetch_token(); ?>">
            <input type="submit" value="ログアウト" style="margin-left:16px;">
        </form>
    </div>
    <div class="main">
                <?php if (!empty($error)): ?>
                    <div style="color: red; font-weight: bold; margin-bottom: 16px;">
                        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
        <div class="form-title">遅刻・欠席登録</div>
        <div class="form-desc">遅刻または欠席情報を入力してください</div>
        <form class="form-section" method="post" action="">
            <input type="hidden" name="fuel_csrf_token" value="<?php echo Security::fetch_token(); ?>">
            <div class="form-label">遅刻または欠席</div>
            <select class="form-select" name="type" required>
                <option value="">遅刻か欠席か選択してください</option>
                <option value="late">遅刻</option>
                <option value="absence">欠席</option>
            </select>
            <div class="form-label">遅刻・欠席日</div>
            <input type="date" class="form-input" name="attendance_date" required placeholder="日程を選択してください">
            <div class="form-label">遅刻・欠席理由</div>
            <textarea class="form-textarea" name="reason" required placeholder="欠席理由を詳細に記入してください"></textarea>
            <div class="action-bar">
                <button type="submit">遅刻・欠席登録</button>
            </div>
        </form>
    </div>
</body>
</html>
