<!doctype html>
<html lang="ja">
	<head>
		<meta charset="UTF-8" />
		<title>設定 | 欠席管理</title>
		<link rel="stylesheet" href="/assets/css/dashboard.css" />
		<link rel="stylesheet" href="/assets/css/settings.css" />
	</head>
	<body>
		<div class="header">
			<span class="dashboard-title">欠席管理</span>
		</div>
		<div class="nav">
			<a href="/dashboard">ダッシュボード</a>
			<a href="/members">部員一覧</a>
			<a href="/settings">設定</a>
			<span class="nav-user"><?php echo e($login_user['username'] ?? ''); ?></span>
			<?php echo Form::open(['action' => 'logout', 'method' => 'post', 'id' => 'logout-form', 'class' => 'logout-form']); ?>
				<?php echo Form::hidden(Config::get('security.csrf_token_key', 'fuel_csrf_token'), Security::fetch_token()); ?>
				<?php echo Form::submit('logout', 'ログアウト', array('class' => 'logout-button')); ?>
			<?php echo Form::close(); ?>
		</div>
		<div class="main">
			<div class="settings-title">設定</div>
			<?php if (Session::get_flash('success')): ?>
				<div class="settings-message settings-message-success">
					<?php echo e(Session::get_flash('success')); ?>
				</div>
			<?php endif; ?>
			<?php if (Session::get_flash('error')): ?>
				<div class="settings-message settings-message-error">
					<?php echo e(Session::get_flash('error')); ?>
				</div>
			<?php endif; ?>

			<?php echo Form::open(['action' => 'settings/update', 'method' => 'post', 'class' => 'settings-section']); ?>
				<div class="settings-label">部員情報</div>
				<div class="settings-spacer"></div>
				   <div class="settings-label settings-label-normal">氏名</div>
				   <input type="text" name="username" class="settings-input" placeholder="氏名" value="<?php echo e($login_user['username'] ?? ''); ?>" required />
				   <div class="settings-label settings-label-normal">学年</div>
				   <input type="number" min="1" max="3" name="grade" class="settings-input" placeholder="学年" value="<?php echo e($login_user['grade'] ?? ''); ?>" required />
				   <div class="settings-label settings-label-normal">メールアドレス</div>
				   <input type="email" name="mail" class="settings-input" placeholder="メールアドレス" value="<?php echo e($login_user['mail'] ?? ''); ?>" required />
				   <?php echo Form::hidden('fuel_csrf_token', Security::fetch_token()); ?>
				   <button type="submit" class="settings-input settings-save-button">保存</button>
			<?php echo Form::close(); ?>
		</div>
	</body>
</html>
