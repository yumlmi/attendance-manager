<!doctype html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <title>ダッシュボード | 欠席管理</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css" />
    <script src="/assets/js/knockout.js"></script>
    <script>
      // FuelPHPのCSRFトークンをJSグローバル変数に渡す
      window.csrf_token = '<?php echo Security::fetch_token(); ?>';
      console.log("after knockout:", typeof ko);
    </script>
    <script src="/assets/js/dashboard.js"></script>
    <!-- CSSはassets/css/dashboard.cssに分離 -->
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
      <div class="dashboard-title">
        ダッシュボード
        <span class="club-name">
          所属部活：<?php echo e($login_user['club_name'] ?? '未設定'); ?>
        </span>
      </div>
      <div>部活動の欠席情報を確認できます</div>
      <div class="action-bar">
        <a href="/attendance_entry" class="action-link"><button type="button" class="action-button">
            遅刻・欠席登録をする
          </button></a>
      </div>
      <div class="dashboard-card absent-list">
        <div class="absent-list-header">
          <span>遅刻/欠席者一覧</span>
          <input type="date" class="dashboard-date-picker" data-bind="value: displayDate, event: { change: changeDate }" />
        </div>
        <table class="absent-list-table">
          <thead>
            <tr>
              <th>部員名</th>
              <th>遅刻/欠席理由</th>
            </tr>
          </thead>
          <tbody data-bind="foreach: absentMembers">
            <tr>
              <td data-bind="text: name"></td>
              <td>
                <span data-bind="visible: !editing(), text: reason"></span>
                <input
                  data-bind="visible: editing, value: reason"
                  class="reason-input"
                />
              </td>
              <td>
                <button
                  data-bind="click: $parent.editMember, visible: !editing()"
                >
                  編集
                </button>
                <button data-bind="click: $parent.saveMember, visible: editing">
                  保存
                </button>
                <button data-bind="click: $parent.deleteMember">削除</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </body>
</html>
