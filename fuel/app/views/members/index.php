
<!doctype html>
<html lang="ja">
	<head>
		<meta charset="UTF-8" />
		<title>部員一覧 | 欠席管理</title>
		<link rel="stylesheet" href="/assets/css/dashboard.css" />
		<link rel="stylesheet" href="/assets/css/members.css" />
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
				<?php echo Form::submit('logout', 'ログアウト', array('style' => 'margin-left:16px;')); ?>
			<?php echo Form::close(); ?>
		</div>
		<div class="main">
			<div class="members-title">
				部員一覧
				<span style="font-size: 0.9em; color: #666; margin-left: 16px;">
					所属部活：<?php echo e($login_user['club_name'] ?? '未設定'); ?>
				</span>
			</div>
			<div class="members-desc">
				登録されている全部員の情報と欠席状況を確認できます
			</div>
			<table class="members-table">
				<thead>
					<tr>
						<th>部員名</th>
						<th>学年</th>
						<th>欠席回数</th>
						<th>メールアドレス</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($members as $member): ?>
					<tr>
						<td><?php echo e($member['username']); ?></td>
						<td><?php echo e($member['grade']); ?></td>
						<td>-</td>
						<td><?php echo e($member['mail']); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</body>
</html>
