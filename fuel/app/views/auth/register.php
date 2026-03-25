<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>欠席管理システム 新規登録</title>
    <style>
        body { background: #fff; }
        .register-container {
            width: 400px; margin: 100px auto; padding: 40px 30px;
            border: 1px solid #ccc; background: #fff; text-align: center;
        }
        .register-title { font-size: 2em; font-weight: bold; margin-bottom: 10px; }
        .register-desc { color: #555; margin-bottom: 30px; }
        .register-label { text-align: left; margin: 10px 0 5px 0; font-weight: bold; }
        .register-input {
            width: 100%; padding: 10px; margin-bottom: 15px; background: #eee; border: none;
            font-size: 1em;
        }
        .register-btn {
            width: 100%; padding: 12px; background: #000; color: #fff; border: none;
            font-size: 1em; font-weight: bold; cursor: pointer; margin-bottom: 20px;
        }
        .back-link { color: #000; text-decoration: underline; cursor: pointer; }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-title">欠席管理システム</div>
        <div class="register-desc">アカウントを作成します</div>
        <?php if (isset($error)): ?>
            <div style="color:red; margin-bottom:10px;\"><?= e($error) ?></div>
        <?php endif; ?>
        <?= Form::open(['action' => 'auth/register', 'method' => 'post']) ?>
            <div class="register-label">氏名</div>
            <?= Form::input('username', Input::post('username', '田中太郎'), ['class' => 'register-input', 'autocomplete' => 'username', 'placeholder' => '田中太郎']) ?>
            <div class="register-label">メールアドレス</div>
            <?= Form::input('email', Input::post('email', 'example@example.com'), ['class' => 'register-input', 'autocomplete' => 'email', 'placeholder' => 'example@example.com']) ?>
            <div class="register-label">パスワード</div>
            <?= Form::password('password', '', ['class' => 'register-input', 'autocomplete' => 'new-password', 'placeholder' => '......']) ?>
            <?= Form::submit('register', 'アカウントを作成', ['class' => 'register-btn']) ?>
        <?= Form::close() ?>
        <div style="margin-top:30px; color:#555;">
            <a href="<?= Uri::create('auth/login') ?>" class="back-link">&lt; ログインページに戻る</a>
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
