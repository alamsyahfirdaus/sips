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
                <th>Jam Pelajaran</th>
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
      <form action="<?= site_url('master/hours/saveData') ?>" method="post" id="form">
        <input type="text" name="jam_pelajaran_id" value="" style="display: none;">
        <div class="modal-body">
          <div class="form-group">
            <label for="jam_pelajaran">Jam Pelajaran</label>
            <input type="text" class="form-control timepicker" id="jam_pelajaran" name="jam_pelajaran" placeholder="Jam Pelajaran" autocomplete="off" autofocus="">
            <small class="help-block"></small>
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
  var base_url  = index + "master/hours/";

  $(document).ready(function() {
    var url_table = "master/hours/showDataTables";
    set_datatable(url_table);
    form_validation();
  });

  function form_validation() {
    $('#form')
    .bootstrapValidator({
      excluded: ':disabled',
      fields: {
        jam_pelajaran: {
          validators: {
            // notEmpty: {
            //     message: 'Jam Pelajaran harus diisi'
            // },
            stringLength: {
                min: 4,
                max: 6,
                message: 'Panjang 4 sampai 6 karakter'
            }
          }
        },
      }
    })
    .on('success.form.bv', function(e) {
        e.preventDefault();
        form_data();
    });
  }

  function add_data() {
    $('#form')[0].reset();
    $('#form').data('bootstrapValidator').resetForm();
    $('.modal-title').text('Tambah ' + title);
    $('#modal-form').modal('show');
  }

  function edit_data(id) {
    var jam_pelajaran = $('[name="jam_pelajaran_'+ id +'"]').val();

    $('#form')[0].reset();
    $('#form').data('bootstrapValidator').resetForm();
    $('[name="jam_pelajaran_id"]').val(id);
    $('[name="jam_pelajaran"]').val(jam_pelajaran);
    $('.modal-title').text('Edit ' + title);
    $('#modal-form').modal('show');
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