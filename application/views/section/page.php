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
<!-- ADD THE CLASS layout-boxed TO GET A BOXED LAYOUT -->
<body class="hold-transition skin-blue layout-boxed sidebar-mini" style="font-family: cambria;">
<div class="wrapper">

  <header class="main-header">
    <?php 
      $query = $this->mall->getSession();
      $green = '#00A65A';
      $white = '#FFFFFF';
    ?>
    <!-- Logo -->
    <a href="" class="logo" style="background-color: <?= $green ?>; color: <?= $white ?>; border-right: 1px solid <?= $green ?>; font-family: cambria;">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini" title="<?= WEBSITE . ' ' . TITLE ?>"><b><?= LOGO_SM ?></b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg" style="background-color:  <?= $green ?>;" title="<?= WEBSITE . ' ' . TITLE ?>"><b><?= LOGO_SM . ' ' . TITLE ?></b></span>

      <!-- <div style="text-align: left;">
        <img src="<?= base_url('assets/custom/img/logo.jpg') ?>" class="" alt="" style="width: 100%; height: 45px; max-width: 45px; margin-bottom: 4px; margin-left: -4px; border-radius: 50%;">
      </div> -->

    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" style="background-color: <?= $green ?>; border-left: 1px solid <?= $green ?>;">
      <!-- Sidebar toggle button-->
      <a href="javascript:void(0)" class="sidebar-toggle" data-toggle="push-menu" role="button" style="color: <?= $white ?>; border-right: 1px solid <?= $green ?>;" onmouseover="style='background-color:  <?= $green ?>; color: <?= $white ?>; border-right: 1px solid <?= $green ?>;'">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu" style="background-color: <?= $green ?>;">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu" style="background-color: <?= $green ?>;">
            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" style="color: <?= $white ?>; border-left: 1px solid <?= $green ?>;" onmouseover="style='background-color:  <?= $green ?>; color: <?= $white ?>; border-left: 1px solid <?= $green ?>;'">
              <!-- <img src="<?= base_url('assets/custom/img/blank.png') ?>" class="user-image" alt="User Image"> -->
              <i class="glyphicon glyphicon-user"></i>
              <!-- <span class="hidden-xs"><?= substr(ucwords($query->full_name), 0, 20) ?></span><i class="caret"></i> -->
              <span class="hidden-xs"></span><i class="caret"></i>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header" style="background-color:  <?= $green ?>; color: <?= $white ?>;">
                <?php if (isset($query->profile_pic)): ?>
                  <img src="<?= base_url(IMAGE . $this->include->image($query->profile_pic)) ?>" class="img-circle" alt="User Image" style="border-radius: 1px solid <?= $white ?>;">
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
                  <a href="<?= site_url('profile') ?>" class="btn btn-default btn-flat"><i class="fa fa-user"></i> Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?= site_url('logout') ?>" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Keluar</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li style="display: none;">
            <a href="javascript:void(0)" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel" style="height: 65px;">
        <div class="pull-left image">
          <?php if (isset($query->profile_pic)): ?>
            <img src="<?= base_url(IMAGE . $this->include->image($query->profile_pic)) ?>" class="img-circle" alt="User Image">
          <?php else: ?>
            <?php
            $full_name = explode(' ', $query->full_name);
            $foto_profile = isset($full_name[0]) ? substr(strtoupper($full_name[0]), 0, 1) : '';
            $foto_profile .= isset($full_name[1]) ? substr(strtoupper($full_name[1]), 0, 1) : '';
            ?>
            <span class="foto_profile" style="width: 45px; height: 45px; font-size: 18px;"><?= $foto_profile ?></span>
          <?php endif ?>
        </div>
        <div class="pull-left info">
          <p><?= substr(ucwords($query->full_name), 0, 20) ?></p>
          <a href="javascript:void(0)"><?= ucwords($query->type_name) ?></a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header" style="background-color: #222D32; "></li>
        <?php $border = 'style="border-left: 3px solid '. $green .';"'; ?>

        <?php if ($query->user_type_id == 1): ?>

          <?php $menu = $this->db->order_by('sort', 'asc')->get('menu')->result(); ?>
          <?php foreach ($menu as $m) : ?>
            <?php $active = $m->url_menu == $this->uri->segment(1) || @$m->menu == @$title ? 'active' : '' ?>
            <?php if ($m->url_menu): ?>
              <li class="<?= $active ?>">
                <a href="<?= site_url($m->url_menu) ?>" <?php if (@$active) echo $border; ?>>
                <i class="fa <?= $m->icon ?>"></i> <span><?= $m->menu ?></span>
                </a>
              </li>
            <?php else: ?>
              <?php 
              $sub_menu   = $this->mall->getSubMenu($query->user_type_id, $m->menu_id);
              $segment    = $this->uri->segment(1) . '/' . $this->uri->segment(2);

              if ($sub_menu['num_rows']): ?>
                <?php $treeview = $m->menu == @$folder ? 'active menu-open' : '' ?>
                <li class="treeview <?= $treeview ?>">
                  <a href="javascript:void(0)" <?php if (@$treeview) echo $border; ?>>
                    <i class="fa <?= $m->icon ?>"></i> <span><?= $m->menu ?></span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <?php foreach ($sub_menu['result'] as $sm): ?>
                      <li class="<?php if($sm->url == $segment || @$sm->sub_menu == @$title) echo 'active' ?>"><a href="<?= site_url($sm->route) ?>"><i class="fa fa-circle-o"></i> <?= $sm->sub_menu ?></a></li>
                    <?php endforeach ?>
                  </ul>
                </li>
              <?php endif ?>
            <?php endif ?>
          <?php endforeach ?>

        <?php elseif ($query->user_type_id == 2) : ?>
          <?php $home = @$folder == 'Beranda' || @$title == 'Beranda' ? 'active' : ''; ?>
          <li class="<?= $home ?>">
            <a href="<?= site_url('home') ?>" <?php if(@$home) echo $border; ?>>
              <i class="fa fa-home"></i> <span>Beranda</span>
            </a>
          </li>
          <?php $mengajar =  $this->uri->segment(1) == 'schedules'  || @$folder == 'Jadwal Mengajar' ? 'active' : ''; ?>
          <li class="<?= $mengajar ?>">
            <a href="<?= site_url('schedules') ?>" <?php if(@$mengajar) echo $border; ?>>
              <i class="fa fa-calendar"></i> <span>Jadwal Mengajar</span>
            </a>
          </li>

          <?php 
          $tapel  = $this->db->get_where('tahun_pelajaran', ['is_aktif' => 'Y'])->row();
          $wakel  = $this->db->get_where('wali_kelas', [
            'id_tahun_pelajaran'  => @$tapel->tahun_pelajaran_id,
            'id_user' => $query->user_id,
          ])->num_rows();

          ?>
          <?php if (@$wakel): ?>
            <?php $wakel1 = @$folder == 'Wali Kelas' ? 'active menu-open' : '' ?>
            <!-- <li class="treeview <?= $wakel ?>">
              <a href="javascript:void(0)" <?php if(@$wakel1) echo $border; ?>>
                <i class="fa fa-folder-open"></i> <span>Wali Kelas</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li class="<?php if(@$title == 'Kelas') echo 'active' ?>"><a href="<?= site_url('teacher/classes') ?>"><i class="fa fa-circle-o"></i> Kelas</a></li>
                <li class="<?php if(@$title == 'Jadwal Pelajaran') echo 'active' ?>"><a href="<?= site_url('teacher/schedule') ?>"><i class="fa fa-circle-o"></i> Jadwal Pelajaran</a></li>
              </ul>
            </li> -->

            <li class="<?php if(@$title == 'Kelas') echo 'active' ?>">
              <a href="<?= site_url('homeroomteacher') ?>" <?php if(@$wakel1) echo $border; ?>>
                <i class="fa fa-folder-open"></i> <span>Wali Kelas</span>
              </a>
            </li>

          <?php endif ?>

          <li class="">
            <a href="<?= site_url('logout') ?>">
              <i class="fa fa-sign-out"></i> <span>Keluar</span>
            </a>
          </li>

        <?php endif ?>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <?= $content; ?>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2021-<?= date('Y') ?> <?= COPYRIGHT ?>.</strong>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark" style="display: none;">
    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane" id="control-sidebar-home-tab"></div>
    </div>
    <!-- /.tab-pane -->
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<link rel="stylesheet" href="<?= base_url(CSS . 'style-page.css') ?>">
<!-- <script src="<?= base_url(JS . 'treeview-menu.js') ?>"></script> -->
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
