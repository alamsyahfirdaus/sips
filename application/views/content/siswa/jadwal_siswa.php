<section class="content-header">
  <h1><?= $title ?>
  <?php if (@$tapel) echo '<small>Tahun Pelajaran '. $tapel .'</small>'; ?>
  </h1>
  <!-- <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-home"></i> <?= @$folder ?></a></li>
    <li class="active"><?= @$title ?></li>
  </ol> -->
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <?= $jadwal_pelajaran; ?>
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
        <div class="callout" style="border-left: 3px solid #00A65A; border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE;">
          <b id="callout-title" style="display: block;"></b>
          <b style="display: block;" id="kelas"></b>
          <b style="display: block;" id="semester"></b>
        </div>
        <div id="progress"></div>
        <div class="table-responsive" style="border-top: 1px solid #EEEEEE;">
          <table id="table" class="table table-condensed" style="width: 100%;">
            <thead>
              <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="text-align: center;">Tanggal</th>
                <th style="text-align: center;">Keterangan</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-rekap">
  <div class="modal-dialog">
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="callout" style="border-left: 3px solid #00A65A; border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE;">
          <b style="display: block;">Tahun Pelajaran : <?= @$tapel ?></b>
          <b style="display: block;" id="semester1"></b>
        </div>
          <?php

          $list = $rekap['kehadiran'];
          $list .= '<ul class="list-group list-group-unbordered">';

          foreach ($this->include->opsiPresensi() as $key => $value) {
            $list .= '<li class="list-group-item">';
            $list .= '<b>'. $value .'</b><b class="pull-right">'. $rekap[$key] .'</b>';
            $list .= '</li>';
            
          } 

          $list .= '</ul>';

          echo $list;

          ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    var ijp = $('[name="id_jadwal_pelajaran"]');
    var id_user = $('[name="id_user"]');
    var semester = $('[name="id_sem"]');
    table = $('#table').DataTable({
        "processing": false,
        "serverSide": true,
        "order": [],
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": false,
        "language": { 
          "infoFiltered": "",
          "sZeroRecords": "<b style='color: #777777;'>TIDAK DITEMUKAN</b>",
          "sSearch": "Cari:"
        },
        "ajax": {
          "url": "<?= site_url('student/showPresensi/' . md5(time())) ?>",
          "type": "POST",
          "data": function(data) {
            data.id_jadwal_pelajaran = ijp.val();
            data.semester = semester.val();
            data.id_user = id_user.val();
          },
        },
        "drawCallback": function(settings) {
          $('#progress').html(settings.json.kehadiran);
        },
        "columnDefs": [{ 
          "targets": [0],
          "orderable": false,
        }],
    });

    ijp.on('change', function() {
      Pace.restart();
      table.ajax.reload();
    });
    
  });

  function list_presensi(id) {
      var title = $('#title').val();
      var kelas = $('[name="kelas"]').val();
      var semester = $('[name="semester"]').val();
      var mapel = $('[name="mapel_'+ id +'"]').val();
      $('.modal-title').text(title);
      $('#callout-title').text(mapel);
      $('#kelas').text(kelas);
      $('#semester').text(semester);
      $('#jadwal_pelajaran_id').val(id).trigger('change');
      $('#modal-form').modal('show');
  }

  function rekap_presensi() {
      Pace.restart();
      var semester  = $('[name="semester"]').val();
      $('.modal-title').text('Rekap Presensi');
      $('#semester1').text(semester);
      $('#modal-rekap').modal('show');
  }
</script>
