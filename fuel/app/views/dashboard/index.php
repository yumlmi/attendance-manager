<h1>ダッシュボード</h1>

<p>ログイン中: <?php echo e($login_user['username']); ?></p>

<p>
	<?php echo Form::open('logout', array('method' => 'post')); ?>
		<?php echo Form::hidden(Config::get('security.csrf_token_key', 'fuel_csrf_token'), Security::fetch_token()); ?>
		<?php echo Form::submit('logout', 'ログアウト'); ?>
	<?php echo Form::close(); ?>
</p>
