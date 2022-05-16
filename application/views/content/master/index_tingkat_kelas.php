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
        </div>
        <div class="box-body table-responsive">
          <table id="table" class="table table-hover" style="width: 100%">
            <thead>
              <tr>
                <th width="5%" class="text-center">No</th>
                <th>Tingkat Kelas</th>
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
      <form action="<?= site_url('master/levels/saveData') ?>" method="post" id="form">
        <input type="text" name="tingkat_kelas_id" value="" style="display: none;">
        <div class="modal-body">
          <div class="form-group">
            <label for="tingkat_kelas">Tingkat  Kelas</label>
            <input type="text" class="form-control" id="tingkat_kelas" name="tingkat_kelas" placeholder="Kelas" autocomplete="off">
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
  var base_url  = index + "master/levels/";

  $(document).ready(function() {
    var url_table = "master/levels/showDataTables";
    set_datatable(url_table);

    form_validation();

  });

  function edit_data(id) {
    var tingkat_kelas = $('[name="tingkat_kelas_'+ id +'"]').val();

    $('#form')[0].reset();
    $('#form').data('bootstrapValidator').resetForm();
    $('[name="tingkat_kelas_id"]').val(id);
    $('[name="tingkat_kelas"]').val(tingkat_kelas);
    $('.modal-title').text('Edit ' + title);
    $('#modal-form').modal('show');
  }

  function form_validation() {
    $('#form')
    .bootstrapValidator({
      excluded: ':disabled',
      fields: {
        tingkat_kelas: {
          validators: {
              notEmpty: {
                  message: 'Tingkat Kelas harus diisi'
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
</script>