<!doctype html>
<html lang="ja">
	<head>
		<meta charset="UTF-8" />
		<title>設定 | 欠席管理</title>
		<link rel="stylesheet" href="/assets/css/dashboard.css" />
		<style>
			body {
				font-family: "Segoe UI", "Hiragino Sans", "Meiryo", sans-serif;
				margin: 0;
				background: #f4f6fa;
			}
			.header {
				display: flex;
				align-items: center;
				border-bottom: 2px solid #222;
				padding: 24px 32px 12px 32px;
				background: #fff;
			}
			.dashboard-title {
				font-size: 2em;
				font-weight: bold;
				letter-spacing: 2px;
			}
			.nav {
				display: flex;
				align-items: center;
				gap: 32px;
				border-bottom: 1.5px solid #222;
				padding: 12px 32px;
				background: #fff;
				font-size: 1.1em;
			}
			.nav a {
				text-decoration: none;
				color: #222;
				font-weight: 500;
				padding: 4px 10px;
				border-radius: 4px;
				transition: background 0.2s;
			}
			.nav a:hover {
				background: #e0e0e0;
			}
			.nav span {
				font-weight: bold;
			}
			.nav button {
				margin-left: 16px;
				padding: 6px 18px;
				font-size: 1em;
				border-radius: 4px;
				border: 1px solid #222;
				background: #fff;
				cursor: pointer;
				transition: background 0.2s;
			}
			.nav button:hover {
				background: #222;
				color: #fff;
			}
			.main {
				padding: 32px;
				max-width: 900px;
				margin: 0 auto;
			}
			.settings-title {
				font-size: 2.2em;
				font-weight: bold;
				margin-bottom: 18px;
			}
			.settings-section {
				background: #fff;
				border: 1.5px solid #bbb;
				border-radius: 12px;
				padding: 32px 28px;
				margin-bottom: 32px;
				box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
				max-width: 600px;
			}
			.settings-label {
				font-size: 1.1em;
				font-weight: bold;
				margin-bottom: 8px;
			}
			.settings-input {
				width: 100%;
				padding: 14px 12px;
				font-size: 1em;
				border: none;
				background: #eee;
				border-radius: 4px;
				margin-bottom: 24px;
			}
			@media (max-width: 800px) {
				.main {
					padding: 12px;
				}
				.settings-section {
					padding: 18px 8px;
				}
			}
		</style>
	</head>
	<body>
		<div class="header">
			<span class="dashboard-title">欠席管理</span>
		</div>
		<div class="nav">
			<a href="/dashboard">ダッシュボード</a>
			<a href="/members">部員一覧</a>
			<a href="/settings">設定</a>
			<span style="margin-left: auto"><?php echo e($login_user['username'] ?? ''); ?></span>
			<?php echo Form::open(['action' => 'logout', 'method' => 'post', 'id' => 'logout-form', 'style' => 'display:inline; margin:0;']); ?>
				<?php echo Form::hidden(Config::get('security.csrf_token_key', 'fuel_csrf_token'), Security::fetch_token()); ?>
				<?php echo Form::submit('logout', 'ログアウト', array('style' => 'margin-left:16px;')); ?>
			<?php echo Form::close(); ?>
		</div>
		<div class="main">
			<div class="settings-title">設定</div>
			<?php if (Session::get_flash('success')): ?>
				<div style="background:#e8f7ee; border:1px solid #63b37f; color:#1f6b3b; padding:10px 12px; border-radius:8px; margin-bottom:14px; max-width:600px;">
					<?php echo e(Session::get_flash('success')); ?>
				</div>
			<?php endif; ?>
			<?php if (Session::get_flash('error')): ?>
				<div style="background:#fdeeee; border:1px solid #d27a7a; color:#8f2f2f; padding:10px 12px; border-radius:8px; margin-bottom:14px; max-width:600px;">
					<?php echo e(Session::get_flash('error')); ?>
				</div>
			<?php endif; ?>

			<?php echo Form::open(['action' => 'settings/update', 'method' => 'post', 'class' => 'settings-section']); ?>
				<div class="settings-label">部員情報</div>
				<div style="margin-bottom: 16px"></div>
				   <div class="settings-label" style="font-weight: normal">氏名</div>
				   <input type="text" name="username" class="settings-input" placeholder="氏名" value="<?php echo e($login_user['username'] ?? ''); ?>" required />
				   <div class="settings-label" style="font-weight: normal">学年</div>
				   <input type="number" min="1" max="3" name="grade" class="settings-input" placeholder="学年" value="<?php echo e($login_user['grade'] ?? ''); ?>" required />
				   <div class="settings-label" style="font-weight: normal">メールアドレス</div>
				   <input type="email" name="mail" class="settings-input" placeholder="メールアドレス" value="<?php echo e($login_user['mail'] ?? ''); ?>" required />
				   <?php echo Form::hidden('fuel_csrf_token', Security::fetch_token()); ?>
				   <button type="submit" class="settings-input" style="background:#222; color:#fff; font-weight:bold; cursor:pointer; margin-top:8px;">保存</button>
			<?php echo Form::close(); ?>
		</div>
	</body>
</html>
