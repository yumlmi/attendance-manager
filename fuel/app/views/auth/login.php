
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>欠席管理システム ログイン</title>
	<link rel="stylesheet" href="/assets/css/auth.css" />
</head>
<body>
	<div class="auth-container">
		<div class="auth-title">欠席管理システム</div>
		<div class="auth-desc">アカウント情報を入力してログインしてください</div>
		<?php if (isset($error)): ?>
			<div class="auth-error"><?= e($error) ?></div>
		<?php endif; ?>
		<?= Form::open(['action' => 'auth/login', 'method' => 'post']) ?>
		<?= Form::hidden(Config::get('security.csrf_token_key', 'fuel_csrf_token'), Security::fetch_token()) ?>
			<div class="auth-label">氏名</div>
			<?= Form::input('username', Input::post('username'), ['class' => 'auth-input', 'autocomplete' => 'username']) ?>
			<div class="auth-label">パスワード</div>
			<?= Form::password('password', '', ['class' => 'auth-input', 'autocomplete' => 'current-password']) ?>
			<div class="auth-label">所属部活</div>
			<select name="club_name" class="auth-input">
				<option value="">選択してください</option>
				<?php if (!empty($club_names)): ?>
					<?php foreach ($club_names as $club): ?>
						<option value="<?= e($club) ?>" <?= Input::post('club_name') === $club ? 'selected' : '' ?>><?= e($club) ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<?= Form::submit('login', 'ログイン', ['class' => 'auth-btn']) ?>
		<?= Form::close() ?>
		<div class="auth-footnote">
			アカウントをお持ちでない方は<br>
			<a href="<?= Uri::create('auth/register') ?>" class="auth-link">新規登録</a>
		</div>
	</div>
</body>
</html>
