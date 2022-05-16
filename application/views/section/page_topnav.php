<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="<?= $this->mall->getLogo(); ?>">
  <title><?= LOGO_SM . ' ' . TITLE ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php $this->load->view('section/css'); ?>
</head>
<?php $this->load->view('section/js'); ?>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav" style="font-family: serif;">
<div class="wrapper">

  <header class="main-header">
    <?php $query = $this->mall->getSession(); ?>
    <nav class="navbar navbar-static-top" style="background-color: #00A65A;">
      <div class="container">
        <div class="navbar-header">
          <a href="<?= base_url() ?>" class="navbar-brand" title="<?= WEBSITE . ' ' . TITLE ?>" style="padding-left: 30px;"><b><?= LOGO_SM . ' ' . TITLE ?></b></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <!-- <li class="active"><a href="javascript:void(0)">Home <span class="sr-only">(current)</span></a></li> -->
            <li class="<?php if($this->uri->segment(1) == 'home') echo 'active' ?>"><a href="<?= site_url('home') ?>">Beranda</a></li>
            <?php if ($this->session->user_type_id == 3): ?>
              <li class="<?php if($this->uri->segment(1) == 'studentschedule') echo 'active' ?>"><a href="<?= site_url('studentschedule') ?>">Jadwal Pelajaran</a></li>
            <?php elseif ($this->session->user_type_id == 4): ?>
                <li class="<?php if($this->uri->segment(1) == 'attendances' || $this->uri->segment(1) == 'presences') echo 'active' ?>"><a href="<?= site_url('attendances') ?>">Presensi Siswa</a></li>
            <?php endif ?>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <!-- <img src="<?= base_url(IMAGE . $this->include->image($query->profile_pic)) ?>" class="user-image" alt="User Image"> -->
                <i class="glyphicon glyphicon-user"></i>
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs"><?= substr(ucwords($query->full_name), 0, 20) ?> <i class="caret"></i></span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header" style="background-color:  #00A65A; color: #FFFFFF;">
                  <?php if (isset($query->profile_pic)): ?>
                    <img src="<?= base_url(IMAGE . $this->include->image($query->profile_pic)) ?>" class="img-circle" alt="User Image" style="border-radius: 1px solid #FFFFFF;">
                    <?php else: ?>
                      <?php
                      $full_name = explode(' ', $query->full_name);
                      $foto_profile = isset($full_name[0]) ? substr(strtoupper($full_name[0]), 0, 1) : '';
                      $foto_profile .= isset($full_name[1]) ? substr(strtoupper($full_name[1]), 0, 1) : '';
                      ?>
                      <span class="foto_profile" style="width: 85px; height: 85px; font-size: 26px;"><?= $foto_profile ?></span>
                  <?php endif ?>
                  <p><?= substr(ucwords($query->full_name), 0, 20) ?>
                    <small>Login Terakhir: <?= $this->include->datetime($query->last_active) ?></small>
                  </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <?php if ($this->session->user_type_id == 4): ?>
                      <a href="<?= site_url('role') ?>" class="btn btn-default btn-flat"><i class="fa fa-cogs"></i> Pindah Role</a>
                    <?php else: ?>
                      <a href="<?= site_url('profile') ?>" class="btn btn-default btn-flat"><i class="fa fa-user"></i> Profile</a>
                    <?php endif ?>
                  </div>
                  <div class="pull-right">
                    <a href="<?= site_url('logout') ?>" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Keluar</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <?= $content; ?>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="container">
      <div class="pull-right hidden-xs">
        <b>Version</b> 1.0.0
      </div>
      <strong>Copyright &copy; 2021-<?= date('Y') ?> <?= COPYRIGHT ?>.</strong>
    </div>
    <!-- /.container -->
  </footer>
</div>
<!-- ./wrapper -->
<link rel="stylesheet" href="<?= base_url(CSS . 'style-page.css') ?>">
<script src="<?= base_url(JS . 'advanced.js') ?>"></script>
<script src="<?= base_url(JS . 'scripts.js') ?>"></script>
<script type="text/javascript">
  <?php if (@$this->session->flashdata('success')): ?>
    Swal.fire({
      type: 'success',
      title: '<span style="font-weight: bold; color: #595959; font-size: 16px; font-family: cambria;"><?= @$this->session->flashdata('success'); ?></span>',
      showConfirmButton: false,
      timer: 1500,
    });
  <?php elseif (@$this->session->flashdata('error')): ?>
    Swal.fire({
      type: 'error',
      title: '<span style="font-weight: bold; color: #595959; font-size: 16px; font-family: cambria;"><?= @$this->session->flashdata('error'); ?></span>',
      showConfirmButton: false,
      timer: 1500
    });
  <?php endif ?>
</script>
</body>
</html>
