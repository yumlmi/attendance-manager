<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>欠席管理システム 新規登録</title>
    <link rel="stylesheet" href="/assets/css/auth.css" />
</head>
<body>
    <div class="auth-container">
        <div class="auth-title">欠席管理システム</div>
        <div class="auth-desc">アカウントを作成します</div>
        <?php if (isset($error)): ?>
            <div class="auth-error"><?= e($error) ?></div>
        <?php endif; ?>
        <?= Form::open(['action' => 'auth/register', 'method' => 'post']) ?>
            <?= Form::hidden(Config::get('security.csrf_token_key', 'fuel_csrf_token'), Security::fetch_token()) ?>
            <div class="auth-label">氏名</div>
            <?= Form::input('username', Input::post('username', '田中太郎'), ['class' => 'auth-input', 'autocomplete' => 'username', 'placeholder' => '田中太郎']) ?>
            <div class="auth-label">メールアドレス</div>
            <?= Form::input('email', Input::post('email', 'example@example.com'), ['class' => 'auth-input', 'autocomplete' => 'email', 'placeholder' => 'example@example.com']) ?>
            <div class="auth-label">パスワード</div>
            <?= Form::password('password', '', ['class' => 'auth-input', 'autocomplete' => 'new-password', 'placeholder' => '......']) ?>

            <div class="auth-label">所属部活</div>
            <?php $clubs = Config::get('club_names', []); ?>
            <select name="club_name" class="auth-input">
                <option value="">選択してください</option>
                <?php foreach ($clubs as $club): ?>
                    <option value="<?= e($club) ?>" <?= Input::post('club_name') === $club ? 'selected' : '' ?>><?= e($club) ?></option>
                <?php endforeach; ?>
            </select>

            <?= Form::submit('register', 'アカウントを作成', ['class' => 'auth-btn']) ?>
        <?= Form::close() ?>
        <div class="auth-footnote">
            <a href="<?= Uri::create('auth/login') ?>" class="auth-link">&lt; ログインページに戻る</a>
        </div>
    </div>
<?php if (!empty($register_error_message)): ?>
<script>
    // サーバー側エラーをコンソールに出力
    console.error(<?= json_encode($register_error_message) ?>);
</script>
<?php endif; ?>
</body>
</html>
