<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-cogs"></i> <?= $folder ?></a></li>
    <li class="active"><?= $title ?></li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-8 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar <?= $sub_title ?></h3>
          <div class="box-tools pull-right"></div>
        </div>
        <div class="box-body table-responsive">
          <table id="table" class="table table-hover" style="width: 100%">
            <thead>
              <tr>
                <th width="5%" class="text-center">No</th>
                <th>Jenis Pengguna</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
  $(document).ready(function() {
    var url_table = "setting/menu/showRole";
    set_datatable(url_table);
  });
</script>