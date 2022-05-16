<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-folder-open"></i> <?= $folder ?></a></li>
    <li class="active"><?= $title ?></li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-8 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar <?= $title ?></h3>
          <div class="box-tools pull-right">
            <a href="javascript:void(0)" onclick="add_data();" class="btn btn-box-tool" data-toggle="tooltip">
              <i class="fa fa-plus"></i></a>
          </div>
        </div>
        <div class="box-body table-responsive">
          <table id="table" class="table table-hover" style="width: 100%">
            <thead>
              <tr>
                <th width="5%" class="text-center">No</th>
                <th>Kode</th>
                <th>Mata Pelajaran</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modal-form">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <form action="<?= site_url('master/subjects/saveData') ?>" method="post" id="form">
        <input type="text" name="mapel_id" value="" style="display: none;">
        <div class="modal-body">
          <div class="form-group">
            <label for="kode_mapel">Kode Mapel</label>
            <input type="text" class="form-control" id="kode_mapel" name="kode_mapel" placeholder="Kode Mapel" autocomplete="off">
            <small class="help-block" id="error-kode_mapel"></small>
          </div>
          <div class="form-group">
            <label for="nama_mapel">Mata Pelajaran</label>
            <input type="text" class="form-control" id="nama_mapel" name="nama_mapel" placeholder="Mata Pelajaran" autocomplete="off">
            <small class="help-block" id="error-nama_mapel"></small>
          </div>
        </div>
        <div class="modal-footer">
          <?= BTN_CLOSE . BTN_SUBMIT ?>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  var base_url  = index + "master/subjects/";

  $(document).ready(function() {
    var url_table = "master/subjects/showDataTables";
    set_datatable(url_table);

    form_validation();

  });

  function add_data() {
    $('#form')[0].reset();
    $('#form').data('bootstrapValidator').resetForm();
    $('.form-group').removeClass('has-error has-success');
    $('#error-kode_mapel').text('');
    $('#error-nama_mapel').text('');
    $('.modal-title').text('Tambah ' + title);
    $('#modal-form').modal('show');
  }

  function edit_data(id) {
    var kode_mapel  = $('[name="kode_mapel_'+ id +'"]').val();
    var nama_mapel  = $('[name="nama_mapel_'+ id +'"]').val();

    $('#form')[0].reset();
    $('#form').data('bootstrapValidator').resetForm();
    $('#error-kode_mapel').text('');
    $('#error-nama_mapel').text('');
    $('[name="mapel_id"]').val(id);
    $('[name="kode_mapel"]').val(kode_mapel);
    $('[name="nama_mapel"]').val(nama_mapel);
    $('.form-group').removeClass('has-error has-success');
    $('.modal-title').text('Edit ' + title);
    $('#modal-form').modal('show');

  }

  function form_validation() {
    $('#form')
    .bootstrapValidator({
      excluded: ':disabled',
      fields: {
        kode_mapel: {
          validators: {
              notEmpty: {
                  message: 'Kode Mapel harus diisi'
              },
          }
        },
        nama_mapel: {
          validators: {
              notEmpty: {
                  message: 'Mata Pelajaran harus diisi'
              },
          }
        },
      }
    })
    .on('success.form.bv', function(e) {
        e.preventDefault();
        form_data();
    });
  }

  function action_success() {
    table.ajax.reload();
    $('#modal-form').modal('hide');
  }

  function delete_data(id) {
    var text  = "Akan menghapus " + title;
    var url   = base_url + "deleteData/" + id;
    confirm_delete(text, url);
  }

  function success_delete() {
    table.ajax.reload();
  }
</script>