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
          <div class="box-tools pull-right" id="btn-add" style="display: none;">
            <a href="javascript:void(0)" onclick="add_data();" class="btn btn-box-tool" data-toggle="tooltip">
              <i class="fa fa-plus"></i></a>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-6 col-xs-12">
              <div class="form-group">
                <label for="id_tahun_pelajaran">Filter Tahun Pelajaran</label>
                <select name="id_tahun_pelajaran" id="id_tahun_pelajaran" class="form-control select2" style="width: 100%">
                  <option value="">-- Pilih Tahun Pelajaran --</option>
                    <?php foreach ($this->mall->get_tapel() as $row): ?>
                      <option value="<?= md5($row->tahun_pelajaran_id) ?>" <?php if($row->is_aktif == 'Y') echo "selected"; ?>><?= $row->tahun_pelajaran ?><?php if($row->is_aktif == 'Y') echo " - Aktif"; ?></option>
                    <?php endforeach ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table id="table" class="table" style="width: 100%;">
              <thead>
                <tr>
                  <th style="text-align: center; width: 5%;">No</th>
                  <th style="width: 15%;">Hari</th>
                  <th>Guru Piket</th>
                  <th style="text-align: center; width: 10%;">Aksi</th>
                </tr>
              </thead>
            </table>
          </div>
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
      <form action="<?= site_url('master/picketteacher/addGuruPiket/'. md5(time())) ?>" method="post" id="form">
        <input type="text" name="id_guru_piket" value="" style="display: none;">
        <div class="modal-body">
          <div class="form-group">
            <label for="id_user">Guru Piket</label>
            <select name="id_user" id="id_user" class="form-control select2" style="width: 100%;">
              <option value="">-- Guru Piket --</option>
            </select>
            <small class="help-block" id="error-id_user"></small>
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
  $(function() {

    table = $('#table').DataTable({
        "processing": false,
        "serverSide": true,
        "ordering": false,
        "order": [],
        "info": false,
        "language": { 
          "infoFiltered": "",
          "sZeroRecords": "<b style='color: #777777;'>TIDAK DITEMUKAN</b>",
          "sSearch": "Cari:"
        },
        "ajax": {
          "url": "<?= site_url('master/picketteacher/showDataTables/'. md5(time())) ?>",
          "type": "POST",
          "data": function(data) {
            data.id_tahun_pelajaran = $('[name="id_tahun_pelajaran"]').val();
          },
        },
        "drawCallback": function(settings) {
          if (settings.json.addDays > 0) {
            $('#btn-add').hide();
          } else {
            $('#btn-add').show();
          }
        },
        "columnDefs": [{ 
          "targets": [-1],
          "orderable": false,
        }],
    });

    $('[name="id_tahun_pelajaran"]').change(function() {
      Pace.restart();
      table.ajax.reload();
    });

    $('#form')
    .bootstrapValidator({
      excluded: ':disabled',
      fields: {
        id_user: {
          validators: {
              notEmpty: {
                  message: 'Guru Piket harus diisi'
              },
          }
        },
      }
    })
    .on('success.form.bv', function(e) {
        e.preventDefault();
        form_data();
    });

  });

  function add_data() {
    $.ajax({
      url: '<?= site_url('master/picketteacher/add/'. md5(time())) ?>',
      type: 'POST',
      dataType: 'json',
      data: {id_tahun_pelajaran: $('[name="id_tahun_pelajaran"]').val()},
      success: function(response) {
        if (response.status) {
          Pace.restart();
          table.ajax.reload();
          flashdata(response.message);
        }
      }
    });
  }

  // function edit_data(id) {
  //   if ($('[name="id_guru_piket"]').val()) {
  //     $('[name="id_guru_piket"]').val('').change();
  //   } else {
  //     $('[name="id_guru_piket"]').val(id).change();
  //   }
  //   table.ajax.reload();
  // }

  function save_data(id) {
    var id_user = $('[name="id_user_'+ id +'"]').val();
    if (id_user) {
      $.ajax({
        url: '<?= site_url('master/picketteacher/update') ?>/' + id,
        type: 'POST',
        dataType: 'json',
        data: {id_user: id_user},
        success: function(response) {
          if (response.status) {
            Pace.restart();
            $('[name="id_guru_piket"]').val('').change();
            table.ajax.reload();
            flashdata(response.message);
          }
        }
      });
    } else {
      $('[name="id_user_'+ id +'"]').closest('.form-group').addClass('has-error');
      $('[name="id_user_'+ id +'"]').nextAll('.help-block').eq(0).text('Guru Piket harus diisi');
      $('[name="id_user_'+ id +'"]').change(function() {
        if ($(this).val()) {
          $('[name="id_user_'+ id +'"]').closest('.form-group').removeClass('has-error').addClass('has-success');
          $('[name="id_user_'+ id +'"]').nextAll('.help-block').eq(0).text('');
        } else {
          $('[name="id_user_'+ id +'"]').closest('.form-group').removeClass('has-success').addClass('has-error');
          $('[name="id_user_'+ id +'"]').nextAll('.help-block').eq(0).text('Guru Piket harus diisi');
        }
      });
    }
  }

  function add_guru_piket(id) {
    $('.modal-title').text('Tambah ' + title);
    $('#form')[0].reset();
    $('#form').data('bootstrapValidator').resetForm();
    $('#id_user').find('option').not(':first').remove();
    $.getJSON('<?= site_url('master/picketteacher/getGuruPiket') ?>/' + id, function(data) {
      var option = [];
      for (let i = 0; i < data.length; i++) {
          option.push({
              id: data[i].id_guru_piket,
              text: data[i].guru_piket
          });
      }
      $('#id_user').select2({data: option});
    });
    $('[name="id_guru_piket"]').val(id).change();
    $('#modal-form').modal('show');
  }

  function action_success() {
    table.ajax.reload();
    $('#modal-form').modal('hide');
  }

  function delete_data(id) {
    var text  = "Akan menghapus " + title;
    var url   = "<?= site_url('master/picketteacher/delete') ?>/" + id;
    confirm_delete(text, url);
  }

  function success_delete() {
    table.ajax.reload();
  }

</script>
