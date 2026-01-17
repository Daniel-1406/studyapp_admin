<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $name?></title>
  <link rel="icon" type="image/x-icon" href="<?php print base_url()?>images/<?php echo $logo ?>">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php print base_url()?>/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php print base_url()?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php print base_url()?>/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
  <!-- Pre loader -->
  <div id="preloader-overlay">
            <div class="preloader-spinner"></div>
        </div>
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b><?php echo $name?></b></a>
    </div>
    <div class="card-body">
        <b><p class="login-box-msg"><?php if(isset($val))print $val?></p></b>
              

      <?php echo form_open("welcome/login") ?>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="username" name="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
           
          </div>
          <!-- /.col -->
          <div class="col-4">
                    <?php echo form_submit("submit","Submit","class='btn btn-primary btn-block'") ;?>

          </div>
          <!-- /.col -->
        </div>
      <?php echo form_close() ;?>

      <p class="mb-1">
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->
<script>
		 window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader-overlay');
        if (preloader) {
            preloader.style.display = 'none';
        }
    });

		</script>
<!-- jQuery -->
<script src="<?php print base_url()?>/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php print base_url()?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php print base_url()?>/dist/js/adminlte.min.js"></script>
</body>
</html>
