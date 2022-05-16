<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-cogs"></i> <?= $folder ?></a></li>
    <li class="active"><?= $title ?></li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar <?= $title ?></h3>
          <div class="box-tools pull-right">
            <a href="<?= site_url('setting/announcement/addedit') ?>" class="btn btn-box-tool" data-toggle="tooltip" title="Tambah">
              <i class="fa fa-plus"></i></a>
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table id="table" class="table" style="width: 100%">
              <thead>
                <tr>
                  <th width="5%" class="text-center">No</th>
                  <th width="20%">Judul</th>
                  <th class="text-center">Gambar</th>
                  <th>Jenis Pengguna / Target</th>
                  <th>Tanggl Terbit</th>
                  <th class="text-center" width="15%">Status</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
  var base_url  = index + "setting/announcement/";

  $(document).ready(function() {
    var url_table = "setting/announcement/showDataTables";
    set_datatable(url_table);

  });

  function delete_data(id) {
    var text  = "Akan menghapus " + title;
    var url   = base_url + "deleteData/" + id;
    confirm_delete(text, url);
  }

  function success_delete() {
    table.ajax.reload();
  }
  
  function change_status(id) {
    $.ajax({
        url: base_url + "changeStatus/" + id,
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          Pace.restart();
          table.ajax.reload();
          if (response.message) {
            flashdata(response.message);
          }
        }
    });
  }
</script>