<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
    <?php if (@$folder): ?>
      <li><a href="<?= site_url() ?>"><i class="fa fa-calendar"></i> <?= $folder ?></a></li>
      <li class="active"><?= $title ?></li>
    <?php else: ?>
      <li><a href="<?= site_url() ?>"><i class="fa fa-calendar"></i> <?= $title ?></a></li>
    <?php endif ?>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar <?= $title ?></h3>
          <div class="box-tools pull-right">
            <a href="javascript:void(0)" onclick="add_data();" class="btn btn-box-tool"><i class="fa fa-plus"></i></a>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-4 col-xs-12">
              <div class="form-group">
                <label for="tahun_pelajaran_id">Tahun Pelajaran</label>
                <select name="tahun_pelajaran_id" id="tahun_pelajaran_id" class="form-control select2" style="width: 100%">
                  <option value="">-- Tahun Pelajaran --</option>
                    <?php foreach ($tahun_pelajaran as $row): ?>
                      <option value="<?= $row->tahun_pelajaran_id ?>" <?php if($row->tahun_pelajaran_id == @$tahun_pelajaran_id) echo "selected"; ?>><?= $row->tahun_pelajaran ?><?php if($row->tahun_pelajaran_id == @$tahun_pelajaran_id) echo " - Aktif"; ?></option>
                    <?php endforeach ?>
                </select>
                <small class="help-block"></small>
              </div>
            </div>
            <div class="col-md-4 col-xs-12">
              <div class="form-group">
                <label for="kelas_id">Kelas</label>
                <select name="kelas_id" id="kelas_id" class="form-control select2" style="width: 100%">
                  <option value="">-- Kelas --</option>
                  <?php foreach ($kelas as $row) {
                    echo '<option value="'. $row->kelas_id .'">'. $row->nama_kelas .'</option>';
                  } ?>
                </select>
                <small class="help-block"></small>
              </div>
            </div>
            <div class="col-md-4 col-xs-12">
              <div class="form-group">
                <label for="hari">Hari</label>
                <select name="hari" id="hari" class="form-control select2" style="width: 100%">
                  <option value="">-- Hari --</option>
                  <?php foreach ($hari as $key => $value) {
                    echo '<option value="'. $key .'">'. $value .'</option>';
                  } ?>
                </select>
                <small class="help-block"></small>
              </div>
            </div>
          </div>
        </div>
        <div class="box-body table-responsive">
          <table id="table" class="table" style="width: 100%;">
            <thead>
              <tr>
                <th width="5%" class="text-center">No</th>
                <th class="text-center">Mata Pelajaran</th>
                <th class="text-center">Kelas</th>
                <th class="text-center">Guru</th>
                <th class="text-center">Hari</th>
                <th class="text-center">Jam Pelajaran</th>
                <th class="text-center" width="15%">Aksi</th>
                <!-- <th width="5%" class="text-center"><input type="checkbox" id="check-all"></th> -->
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
      <form action="<?= site_url('schedules/saveData') ?>" method="post" id="form">
        <div class="modal-body">
          <input type="hidden" id="id_tahun_pelajaran" name="id_tahun_pelajaran" value="">
          <div class="form-group">
            <label for="id_kelas">Kelas</label>
            <select class="form-control select2" style="width: 100%;" name="id_kelas" id="id_kelas">
              <option value="">-- Kelas --</option>
            </select>
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
  var base_url  = index + "schedules/";
  var itp       = $('[name="tahun_pelajaran_id"]');
  var kelas_id  = $('[name="kelas_id"]');
  var hari      = $('[name="hari"]');

  $(document).ready(function() {
    datetable();
    form_validation();
    checked();

    $('[name="id_tahun_pelajaran"]').change(function() {
      load_kelas(this.value);
    });

  });

  function datetable() {
    table = $('#table').DataTable({
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "order": [],
        "info" : false,
        "language": { 
          "infoFiltered": "",
          "sZeroRecords": "<b style='color: #777777;'>TIDAK DITEMUKAN</b>",
          "sSearch": "Cari:"
        },
        "ajax": {
          "url": base_url + "showDataTables",
          "type": "POST",
          "data": function(data) {
            data.itp = itp.val();
            data.id_kelas = kelas_id.val();
            data.hari = hari.val();
          },
        },
        "columnDefs": [{ 
          "targets": [-1],
          "orderable": false,
        }],
    });

    itp.change(function() {
      Pace.restart();
      table.ajax.reload();
      $('#tahun_pelajaran_id').closest('.form-group').removeClass('has-error');
      $('#tahun_pelajaran_id').nextAll('.help-block').eq(0).text('');
    });

    kelas_id.change(function() {
      Pace.restart();
      table.ajax.reload();
    });

    hari.change(function() {
      Pace.restart();
      table.ajax.reload();
    });
  }

  function add_data() {
    if (itp.val()) {
      $('#form')[0].reset();
      $('#form').data('bootstrapValidator').resetForm();
      $('#id_tahun_pelajaran').val(itp.val()).trigger('change');
      $('.modal-title').text('Tambah ' + title);
      $('#modal-form').modal('show');
    } else {
      $('#tahun_pelajaran_id').closest('.form-group').addClass('has-error');
      $('#tahun_pelajaran_id').nextAll('.help-block').eq(0).text('Tahun Pelajaran harus diisi');
    }
  }

  function form_validation() {
    $('#form')
    .bootstrapValidator({
      excluded: ':disabled',
      fields: {
        id_kelas: {
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

  function load_kelas(id_tahun_pelajaran) {

      $('#id_kelas').find('option').not(':first').remove();

      $.getJSON(base_url + "getKelas/" + id_tahun_pelajaran, function (data) {
          var option = [];
          for (let i = 0; i < data.length; i++) {
              option.push({
                  id: data[i].kelas_id,
                  text: data[i].nama_kelas
              });
          }
          $('#id_kelas').select2({
              data: option
          })
      });
  }

  function checked() {
    $("#check-all").click(function () {
      $(".data-check").prop('checked', $(this).prop('checked'));
    });
  }

  function unchecked() {
    $('#check-all').prop('checked', false);
    $('.data-check').prop('checked', false);
  }

  function change_guru(id) {
    id_user = $('[name="id_user_'+ id +'"]').val();
    $.ajax({
        url: base_url + "changeGuru",
        data: {
          jadwal_pelajaran_id: id,
          id_user: id_user,
        },
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          Pace.restart();
          table.ajax.reload();
          if (response.message) {
            var alert = 'error';
            flashdata(response.message, alert);
          }
        }
    });
  }

  function change_hari(id) {
    id_hari = $('[name="hari_'+ id +'"]').val();
    $.ajax({
        url: base_url + "changeHari",
        data: {
          jadwal_pelajaran_id: id,
          hari: id_hari,
        },
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          Pace.restart();
          table.ajax.reload();
          if (response.message) {
            var alert = 'error';
            flashdata(response.message, alert);
          }
        }
    });
  }

  function change_mulai(id) {
    mulai = $('[name="mulai_'+ id +'"]').val();
    $.ajax({
        url: base_url + "changeMulai",
        data: {
          jadwal_pelajaran_id: id,
          mulai: mulai,
        },
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          Pace.restart();
          table.ajax.reload();
          if (response.message) {
            var alert = 'error';
            flashdata(response.message, alert);
          }
        }
    });
  }

  function change_selesai(id) {
    selesai = $('[name="selesai_'+ id +'"]').val();
    $.ajax({
        url: base_url + "changeSelesai",
        data: {
          jadwal_pelajaran_id: id,
          selesai: selesai,
        },
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          Pace.restart();
          table.ajax.reload();
          if (response.message) {
            flashdata(response.message, response.alert);
          }
        }
    });
  }

  function delete_data(id) {
    var text  = "Akan menghapus " + title;
    var url   = base_url + "deleteData/" + id;
    confirm_delete(text, url);
  }

  function success_delete() {
    table.ajax.reload();
  }

  function edit_data(id) {
    $.ajax({
        url: base_url + "resetData/" + id,
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          Pace.restart();
          table.ajax.reload();
        }
    });
  }

  function list_delete() {
    var list_id = [];
    $(".data-check:checked").each(function() {
        list_id.push(this.value);
    });

    if(list_id.length > 0) {
      Swal.fire({
        title: '<span style="font-family: cambria;">Apakah anda yakin?</span>',
        text: 'Akan menghapus ' + title,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00A65A',
        cancelButtonColor: '#6C757D',
        confirmButtonText: '<span style="font-family: cambria;"><i class="fa fa-angle-double-right"></i> Ya</span>',
        cancelButtonText: '<span style="font-family: cambria;"><i class="fa fa-angle-double-left"></i> Tidak</span>',
        reverseButtons: true,
      }).then((result) => {
        if (result.value) {
          $.ajax({
              url : base_url + 'deleteData',
              type: "POST",
              data: {
                id: list_id
              },
              dataType: "JSON",
              success: function(response) {
                Pace.restart();
                table.ajax.reload();
                if (response.message) {
                  flashdata(response.message);
                  unchecked();
                }
              }
          });
        }
      })
    } else {
      var message = 'Tidak ada data yang dipilih';
      var type = 'error';
      flashdata(message, type);
    }
  }

  function sub_jadwal(jadwal_pelajaran_id) {
    $.getJSON(base_url + '/add_sub_jadwal/' + jadwal_pelajaran_id, function(response) {
      if (response.status) {
        Pace.restart();
        table.ajax.reload();
        flashdata(response.message);
      }
    });
  }

</script>