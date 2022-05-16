<section class="content-header">
  <h1><?= $title ?>
    <?php if ($tahun_pelajaran) echo '<small>Tahun Pelajaran '. $tahun_pelajaran .'</small>'; ?>
  </h1>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar Presensi Siswa</h3>
          <div class="box-tools pull-right">
            <a href="<?= site_url('presences/'. md5(time())) ?>" class="btn btn-box-tool" data-toggle="tooltip"><i class="fa fa-user-plus"></i></a>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="id_kelas">Kelas</label>
                <select name="id_kelas" id="id_kelas" class="form-control select2" style="width: 100%;">
                  <option value="">-- Pilih Kelas --</option>
                  <?php foreach ($kelas as $row) {
                    echo '<option value="'. md5($row->kelas_id) .'">'. $row->nama_kelas .'</option>';
                  } ?>
                </select>
                <small class="help-block"></small>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="semester">Semester</label>
                <select name="semester" id="semester" class="form-control select2" style="width: 100%">
                  <option value="">-- Semester --</option>
                  <?php foreach (['1' => '1 (Ganjil)', '2' => '2 (Genap)'] as $key => $value): ?>
                    <option value="<?= $key ?>" <?php if($key == $semester) echo 'selected'; ?>><?= $value ?></option>
                  <?php endforeach ?>
                </select>
                <small class="help-block"></small>
              </div>
            </div>
            <div class="col-md-4">
              <label for="">Tanggal</label>
              <div class="input-group">
                <div class="input-group-btn">
                  <button type="button" class="btn btn-success pull-right" id="daterange-btn"><i class="fa fa-calendar"></i></button>
                </div>
                <input type="text" id="daterange" class="form-control" value="" disabled="" placeholder="-- Pilih Tanggal --">
                <div class="input-group-btn">
                  <button type="button" class="btn btn-default" id="btn-refresh"><i class="fa fa-refresh"></i></button>
                </div>
              </div>
              <div style="display: none;">
                <input type="text" id="tgl_awal" name="tgl_awal" value="">
                <input type="text" id="tgl_akhir" name="tgl_akhir" value="">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="table" class="table table-hover" style="width: 100%;">
                  <thead>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th>NIS</th>
                    <th>Nama<span style="color: #FFFFFF;">_</span>Lengkap</th>
                    <th>Jenis<span style="color: #FFFFFF;">_</span>Kelamin</th>
                    <?php foreach ($this->include->opsiPresensi() as $key => $value) {
                      if ($key != 4) {
                        echo '<th style="text-align: center;">'. $value .'</th>';
                      } else {
                        echo '<th style="text-align: center;">Tanpa<span style="color: #FFFFFF;">_</span>Keterangan</th>';
                      }
                    } ?>
                    <th style="width: 25%; text-align: center;">Persentase</th>
                  </thead>
                  <tfoot id="jumlah" style="display: none;">
                    <tr>
                      <th colspan="4" style="text-align: center;">Jumlah<span style="color: #FFFFFF;">_</span>Siswa</th>
                      <th colspan="2" style="text-align: center;">Laki-Laki</th>
                      <th colspan="2" style="text-align: center;">Perempuan</th>
                      <th></th>
                    </tr>
                    <tr>
                      <th colspan="4" style="text-align: center;" id="jml_siswa">0</th>
                      <th colspan="2" style="text-align: center;" id="laki_laki">0</th>
                      <th colspan="2" style="text-align: center;" id="perempuan">0</th>
                      <th></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Grafik <?= $title ?></h3>
        </div>
        <div class="box-body" id="chart-area" style="display: none;">
          <div class="chart" id="bar-area">
            <canvas id="barChart" style="max-height: 300px;"></canvas>
          </div>
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
          "url": "<?= site_url('home/showRekapPresensiKelas/'. md5($id_tahun_pelajaran)) ?>",
          "type": "POST",
          "data": function(data) {
            data.id_kelas   = $('[name="id_kelas"]').val();
            data.id_tapel   = "<?= $id_tahun_pelajaran ?>";
            data.semester   = $('[name="semester"]').val();
            data.tgl_awal   = $('[name="tgl_awal"]').val();
            data.tgl_akhir  = $('[name="tgl_akhir"]').val();
          },
        },
        "drawCallback": function(settings) {
          $('#sZeroRecords').text(settings.json.sZeroRecords);
          $('#jml_siswa').text(settings.json.jml_siswa);
          $('#laki_laki').text(settings.json.laki_laki);
          $('#perempuan').text(settings.json.perempuan);
          if (settings.json.recordsFiltered > 0) {
            $('#jumlah').show();
            $('#chart-area').slideDown('slow', function() {
              $(this).show();
            });
            bar_chart(settings.json.arr_siswa, settings.json.arr_hadir, settings.json.arr_color);
          } else {
            $('#jumlah').hide();
            $('#chart-area').slideUp('slow', function() {
              $(this).hide();
            });
          }
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

    $('[name="semester"]').change(function() {
      Pace.restart();
      table.ajax.reload();
    });

    $('[name="tgl_awal"]').change(function() {
      Pace.restart();
      table.ajax.reload();
    });

    $('[name="tgl_akhir"]').change(function() {
      Pace.restart();
      table.ajax.reload();
    });

    $('.applyBtn').click(function() {
      Pace.restart();
      table.ajax.reload();
    });

    $('#btn-refresh').click(function() {
      $('#daterange').val('').change();
      $('#tgl_awal').val('').change();
      $('#tgl_akhir').val('').change();
    });

  });

</script>