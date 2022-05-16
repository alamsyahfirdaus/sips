<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-folder-open"></i> <?= $folder ?></a></li>
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
            <!-- <a href="javascript:void(0)" onclick="add_data();" class="btn btn-box-tool" data-toggle="tooltip">
              <i class="fa fa-plus"></i></a> -->
            <a href="javascript:void(0)" onclick="save_data();" class="btn btn-box-tool" data-toggle="tooltip">
              <i class="fa fa-plus"></i></a>
          </div>
        </div>
        <div class="box-body table-responsive">
          <table id="table" class="table" style="width: 100%;">
            <thead>
              <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="text-align: center;">Tahun Pelajaran</th>
                <th style="width: 20%; text-align: center;">Semester</th>
                <th style="text-align: center;">Tanggal</th>
                <th style="width: 10%; text-align: center;">Aksi</th>
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
      <form action="<?= site_url('master/years/saveData') ?>" method="post" id="form">
        <input type="text" name="tahun_pelajaran_id" value="" style="display: none;">
        <div class="modal-body">
          <div id="response"></div>
          <div class="form-group">
            <label for="tahun_pelajaran">Tahun Pelajaran</label>
            <input type="text" class="form-control" id="tahun_pelajaran" name="tahun_pelajaran" placeholder="Contoh: <?= date('Y') ?>" autocomplete="off">
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
  var base_url  = index + "master/years/";

  $(document).ready(function() {
    var url_table = "master/years/showDataTables";
    set_datatable(url_table,);

    form_validation();

  });

  function add_data() {
    $('#form')[0].reset();
    $('#form').data('bootstrapValidator').resetForm();
    $('.modal-title').text('Tambah ' + title);
    $('#modal-form').modal('show');
  }

  function edit_data(id) {
    var tahun_pelajaran = $('[name="tahun_pelajaran_'+ id +'"]').val();

    $('#form')[0].reset();
    $('#form').data('bootstrapValidator').resetForm();
    $('[name="tahun_pelajaran_id"]').val(id);
    $('[name="tahun_pelajaran"]').val(tahun_pelajaran);
    $('.modal-title').text('Edit ' + title);
    $('#modal-form').modal('show');
  }

  function form_validation() {
    $('#form')
    .bootstrapValidator({
      excluded: ':disabled',
      fields: {
        tahun_pelajaran: {
          validators: {
            notEmpty: {
                message: 'Tahun harus diisi'
            },
            digits: {
              message: 'Tahun hanya boleh berisi angka'
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

  function change_semester(id) {
    $.ajax({
        url: base_url + "changeSemester/" + id,
        data: {
          semester: $('[name="semester_'+ id +'"]').val(),
        },
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

  function save_data() {
    $.ajax({
        url: base_url + "addData/<?= time() ?>",
        data: {
          tahun_pelajaran_id: '<?= md5(time()) ?>',
        },
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          if (response.status) {
            Pace.restart();
            table.ajax.reload();
            flashdata(response.message);
          } else {
             add_data();
          }
        }
    });
  }

  function change_tanggal(id) {
    $.ajax({
      url: base_url + 'changeTanggal/' + id,
      type: 'POST',
      dataType: 'json',
      data: {
        tanggal_mulai: $('[name="tanggal_mulai_'+ id +'"]').val(),
        tanggal_selesai: $('[name="tanggal_selesai_'+ id +'"]').val(),
      },
      success: function(response) {
        Pace.restart();
        table.ajax.reload();
        if (response.message) {
          var type = response.status ? 'success' : 'error';
          flashdata(response.message, type);
        }
      }
    });
  }

</script>