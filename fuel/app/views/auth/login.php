
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>欠席管理システム ログイン</title>
	<style>
		body { background: #fff; }
		.login-container {
			width: 400px; margin: 100px auto; padding: 40px 30px;
			border: 1px solid #ccc; background: #fff; text-align: center;
		}
		.login-title { font-size: 2em; font-weight: bold; margin-bottom: 10px; }
		.login-desc { color: #555; margin-bottom: 30px; }
		.login-label { text-align: left; margin: 10px 0 5px 0; font-weight: bold; }
		.login-input {
			width: 100%; padding: 10px; margin-bottom: 15px; background: #eee; border: none;
			font-size: 1em;
		}
		.login-btn {
			width: 100%; padding: 12px; background: #000; color: #fff; border: none;
			font-size: 1em; font-weight: bold; cursor: pointer; margin-bottom: 20px;
		}
		.register-link { color: #000; text-decoration: underline; cursor: pointer; }
	</style>
</head>
<body>
	<div class="login-container">
		<div class="login-title">欠席管理システム</div>
		<div class="login-desc">アカウント情報を入力してログインしてください</div>
		<?php if (isset($error)): ?>
			<div style="color:red; margin-bottom:10px;\"><?= e($error) ?></div>
		<?php endif; ?>
		<?= Form::open(['action' => 'auth/login', 'method' => 'post']) ?>
			<div class="login-label">氏名</div>
			<?= Form::input('username', Input::post('username'), ['class' => 'login-input', 'autocomplete' => 'username']) ?>
			<div class="login-label">パスワード</div>
			<?= Form::password('password', '', ['class' => 'login-input', 'autocomplete' => 'current-password']) ?>
			<?= Form::submit('login', 'ログイン', ['class' => 'login-btn']) ?>
		<?= Form::close() ?>
		<div style="margin-top:30px; color:#555;">
			アカウントをお持ちでない方は<br>
			<a href="<?= Uri::create('auth/register') ?>" class="register-link">新規登録</a>
		</div>
	</div>
</body>
</html>
