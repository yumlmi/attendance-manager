<h1>メンバー一覧</h1>

<p>
	<a href="<?php echo Uri::create('members/create'); ?>">新規作成</a>
	| <a href="<?php echo Uri::create('dashboard'); ?>">ダッシュボードへ戻る</a>
</p>

<?php if (empty($members)): ?>
	<p>メンバーが登録されていません。</p>
<?php else: ?>
	<table border="1" cellpadding="8" cellspacing="0">
		<thead>
			<tr>
				<th>ID</th>
				<th>ユーザー名</th>
				<th>学年</th>
				<th>メール</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($members as $member): ?>
				<?php $member_id = (int) $member['id']; ?>
				<tr>
					<td><?php echo $member_id; ?></td>
					<td><?php echo e($member['username']); ?></td>
					<td><?php echo (int) $member['grade']; ?></td>
					<td><?php echo e($member['mail']); ?></td>
					<td>
						<a href="<?php echo Uri::create('members/edit/'.$member_id); ?>">編集</a>
						<?php echo Form::open('members/delete/'.$member_id, array('method' => 'post', 'style' => 'display:inline; margin-left:8px;')); ?>
							<?php echo Form::hidden(Config::get('security.csrf_token_key', 'fuel_csrf_token'), Security::fetch_token()); ?>
							<?php echo Form::submit('delete', '削除', array('onclick' => "return confirm('本当に削除しますか？');")); ?>
						<?php echo Form::close(); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
