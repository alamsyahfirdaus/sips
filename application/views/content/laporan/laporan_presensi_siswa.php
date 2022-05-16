<section class="content-header">
  <h1><?= $folder .' '. $title ?></h1>
  <ol class="breadcrumb">
      <li><a href=""><i class="fa fa-bar-chart"></i> <?= $folder .' '. $title ?></a></li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar <?= $title ?></h3>
          <!-- <div class="box-tools pull-right">
            <a href="javascript:void(0)" onclick="report_data();" class="btn btn-box-tool" data-toggle="tooltip"><i class="fa fa-print"></i></a>
          </div> -->
        </div>
        <div class="box-body">
          <form action="" method="post" id="form" target="_blank">
            <div class="row">
              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                  <label for="id_tahun_pelajaran">Filter Tahun Pelajaran</label>
                  <select name="id_tahun_pelajaran" id="id_tahun_pelajaran" class="form-control select2" style="width: 100%">
                    <option value="">-- Tahun Pelajaran --</option>
                      <?php foreach ($tahun_pelajaran as $row): ?>
                        <option value="<?= md5($row->tahun_pelajaran_id) ?>" <?php if($row->tahun_pelajaran_id == @$tahun_pelajaran_id) echo "selected"; ?>><?= $row->tahun_pelajaran ?><?php if($row->tahun_pelajaran_id == @$tahun_pelajaran_id) echo "- Aktif"; ?></option>
                      <?php endforeach ?>
                  </select>
                  <small class="help-block"></small>
                </div>
              </div>
              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                  <label for="id_semester">Filter Semester</label>
                  <select name="id_semester" id="id_semester" class="form-control select2" style="width: 100%">
                    <option value="">-- Semester --</option>
                    <?php foreach ($semester as $key => $value): ?>
                      <option value="<?= $key ?>" <?php if($key == @$id_semester) echo 'selected'; ?>><?= $value ?></option>
                    <?php endforeach ?>
                  </select>
                  <small class="help-block"></small>
                </div>
              </div>
              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                  <label for="id_kelas">Filter Kelas</label>
                  <select name="id_kelas" id="id_kelas" class="form-control select2" style="width: 100%">
                    <option value="">-- Kelas --</option>
                    <?php foreach ($kelas as $row): ?>
                      <option value="<?= md5($row->kelas_id) ?>"><?= $row->nama_kelas ?></option>
                    <?php endforeach ?>
                  </select>
                  <small class="help-block"></small>
                </div>
              </div>
            </div>
            <!-- <div class="row">
              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                  <label for="tanggal">Filter Tanggal</label>
                  <select name="tanggal" id="tanggal" class="form-control select2" style="width: 100%">
                    <option value="">-- Tanggal --</option>
                  </select>
                  <small class="help-block"></small>
                </div>
              </div>
              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                  <label for="bulan">Filter Bulan</label>
                  <select name="bulan" id="bulan" class="form-control select2" style="width: 100%">
                    <option value="">-- Bulan --</option>
                  </select>
                  <small class="help-block"></small>
                </div>
              </div>
            </div> -->
            <div class="row">
              <div class="col-md-4 col-xs-12">
                <label for="">Filter Tanggal</label>
                <div class="input-group">
                  <div class="input-group-btn">
                    <button type="button" class="btn btn-success pull-right" id="daterange-btn"><i class="fa fa-calendar"></i></button>
                  </div>
                  <input type="text" id="daterange" class="form-control" value="" disabled="" placeholder="-- Pilih Tanggal --">
                  <div class="input-group-btn">
                    <button type="button" class="btn btn-default" id="btn-refresh"><i class="fa fa-refresh"></i></button>
                  </div>
                </div>
                <input type="text" id="tgl_awal" name="tgl_awal" value="" style="display: none;">
                <input type="text" id="tgl_akhir" name="tgl_akhir" value="" style="display: none;">
              </div>
              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                  <label for="id_jadwal_pelajaran">Filter Mata Pelajaran</label>
                  <select name="id_jadwal_pelajaran" id="id_jadwal_pelajaran" class="form-control select2" style="width: 100%">
                    <option value="">-- Mata Pelajaran --</option>
                  </select>
                  <small class="help-block"></small>
                </div>
              </div>
              <div class="col-md-4 col-xs-12">
                <div class="form-group">
                  <label for="id_user">Filter Siwa</label>
                  <select name="id_user" id="id_user" class="form-control select2" style="width: 100%">
                    <option value="">-- Siswa --</option>
                  </select>
                  <small class="help-block"></small>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="box-body table-responsive">
          <table id="table" class="table table-hover" style="width: 100%">
            <thead>
              <tr>
                <th width="5%" class="text-center">No</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <?php foreach ($this->include->opsiPresensi() as $key => $value) {
                  echo '<th class="text-center">'. $value .'</th>';
                } ?>
                <!-- <th width="10%" class="text-center">Aksi</th> -->
              </tr>
            </thead>
            <tfoot id="jumlah">
              <tr>
                <th colspan="4" style="text-align: center;">Rekap Presensi Siswa</th>
                <th style="text-align: center;" id="hadir">0</th>
                <th style="text-align: center;" id="sakit">0</th>
                <th style="text-align: center;" id="izin">0</th>
                <th style="text-align: center;" id="alpa">0</th>
              </tr>
              <tr>
                <th colspan="4" style="text-align: center;">Jumlah Siswa</th>
                <th colspan="2" style="text-align: center;">Laki-Laki</th>
                <th colspan="2" style="text-align: center;">Perempuan</th>
              </tr>
              <tr>
                <th colspan="4" style="text-align: center;" id="jml_siswa">0</th>
                <th colspan="2" style="text-align: center;" id="laki_laki">0</th>
                <th colspan="2" style="text-align: center;" id="perempuan">0</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<input type="hidden" id="user_id" name="user_id" value="">

