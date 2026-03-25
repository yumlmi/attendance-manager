<?php
$is_edit = ($mode === 'edit');
$action = $is_edit ? 'members/edit/'.$member_id : 'members/create';
?>

<h1><?php echo $is_edit ? 'メンバー編集' : 'メンバー作成'; ?></h1>

<?php if ( ! empty($error)): ?>
	<p style="color: #c00;"><?php echo e($error); ?></p>
<?php endif; ?>

<?php echo Form::open(array('action' => $action, 'method' => 'post')); ?>
	<?php echo Form::hidden(Config::get('security.csrf_token_key', 'fuel_csrf_token'), Security::fetch_token()); ?>

	<p>
		<label for="username">ユーザー名</label><br>
		<?php echo Form::input('username', $member['username'], array('id' => 'username')); ?>
	</p>

	<p>
		<label for="password">パスワード<?php echo $is_edit ? '（変更時のみ入力）' : ''; ?></label><br>
		<?php echo Form::password('password', '', array('id' => 'password')); ?>
	</p>

	<p>
		<label for="grade">学年</label><br>
		<?php echo Form::select('grade', $member['grade'], array(1 => '1', 2 => '2', 3 => '3'), array('id' => 'grade')); ?>
	</p>

	<p>
		<label for="mail">メールアドレス</label><br>
		<?php echo Form::input('mail', $member['mail'], array('id' => 'mail')); ?>
	</p>

	<p>
		<?php echo Form::submit('save', $is_edit ? '更新' : '作成'); ?>
		<a href="<?php echo Uri::create('members'); ?>">一覧へ戻る</a>
	</p>
<?php echo Form::close(); ?>
