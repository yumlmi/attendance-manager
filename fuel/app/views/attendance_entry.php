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
    <div class="main" style="display: flex; justify-content: center; align-items: flex-start; min-height: 70vh;">
        <div style="background: #fff; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 40px 32px 32px 32px; max-width: 540px; width: 100%; margin-top: 32px;">
            <?php if (!empty($error)): ?>
                <div style="color: #d32f2f; font-weight: bold; margin-bottom: 20px; font-size: 1.1em;">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>
            <div style="font-size: 2em; font-weight: bold; margin-bottom: 8px;">遅刻・欠席登録</div>
            <div style="color: #444; margin-bottom: 28px; font-size: 1.05em;">遅刻または欠席情報を入力してください</div>
            <form method="post" action="">
                <input type="hidden" name="fuel_csrf_token" value="<?php echo Security::fetch_token(); ?>">
                <div style="margin-bottom: 22px;">
                    <label for="type" style="font-weight: 500; display: block; margin-bottom: 6px;">遅刻または欠席</label>
                    <select id="type" name="type" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; background: #f5f5f5; font-size: 1em;">
                        <option value="">遅刻か欠席か選択してください</option>
                        <option value="late">遅刻</option>
                        <option value="absence">欠席</option>
                    </select>
                </div>
                <div style="margin-bottom: 22px;">
                    <label for="attendance_date" style="font-weight: 500; display: block; margin-bottom: 6px;">遅刻・欠席日</label>
                    <input type="date" id="attendance_date" name="attendance_date" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; background: #f5f5f5; font-size: 1em;">
                </div>
                <div style="margin-bottom: 28px;">
                    <label for="reason" style="font-weight: 500; display: block; margin-bottom: 6px;">遅刻・欠席理由</label>
                    <textarea id="reason" name="reason" required placeholder="欠席理由を詳細に記入してください" style="width: 100%; min-height: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; background: #fafafa; font-size: 1em;"></textarea>
                </div>
                <button type="submit" style="width: 100%; background: #111; color: #fff; font-size: 1.1em; font-weight: bold; padding: 13px 0; border: none; border-radius: 4px; cursor: pointer; letter-spacing: 0.05em;">遅刻・欠席登録</button>
            </form>
        </div>
    </div>
</body>
</html>