<div class="modal fade" id="modal-table">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="callout">
          <b id="callout-title"></b>
          <b id="callout-mapel"></b>
        </div>
        <div class="table-responsive" style="border-top: 1px solid #EEEEEE;">
          <!-- <input type="text" id="id_user" name="id_user" value="" style="display: none;"> -->
          <table id="table1" class="table table-condensed" style="width: 100%">
            <thead>
              <tr>
                <th width="5%" class="text-center">No</th>
                <th width="20%">Tanggal</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<style type="text/css">
  .callout{
    border-left: 3px solid #00A65A; 
    border-right: 1px solid #EEEEEE; 
    border-top: 1px solid #EEEEEE; 
    border-bottom: 1px solid #EEEEEE;
  } 
  .callout b{
    display: block;
  }
</style>

<script type="text/javascript">
  var table1;
  var base_url      = index + "report/";
  var itp           = $('[name="id_tahun_pelajaran"]');
  var semester      = $('[name="id_semester"]');
  var id_kelas      = $('[name="id_kelas"]');
  var ijp           = $('[name="id_jadwal_pelajaran"]');
  var id_user       = $('[name="id_user"]');
  var tanggal       = $('[name="tanggal"]');
  var bulan         = $('[name="bulan"]');
  var tgl_awal      = $('[name="tgl_awal"]');
  var tgl_akhir     = $('[name="tgl_akhir"]');

  $(document).ready(function() {
    load_presensi();

    table = $('#table').DataTable({
      "processing": true,
      "serverSide": true,
      "ordering": false,
      "searching": false,
      "info": false,
      "order": [],
      "language": { 
          "infoFiltered": "",
          "sZeroRecords": "<b style='color: #777777;' id='sZeroRecords'></b>",
          "sSearch": "Cari:"
        },
        "ajax": {
          "url": base_url + "showPresence/<?= md5(time()) ?>",
          "type": "POST",
          "data": function(data) {
            data.id_tahun_pelajaran = itp.val();
            data.semester = semester.val();
            data.id_kelas = id_kelas.val();
            data.id_jadwal_pelajaran = ijp.val();
            data.tanggal = tanggal.val();
            data.bulan = bulan.val();
            data.tgl_awal = tgl_awal.val();
            data.tgl_akhir = tgl_akhir.val();
          },
        },
        "drawCallback": function(settings) {
         $('#user_id').val(settings.json.recordsFiltered).trigger('change');
         $('#sZeroRecords').text(settings.json.sZeroRecords);
         $('#hadir').text(settings.json.hadir);
         $('#sakit').text(settings.json.sakit);
         $('#izin').text(settings.json.izin);
         $('#alpa').text(settings.json.alpa);
         $('#jml_siswa').text(settings.json.jml_siswa);
         $('#laki_laki').text(settings.json.laki_laki);
         $('#perempuan').text(settings.json.perempuan);
         if (settings.json.recordsFiltered > 0) {
          $('#jumlah').show();
         } else {
          $('#jumlah').hide();
         }
        },
        "columnDefs": [{ 
          "targets": [-1],
          "orderable": false,
        }],
    });

    itp.change(function() {
      Pace.restart();
      table.ajax.reload();
      table1.ajax.reload();
      load_mapel();
      $('#id_tahun_pelajaran').closest('.form-group').removeClass('has-error');
      $('#id_tahun_pelajaran').nextAll('.help-block').eq(0).text('');
      load_tanggal();
      load_bulan();
    });

    semester.change(function() {
      Pace.restart();
      table.ajax.reload();
      table1.ajax.reload();
      $('#id_semester').closest('.form-group').removeClass('has-error');
      $('#id_semester').nextAll('.help-block').eq(0).text('');
      load_tanggal();
      load_bulan();
    });

    id_kelas.change(function() {
      Pace.restart();
      table.ajax.reload();
      load_mapel();
      $('#id_kelas').closest('.form-group').removeClass('has-error');
      $('#id_kelas').nextAll('.help-block').eq(0).text('');
      load_tanggal();
      load_bulan();
    });

    ijp.change(function() {
      Pace.restart();
      table.ajax.reload();
      table1.ajax.reload();
    });

    tanggal.change(function() {
      Pace.restart();
      table.ajax.reload();
      table1.ajax.reload();
      if (bulan.val()) {
        bulan.val('').change();
        bulan.select2(null, false);
      }
    });

    bulan.change(function() {
      Pace.restart();
      table.ajax.reload();
      table1.ajax.reload();
      if (tanggal.val()) {
        tanggal.val('').change();
        tanggal.select2(null, false);
      }
    });

    tgl_awal.change(function() {
      Pace.restart();
      table.ajax.reload();
      table1.ajax.reload();
    });

    tgl_akhir.change(function() {
      Pace.restart();
      table.ajax.reload();
      table1.ajax.reload();
    });

    $('.applyBtn').click(function() {
      Pace.restart();
      table3.ajax.reload();
    });

    $('#btn-refresh').click(function() {
      $('#daterange').val('').change();
      $('#tgl_awal').val('').change();
      $('#tgl_akhir').val('').change();
    });

  });

  function load_mapel() {
    var id_tahun_pelajaran = itp.val() ? itp.val() : "<?= time() ?>";

    $('#id_jadwal_pelajaran').find('option').not(':first').remove();

    $.getJSON(base_url + "getMapel/" + id_tahun_pelajaran + '/' + id_kelas.val(), function (data) {
        var option = [];
        for (let i = 0; i < data.length; i++) {
            option.push({
                id: data[i].jadwal_pelajaran_id,
                text: data[i].nama_mapel
            });
        }
        $('#id_jadwal_pelajaran').select2({
            data: option
        })
    });
  }

  function report_data() {
    var user_id   = $('[name="user_id"]').val();
    if (itp.val() && semester.val() && id_kelas.val()) {
      if (user_id > 0) {
        $('#form').attr('action', '<?= site_url('report/rps') ?>');
        $('#form').submit();
      } else {
        var message = 'Kelas Kosong';
        var type    = 'error';
        flashdata(message, type);
      }
    } else {
      if (!itp.val()) {
        $('#id_tahun_pelajaran').closest('.form-group').addClass('has-error');
        $('#id_tahun_pelajaran').nextAll('.help-block').eq(0).text('Tahun Pelajaran harus diisi');
      }
      if (!semester.val()) {
        $('#id_semester').closest('.form-group').addClass('has-error');
        $('#id_semester').nextAll('.help-block').eq(0).text('Semester harus diisi');
      } 
      if (!id_kelas.val()) {
        $('#id_kelas').closest('.form-group').addClass('has-error');
        $('#id_kelas').nextAll('.help-block').eq(0).text('Kelas harus diisi');
      } 
    }
  }

  function print_data(id) {
    if (itp.val() && semester.val()) {
      $.ajax({
          url: base_url + "checkMapel/" + itp.val(),
          data: {
            id_kelas: id_kelas.val(),
          },
          type: "POST",
          dataType: "JSON",
          success: function(response) {
            if (response.status) {
              $('#form').attr('action', '<?= site_url('report/student/') ?>' + id);
              $('#form').submit();
            } else {
              var message = 'Jadwal Pelajaran Kosong';
              var type    = 'error';
              flashdata(message, type);
            }
          }
      });
    } else {
      if (!itp.val()) {
        $('#id_tahun_pelajaran').closest('.form-group').addClass('has-error');
        $('#id_tahun_pelajaran').nextAll('.help-block').eq(0).text('Tahun Pelajaran harus diisi');
      }
      if (!semester.val()) {
        $('#id_semester').closest('.form-group').addClass('has-error');
        $('#id_semester').nextAll('.help-block').eq(0).text('Semester harus diisi');
      } 
    }
  }

  function detail_data(id) {
    var name = $('#name_'+ id +'').val();
    var mapel = $('#mapel_'+ id +'').val();

    if (itp.val() && semester.val()) {

      if (ijp.val()) {
        $('#callout-mapel').text(mapel);
      } else {
        $('#callout-mapel').text('');
      }

      $('.modal-title').text('Detail Presensi Siswa');
      $('#callout-title').text(name);
      $('#id_user').val(id).trigger('change');
      $('#modal-table').modal('show');

    } else {
      if (!itp.val()) {
        $('#id_tahun_pelajaran').closest('.form-group').addClass('has-error');
        $('#id_tahun_pelajaran').nextAll('.help-block').eq(0).text('Tahun Pelajaran harus diisi');
      }
      if (!semester.val()) {
        $('#id_semester').closest('.form-group').addClass('has-error');
        $('#id_semester').nextAll('.help-block').eq(0).text('Semester harus diisi');
      }
    }
  }

  function load_presensi() {
    table1 = $('#table1').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": false,
        "language": { 
          "infoFiltered": ""
        },
        "ajax": {
          "url": base_url + "showDetailPresence/<?= md5(time()) ?>",
          "type": "POST",
          "data": function(data) {
            data.id_tahun_pelajaran = itp.val();
            data.id_jadwal_pelajaran = ijp.val();
            data.id_user  = id_user.val();
            data.semester = semester.val();
          },
        },
        "columnDefs": [{ 
          "targets": [-1],
          "orderable": false,
        }],
    });

    id_user.change(function() {
      Pace.restart();
      table1.ajax.reload();
    });

  }

  function change_status(id) {
    var status = $('#status_'+ id +'').val();

    $.ajax({
        url: base_url + "changeStatus/" + itp.val(),
        data: {
          presensi_id: id,
          status: status,
        },
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          Pace.restart();
          table.ajax.reload();
          table1.ajax.reload();
          if (response.message) {
            flashdata(response.message);
          }
        }
    });
  }

  function load_tanggal() {
    var id_tahun_pelajaran = itp.val() ? itp.val() : "<?= time() ?>";

    $('#tanggal').find('option').not(':first').remove();

    $.getJSON(base_url + 'getTglPresensi/' + id_tahun_pelajaran, {
      id_kelas: id_kelas.val(),
      semester: semester.val(),
    }, function(data) {
      var option = [];
      for (let i = 0; i < data.length; i++) {
          option.push({
              id: data[i].id_tgl,
              text: data[i].tanggal
          });
      }
      $('#tanggal').select2({
          data: option
      })
    });

  }

  function load_bulan() {
    var id_tahun_pelajaran  = itp.val() ? itp.val() : "<?= time() ?>";
    var id_semester         = semester.val() ? semester.val() : "<?= time() ?>";

    $('#bulan').find('option').not(':first').remove();

    $.getJSON(base_url + 'getBlnPresensi/' + id_semester + '/' + id_tahun_pelajaran, function(data) {
      var option = [];
      for (let i = 0; i < data.length; i++) {
          option.push({
              id: data[i].id_bln,
              text: data[i].bulan
          });
      }
      $('#bulan').select2({
          data: option
      })
    });

  }
</script>