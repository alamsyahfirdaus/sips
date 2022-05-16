<section class="content-header">
  <h1><?= $title ?>
    <?php if ($tahun_pelajaran) echo '<small>Tahun Pelajaran '. $tahun_pelajaran .'</small>'; ?>
  </h1>
  <?php if (isset($id_wali_kelas)): ?>
    <ol class="breadcrumb">
      <li><a href="<?= site_url('homeroomteacher') ?>"><i class="fa fa-folder-open"></i> <?= @$folder ?></a></li>
      <li class="active"><?= @$title ?></li>
    </ol>
  <?php endif ?>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar Presensi Siswa</h3>
          <div class="box-tools pull-right">
            <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-box-tool" data-toggle="tooltip"><i class="fa fa-times"></i></a>
          </div>
        </div>
        <div class="box-body">
          <?php if (isset($id_guru_piket) || isset($id_wali_kelas)): ?>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="id_kelas">Kelas</label>
                  <select name="id_kelas" id="id_kelas" class="form-control select2" style="width: 100%;">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($kelas as $row) {
                      $selected = $row->kelas_id == $id_kelas ? 'selected' : '';
                      echo '<option value="'. md5($row->kelas_id) .'" '. $selected .'>'. $row->nama_kelas .'</option>';
                    } ?>
                  </select>
                  <small class="help-block"></small>
                </div>
                <div class="form-group">
                  <label for="tanggal">Tanggal</label>
                  <select name="tanggal" id="tanggal" class="form-control select2" style="width: 100%;">
                    <option value="">-- Pilih Tanggal --</option>
                    <?php foreach ($tanggal as $key => $value) {
                      echo '<option value="'. $key .'">'. $value .'</option>';
                    } ?>
                  </select>
                  <small class="help-block"></small>
                </div>
                <div class="form-group">
                  <button type="button" id="btn-check" class="btn btn-success btn-sm" style="font-weight: bold; float: right;"><i class="fa fa-save"></i> Simpan</button>
                </div>
              </div>
              <div class="col-md-9">
                <div class="table-responsive">
                  <table id="table" class="table table-hover" style="width: 100%;">
                    <thead>
                      <th style="width: 5%; text-align: center;">No</th>
                      <th>NIS</th>
                      <th>Nama<span style="color: #FFFFFF;">_</span>Lengkap</th>
                      <th>Jenis<span style="color: #FFFFFF;">_</span>Kelamin</th>
                      <th style="width: 20%; text-align: center;">Status<span style="color: #FFFFFF;">_</span>Kehadiran</th>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          <?php else: ?>
            <div class="alert alert-danger alert-dismissible">
              <h4><i class="icon fa fa-warning"></i> Tidak Bisa Presensi Siswa!</h4>
              Anda bukan guru piket pada tahun pelajaran sekarang.
            </div>
          <?php endif ?>
        </div>
      </div>
    </div>
  </div>
</section>

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
          "sZeroRecords": "<b style='color: #777777;' id='sZeroRecords'></b>",
          "sSearch": "Cari:"
        },
        "ajax": {
          "url": "<?= site_url('home/showPresensiKelas/'. md5($id_tahun_pelajaran)) ?>",
          "type": "POST",
          "data": function(data) {
            data.id_kelas = $('[name="id_kelas"]').val();
            data.tanggal = $('[name="tanggal"]').val();
            data.semester = "<?= $semester ?>";
          },
        },
        "drawCallback": function(settings) {
          $('#sZeroRecords').text(settings.json.sZeroRecords);
        },
        "columnDefs": [{ 
          "targets": [-1],
          "orderable": false,
        }],
    });

    $('[name="id_kelas"]').change(function() {
      Pace.restart();
      table.ajax.reload();
    });

    $('[name="tanggal"]').change(function() {
      Pace.restart();
      table.ajax.reload();
    });

    $('#btn-check').click(function() {
      var errors = [];
      var fields = {"id_kelas": "Kelas", "tanggal": "Tanggal"};
      $.each(fields, function(index, val) {
        if (!$('[name="'+ index +'"]').val()) {
          $('[name="'+ index +'"]').closest('.form-group').addClass('has-error');
          $('[name="'+ index +'"]').nextAll('.help-block').eq(0).text(''+ val +' harus dipilih');
          $('[name="'+ index +'"]').change(function() {
            $('[name="'+ index +'"]').closest('.form-group').removeClass('has-error');
            $('[name="'+ index +'"]').nextAll('.help-block').eq(0).text('');
          });
          errors.push(index);
        }
      });
      if (errors.length < 1) {
        $.ajax({
          url: '<?= site_url('presences') ?>/' + $('[name="id_kelas"]').val(),
          type: 'POST',
          dataType: 'json',
          data: {
            tanggal: $('[name="tanggal"]').val(),
            id_tahun_pelajaran: "<?= $id_tahun_pelajaran ?>",
            semester: "<?= $semester ?>",
          },
          success: function(response) {
            if (response.status) {
              Pace.restart();
              table.ajax.reload();
              Swal.fire({
                title: '<span style="font-family: serif;">Berhasil Melakukan Presensi Siswa</span>',
                text: 'Silahkan Pilih Siswa, Jika Akan Mengubah Status Kehadiran',
                type: 'success',
                showConfirmButton: false,
                timer: 3500
              });
            } else {
              $('[name="tanggal"]').closest('.form-group').addClass('has-error');
              $('[name="tanggal"]').nextAll('.help-block').eq(0).text('Tanggal sudah diinput');
              $('[name="tanggal"]').change(function() {
                $('[name="tanggal"]').closest('.form-group').removeClass('has-error');
                $('[name="tanggal"]').nextAll('.help-block').eq(0).text('');
              });
            }
          }
        });
        
      }
    });

  });

  function change_status(id) {
    $.ajax({
      url: '<?= site_url('home/changeStatuskehadiran/'. md5(time())) ?>',
      type: 'POST',
      dataType: 'json',
      data: {
        tanggal: $('[name="tanggal"]').val(),
        id_user: id,
        id_tapel: "<?= $id_tahun_pelajaran ?>",
        semester: "<?= $semester ?>",
        status: $('[name="status_' + id + '"]').val(),
      },
      success: function(response) {
        if (response.status) {
          Pace.restart();
          table.ajax.reload();
          var message = 'Berhasil Mengubah Status Kehadiran';
          flashdata(message);
        }
      }
    });
    
  }

</script>