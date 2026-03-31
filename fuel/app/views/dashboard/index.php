<h1>ダッシュボード</h1>

<p>ログイン中: <?php echo e($login_user['username']); ?></p>

<p>
	<?php echo Form::open('logout', array('method' => 'post', 'id' => 'logout-form')); ?>
		<?php echo Form::hidden(Config::get('security.csrf_token_key', 'fuel_csrf_token'), Security::fetch_token()); ?>
		<?php echo Form::submit('logout', 'ログアウト'); ?>
	<?php echo Form::close(); ?>
</p>

<!-- Knockout.js をCDNから読み込む -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.5.1/knockout-min.js"></script>
<!-- ログアウトボタンsubmit時のデバッグ用スクリプト -->
<script>
window.onload = function() {
  var logoutForm = document.getElementById('logout-form');
  if (logoutForm) {
    logoutForm.addEventListener('submit', function(e) {
      try {
        console.log('ログアウトボタンが押されました');
      } catch (err) {
        console.error('ログアウトsubmit時エラー:', err);
      }
    });
  }
};
</script>
<!-- ダッシュボード用JS -->
<script src="/assets/js/dashboard.js"></script>
