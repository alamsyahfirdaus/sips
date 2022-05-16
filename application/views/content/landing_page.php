<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= TITLE ?></title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?= base_url('assets/custom/adminlte3/') ?>/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/custom/adminlte3/') ?>/dist/css/adminlte.min.css">
</head>
<body class="hold-transition layout-top-nav" style="font-family: cambria;">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand-md navbar-dark navbar-light" style="background-color: #00A65A;">
    <div class="container">
      <a href="javascript:void(0)" class="navbar-brand">
        <img src="<?= base_url('assets/custom/img/logo.jpg') ?>" alt="AdminLTE Logo" class="brand-image img-rounded" style="width: 30px;">
        <span class="brand-text" style="font-weight: bold;"><?= TITLE ?></span>
      </a>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse"></div>

      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">

        <li class="nav-item dropdown">
          <a id="dropdownSubMenu1" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle" style="font-weight: bold; color: #FFFFFF;">Login</a>
          <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu dropdown-menu-right">
            <?php foreach ($user_type as $row) {
              if ($row->user_type_id > 1) {
                echo '<li class="dropdown-divider"></li>';
              }
              echo '<li><a href="javascript:void(0)" onclick="sign_in(' . "'" . md5($row->user_type_id) . "'" . ')" class="dropdown-item" style="background-color: #FFFFFF; color: #333333;"><i class="fas fa-user mr-2"></i>'. $row->type_name .'</a></li>';
            } ?>  
          </ul>
        </li>

      </ul>
    </div>
  </nav>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container">
        <form action="<?= site_url('login') ?>" method="post" id="form" style="display: none;">
          <input type="text" name="user_type_id" value="">
        </form>
        <script type="text/javascript">
          function sign_in(id) {
            $('[name="user_type_id"]').val(id);
            $('#form').submit();
          }
        </script>
      </div>
    </div>

    <div class="content">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div class="card">
              <div class="card-body">
                <div id="<?= strtolower(date('l')) ?>" class="carousel slide" data-ride="carousel">
                  <ol class="carousel-indicators">
                    <?php 
                      $query  = $this->db->order_by('sort', 'desc')->where('is_aktif', 'Y')->get('image_slider');
                      for ($i=0; $i < $query->num_rows(); $i++) { 
                        $active = $i < 1 ? 'class="active"' : '';
                        echo '<li data-target="#'. strtolower(date('l')) .'" data-slide-to="'. $i .'" '. $active .'></li>';
                      }
                    ?>
                  </ol>
                  <div class="carousel-inner">
                    <?php 
                      $no = 0;
                      foreach ($query->result() as $row) {
                      $active = $no < 1 ? 'active' : '';
                      $image = '<div class="carousel-item '. $active .'">';
                      $image .= '<img class="d-block w-100 image" src="'. base_url(IMAGE . $this->include->image($row->gambar)) .'">';
                      $image .= '</div>';
                      echo $image;
                      $no++;
                    } ?>
                  </div>
                  <a class="carousel-control-prev" href="#<?= strtolower(date('l')) ?>" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="carousel-control-next" href="#<?= strtolower(date('l')) ?>" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title" style="font-weight: bold;">Visi Sekolah</h5>
              </div>
              <div class="card-body">
                <div class="card-text" style="text-align: justify;">
                  <?php 
                    $query = $this->db->get_where('pengaturan', ['id_pengaturan' => 1])->row();
                    echo $query->pengaturan;
                  ?>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <h5 class="card-title" style="font-weight: bold;">Misi Sekolah</h5>
              </div>
              <div class="card-body">
                <div class="card-text" style="text-align: justify;">
                  <?php 
                    $query = $this->db->get_where('pengaturan', ['id_pengaturan' => 2])->row();
                    echo $query->pengaturan;
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <style type="text/css">
      .image {
        height: 450px;
        display: block;
        margin-left: auto;
        margin-right: auto;
      }
      .card {
        border-top: 3px solid #00A65A;
      }
    </style>
  </div>

  <footer class="main-footer">
    <strong style="font-size: 14px;">Copyright &copy; 2021-<?= date('Y') ?> <a href="javascript:void(0)" style="color: #869099;"><?= COPYRIGHT ?></a>.</strong>
  </footer>
</div>

<script src="<?= base_url('assets/custom/adminlte3/') ?>/plugins/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets/custom/adminlte3/') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/custom/adminlte3/') ?>/dist/js/adminlte.min.js"></script>
<script src="<?= base_url('assets/custom/adminlte3/') ?>/dist/js/demo.js"></script>
</body>
</html>
