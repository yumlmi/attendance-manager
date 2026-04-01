<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo $title; ?></title>
  <?php echo Asset::css('bootstrap.css'); ?>
  <?php echo Asset::css('welcome.css'); ?>
</head>
<body>
  <div class="welcome-top">
    <?php echo Form::open('logout', array('method' => 'post', 'class' => 'welcome-logout-form')); ?>
      <?php echo Form::hidden(Config::get('security.csrf_token_key', 'fuel_csrf_token'), Security::fetch_token()); ?>
      <?php echo Form::submit('logout', 'ログアウト'); ?>
    <?php echo Form::close(); ?>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1><?php echo $title; ?> <small>We can't find that!</small></h1>
        <hr>
        <p>The controller generating this page is found at <code>APPPATH/classes/controller/welcome.php</code>.</p>
        <p>This view is located at <code>APPPATH/views/welcome/404.php</code>.</p>
      </div>
    </div>
    <footer>
      <p class="pull-right">Page rendered in {exec_time}s using {mem_usage}mb of memory.</p>
      <p>
        <a href="http://fuelphp.com">FuelPHP</a> is released under the MIT license.<br>
        <small>Version: <?php echo Fuel::VERSION; ?></small>
      </p>
    </footer>
  </div>
</body>
</html>
