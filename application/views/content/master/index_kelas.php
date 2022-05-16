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
                <th>Kelas</th>
                <!-- <th>Tingkat Kelas</th> -->
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
      <form action="<?= site_url('master/classes/addData/' . md5(time())) ?>" method="post" id="form">
        <input type="text" name="kelas_id" value="" style="display: none;">
        <div class="modal-body">
          <div class="form-group">
            <label for="id_tingkat_kelas">Kelas</label>
            <select name="id_tingkat_kelas" id="id_tingkat_kelas" class="form-control select2">
              <option value="">-- Kelas --</option>
              <?php foreach ($this->db->get('tingkat_kelas')->result() as $key) {
                echo '<option value="'. md5($key->tingkat_kelas_id) .'">'. $key->tingkat_kelas .'</option>';
              } ?>
            </select>
            <small class="help-block"></small>
          </div>
          <!-- <div class="form-group">
            <label for="nama_kelas">Kelas</label>
            <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" placeholder="Kelas" autocomplete="off">
            <small class="help-block" id="error-nama_kelas"></small>
          </div> -->
          <!-- <div class="form-group" id="id_nama_kelas"></div> -->
        </div>
        <div class="modal-footer">
          <?= BTN_CLOSE . BTN_SUBMIT ?>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  var base_url  = index + "master/classes/";
  var url_table = "master/classes/showDataTables/<?= md5(time()) ?>";

  $(function() {
    set_datatable(url_table);
    form_validation();

    $('[name="id_tingkat_kelas"]').change(function() {
      var id_tingkat_kelas = $(this).val() ? $(this).val() : '<?= md5(time()) ?>'
      $.getJSON(base_url + 'sort_nama_kelas/' + id_tingkat_kelas, function(response) {
        if (response.status) {
          table.ajax.reload();
        }
      });
    });

  });

  function add_data() {
    $('#form')[0].reset();
    $('#form').data('bootstrapValidator').resetForm();
    $('.form-group').removeClass('has-error has-success');
    $('#error-nama_kelas').text('');
    $('.select2').select2(null, false);
    $('.modal-title').text('Tambah ' + title);
    $('#modal-form').modal('show');
    // load_nama_kelas(null, null);
  }

  // function edit_data(id) {
  //   var id_tingkat_kelas  = $('[name="id_tingkat_kelas_'+ id +'"]').val();
  //   var nama_kelas        = $('[name="nama_kelas_'+ id +'"]').val();

  //   $('#form')[0].reset();
  //   $('#form').data('bootstrapValidator').resetForm();
  //   $('#error-nama_kelas').text('');
  //   $('[name="kelas_id"]').val(id);
  //   $('[name="id_tingkat_kelas"]').val(id_tingkat_kelas).change();
  //   // $('[name="nama_kelas"]').val(nama_kelas);
  //   load_nama_kelas(id_tingkat_kelas, id);
  //   $('.form-group').removeClass('has-error has-success');
  //   $('.modal-title').text('Edit ' + title);
  //   $('#modal-form').modal('show');
  // }

  function form_validation() {
    $('#form')
    .bootstrapValidator({
      excluded: ':disabled',
      fields: {
        // nama_kelas: {
        //   validators: {
        //       notEmpty: {
        //           message: 'Kelas harus diisi'
        //       },
        //   }
        // },
        id_tingkat_kelas: {
          validators: {
              notEmpty: {
                  message: 'Kelas harus diisi'
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

  // function load_nama_kelas(id_tingkat_kelas = null, id_kelas = null) {
  //   $.ajax({
  //       url: base_url + "getKelas/" + id_tingkat_kelas + '/' + id_kelas,
  //       data: {
  //         id_tingkat_kelas: '<?= md5(time()) ?>',
  //         id_kelas: '<?= md5(time()) ?>',
  //       },
  //       type: "POST",
  //       dataType: "JSON",
  //       success: function(response) {
  //         $('#id_nama_kelas').html(response.kelas);
  //       }
  //   });

  // }
</script>

