<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url('schedules') ?>"><i class="fa fa-calendar"></i> <?= $title ?></a></li>
    <li class="active"><?= $header ?></li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar Presensi</h3>
          <div class="box-tools pull-right">
            <div class="btn-group">
              <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
              <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:void(0)" onclick="add_data();">Input Presensi</a></li>
                <li class="divider"></li>
                <li><a href="javascript:void(0)" onclick="print_per_semester();">Rekap Per Semester</a></li>
                <li class="divider"></li>
                <li><a href="javascript:void(0)" onclick="print_per_siswa();">Rekap Per Siswa</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="box-body">
          <div class="callout">
            <?= $content['callout'] ?>
          </div>
          <div class="row">
            <div class="col-md-4 col-xs-12">
              <div class="form-group">
                <?= $content['mapel'] ?>
              </div>
            </div>
            <div class="col-md-4 col-xs-12">
              <div class="form-group">
                <?= $content['semester'] ?>
                </select>
              </div>
            </div>
            <div class="col-md-4 col-xs-12">
              <div class="form-group">
                <?= $content['siswa'] ?>
              </div>
            </div>
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table id="table" class="table table-hover" style="width: 100%">
              <thead>
                <tr>
                  <th width="5%" class="text-center">No</th>
                  <th>NIS</th>
                  <th>Nama</th>
                  <th width="5%" class="text-center">JK</th>
                  <?php foreach ($this->include->opsiPresensi() as $key => $value) {
                    echo '<th class="text-center">'. $value .'</th>';
                  } ?>
                  <th width="5%" class="text-center">Detail</th>
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
        </div>
        <div class="table-responsive" style="border-top: 1px solid #EEEEEE;">
          <input type="hidden" id="id_user" name="id_user" value="">
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

<form action="<?= site_url('schedules/pps') ?>" id="print-semester" method="post" target="_blank" style="display: none;">
  <input type="text" name="smt" value="">
  <input type="text" name="tpi" value="">
  <input type="text" name="kls" value="">
</form>

<form action="<?= site_url('schedules/ppd') ?>" id="print-siswa" method="post" target="_blank" style="display: none;">
  <input type="text" name="smt" value="">
  <input type="text" name="tpi" value="">
  <input type="text" name="siswa" value="">
</form>

<script type="text/javascript">
  var base_url  = index + "schedules/";
  var id_kelas  = $('[name="id_kelas"]');
  var ijp       = $('[name="id_jadwal_pelajaran"]');
  var itp       = $('[name="id_tahun_pelajaran"]');
  var semester  = $('[name="id_semester"]');
  var id_user   = $('[name="user_id"]');

  $(document).ready(function() {
      list_presensi();

      table = $('#table').DataTable({
        "dom": "tp",
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "order": [],
        "language": { 
          "infoFiltered": ""
        },
          "ajax": {
            "url": base_url + "showAttendance",
            "type": "POST",
            "data": function(data) {
              data.id_kelas = id_kelas.val();
              data.id_jadwal_pelajaran = ijp.val();
              data.id_tahun_pelajaran = itp.val();
              data.id_user = id_user.val();
              data.semester = semester.val();
            },
          },
          "columnDefs": [{ 
            "targets": [-1],
            "orderable": false,
          }],
      });

      ijp.change(function() {
        Pace.restart();
        table.ajax.reload();
        if (ijp.val()) {
          var mapel = $('[name="mapel_'+ ijp.val() +'"]').val();
          $('#mapel').text(mapel);
        } else {
          $('#mapel').text('');
        }
        $('#id_jadwal_pelajaran').closest('.form-group').removeClass('has-error');
        $('#id_jadwal_pelajaran').nextAll('.help-block').eq(0).text('');
      });

      id_user.change(function() {
        Pace.restart();
        table.ajax.reload();
        $('#user_id').closest('.form-group').removeClass('has-error');
        $('#user_id').nextAll('.help-block').eq(0).text('');
      });

      semester.change(function() {
        Pace.restart();
        table.ajax.reload();
        $('#id_semester').closest('.form-group').removeClass('has-error');
        $('#id_semester').nextAll('.help-block').eq(0).text('');
      });


  });

  function show_presensi(id) {
    var title = $('#title').val();
    var name = $('[name="name_'+ id +'"]').val();

    if (ijp.val()) {
      $('.modal-title').text(title);
      $('#callout-title').text(name);
      $('#id_user').val(id).trigger('change');
      $('#modal-form').modal('show');
    } else {
      $('#id_jadwal_pelajaran').closest('.form-group').addClass('has-error');
      $('#id_jadwal_pelajaran').nextAll('.help-block').eq(0).text('Mata Pelajaran harus diisi');
    }

  }

  function list_presensi() {
    var id_user = $('[name="id_user"]');

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
          "url": base_url + "showPresensi",
          "type": "POST",
          "data": function(data) {
            data.id_jadwal_pelajaran = ijp.val();
            data.id_user  = id_user.val();
            data.semester = semester.val();
            data.id       = "<?= time() ?>";
          },
        },
        "columnDefs": [{ 
          "targets": [-1],
          "orderable": false,
        }],
    });

    id_user.on('change', function() {
      Pace.restart();
      table1.ajax.reload();
    });
  }

  function change_status(id) {
    status = $('[name="status_' + id + '"]').val();
    presensi_id = $('[name="presensi_id_' + id + '"]').val();

    $.ajax({
        url: base_url + "changeStatus/" + ijp.val(),
        data: {
          id_presensi: presensi_id,
          status: status,
        },
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          Pace.restart();
          table.ajax.reload();
          table1.ajax.reload();
        }
    });
  }

  function add_data() {
    if (ijp.val()) {
      window.location.href = base_url + "presence/" + ijp.val();
    } else {
      $('#id_jadwal_pelajaran').closest('.form-group').addClass('has-error');
      $('#id_jadwal_pelajaran').nextAll('.help-block').eq(0).text('Mata Pelajaran harus diisi');  
    }
  }

  function print_per_semester() {
    if (semester.val()) {
      var tpi = $('[name="id_tahun_pelajaran"]').val();
      $('[name="smt"]').val(semester.val());
      $('[name="tpi"]').val(tpi);
      $('[name="kls"]').val(id_kelas.val());
      $('#print-semester').submit();
    } else {
      $('#id_semester').closest('.form-group').addClass('has-error');
      $('#id_semester').nextAll('.help-block').eq(0).text('Semester harus diisi');  
    }
  }

  function print_per_siswa() {
    if (semester.val() && id_user.val()) {
      var tpi = $('[name="id_tahun_pelajaran"]').val();
      $('[name="smt"]').val(semester.val());
      $('[name="tpi"]').val(tpi);
      $('[name="siswa"]').val(id_user.val());
      $('#print-siswa').submit();
    } else {
      if (semester.val()) {
        $('#id_semester').closest('.form-group').removeClass('has-error');
        $('#id_semester').nextAll('.help-block').eq(0).text('');
      } else {
        $('#id_semester').closest('.form-group').addClass('has-error');
        $('#id_semester').nextAll('.help-block').eq(0).text('Semester harus diisi');
      }
      $('#user_id').closest('.form-group').addClass('has-error');
      $('#user_id').nextAll('.help-block').eq(0).text('Siswa harus diisi');  
    }
  }
  
</script>