<section class="content-header">
  <h1><?= $title ?>
    <?php if (@$tapel): ?>
     <small>Tahun Pelajaran <?= $tapel ?></small>
    <?php endif ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="javascript:void(0)"><i class="fa fa-home"></i> <?= $title ?></a></li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box">
        <div class="inner">
          <h3><?= $pengguna ?></h3>
          <p>Pengguna</p>
        </div>
        <div class="icon">
          <i class="fa fa-users"></i>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box">
        <div class="inner">
          <h3><?= $adminstrator ?></h3>
          <p>Administrator</p>
        </div>
        <div class="icon">
          <a href="<?= site_url('user/administration') ?>"><i class="fa fa-user"></i></a>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box">
        <div class="inner">
          <h3><?= $guru ?></h3>
          <p>Guru</p>
        </div>
        <div class="icon">
          <a href="<?= site_url('user/teacher') ?>"><i class="fa fa-user"></i></a>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <div class="small-box">
        <div class="inner">
          <h3><?= $siswa ?></h3>
          <p>Siswa</p>
        </div>
        <div class="icon">
          <a href="<?= site_url('user/student') ?>"><i class="fa fa-user"></i></a>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar Jadwal Pelajaran <small><?= $this->include->days(date('w')) . ', ' . $this->include->date(date('Y-m-d')) ?></small></h3>
        </div>
        <div class="box-body table-responsive">
          <table id="table" class="table table-hover" style="width: 100%">
            <thead>
              <tr>
                <th width="5%" class="text-center">No</th>
                <th>Kode<span style="color: #FFFFFF;">_</span>-<span style="color: #FFFFFF;">_</span>Mata<span style="color: #FFFFFF;">_</span>Pelajaran</th>
                <th>Kelas</th>
                <th>Guru</th>
                <th width="15%">Jam<span style="color: #FFFFFF;">_</span>Pelajaran</th>
                <!-- <th width="5%" class="text-center">Status</th> -->
                <th width="10%" class="text-center">Presensi<span style="color: #FFFFFF;">_</span>Siswa</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modal-table">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Input Presensi</h4>
      </div>
      <div class="modal-body">
        <div class="callout"></div>
        <div class="row">
          <div class="col-md-12 col-xs-12">
            <input type="hidden" id="id_kelas" name="id_kelas" value="">
            <input type="hidden" id="id_jadwal_pelajaran" name="id_jadwal_pelajaran" value="">
            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-search"></i>
              </span>
              <select name="user_id" id="user_id" class="form-control select2"></select>
              <span class="input-group-addon">
                <input type="checkbox" id="check-all" value="" style="display: none;">
                <i class="fa fa-check" id="checked" style="display: none;"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <div class="table-responsive" style="border-top: 1px solid #EEEEEE;">
          <table id="table-modal" class="table table-condensed" style="width: 100%">
            <thead>
              <tr>
                <th width="5%" class="text-center">No</th>
                <th>NIS</th>
                <th>Nama</th>
                <th width="5%" class="text-center">JK</th>
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
  .small-box{
    height: 120px;
    background-color: #FFFFFF;
    border-top: 3px solid #00A65A;
  }
  .inner{
    color: #333333;
    font-weight: bold;
  }
  .inner h3{
    font-family: serif;
  }
  .small-box .icon{
    margin-top: 5px;
    color: #333333;
  }
  .icon a{ 
    color: #333333;
  }

  .callout{
    border-left: 3px solid #00A65A; 
    border-bottom: 1px solid #EEEEEE; 
    border-right: 1px solid #EEEEEE; 
    border-top: 1px solid #EEEEEE;
  }
  .callout b{
    display: block;
  }
</style>

<script type="text/javascript">
  var tb_input;
  var base_url  = index + "teacher/";

  $(document).ready(function() {
    var url_table = "home/showJadwal";
    set_datatable(url_table);
    datatable();

    $('#check-all').click(function() {
      change_all($(this).val());
    });

  });

  function add_data(id) {
    Pace.restart();
    load_callout(id);
    check_date(id);
    var kelas_id = $('[name="id_kelas_'+ id +'"]').val();
    $('#id_kelas').val(kelas_id).trigger('change');
    $('#id_jadwal_pelajaran').val(id).trigger('change');
    $('#check-all').val(id);
    $('#modal-table').modal('show');
  }

  function load_callout(id_jadwal_pelajaran) {
    $.ajax({
        url: index + "home/getDetailJadwal/" + id_jadwal_pelajaran,
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          $('.callout').html(response.callout);
          $('#user_id').html(response.option);
        }
    });
  }

  function datatable() {
    var id_kelas  = $('[name="id_kelas"]');
    var id_jadwal_pelajaran = $('[name="id_jadwal_pelajaran"]');
    var id_user = $('[name="user_id"]');

    tb_input = $('#table-modal').DataTable({
      "dom": "tp",
      "processing": true,
      "serverSide": true,
      "order": [],
      "ordering": false,
      "language": { 
        "infoFiltered": ""
      },
        "ajax": {
          "url": base_url + "showKehadiran",
          "type": "POST",
          "data": function(data) {
            data.id_kelas = id_kelas.val();
            data.id_jadwal_pelajaran = id_jadwal_pelajaran.val();
            data.id_user = id_user.val();
          },
        },
        "columnDefs": [{ 
          "targets": [0],
          "orderable": false,
        }],
    });

    id_user.change(function() {
        Pace.restart();
        tb_input.ajax.reload();
    });

    id_kelas.change(function() {
        tb_input.ajax.reload();
    });

    id_jadwal_pelajaran.change(function() {
        tb_input.ajax.reload();
    });

  }

  function check_date(id_jadwal_pelajaran) {
    $.ajax({
        url: base_url + "getDate/" + id_jadwal_pelajaran,
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          if (response.status) {
            $('#check-all').hide();
            $('#checked').show();
          } else {
            $('#check-all').show();
            $('#checked').hide();
          }
        }
    });
  }

  function change_all(id_jadwal_pelajaran) {
    $.ajax({
        url: base_url + "changeAll/" + id_jadwal_pelajaran,
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          if (response.status) {
            Pace.restart();
            tb_input.ajax.reload();
            table.ajax.reload();
            check_date(id_jadwal_pelajaran);
          }
          $('#check-all').prop('checked', false);
        }
    });
  }

  function change_status(id) {
    status = $('[name="status_' + id + '"]').val();
    user_id = $('[name="user_id_' + id + '"]').val();
    id_jadwal_pelajaran = $('[name="id_jadwal_pelajaran_' + id + '"]').val();

    $.ajax({
        url: base_url + "addStatus/" + id_jadwal_pelajaran,
        data: {
          id_user: user_id,
          status: status,
        },
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          Pace.restart();
          tb_input.ajax.reload();
          table.ajax.reload();
          check_date(id_jadwal_pelajaran);
        }
    });
  }
</script>