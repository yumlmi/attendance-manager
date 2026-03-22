<h1>ログイン</h1>

<?php if ( ! empty($error)): ?>
	<p style="color: #c00;"><?php echo e($error); ?></p>
<?php endif; ?>

<?php echo Form::open('login'); ?>
	<?php echo Form::hidden(Config::get('security.csrf_token_key', 'fuel_csrf_token'), Security::fetch_token()); ?>
	<p>
		<label for="username">ユーザー名</label><br>
		<?php echo Form::input('username', $username, array('id' => 'username')); ?>
	</p>
	<p>
		<label for="password">パスワード</label><br>
		<?php echo Form::password('password', '', array('id' => 'password')); ?>
	</p>
	<p>
		<label>
			<?php echo Form::checkbox('remember', '1', false); ?> ログイン状態を保持する
		</label>
	</p>
	<p>
		<?php echo Form::submit('submit', 'ログイン'); ?>
	</p>
<?php echo Form::close(); ?>
