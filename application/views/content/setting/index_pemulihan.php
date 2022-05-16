<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-cogs"></i> <?= $folder ?></a></li>
    <li class="active"><?= $title ?></li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar <?= $title ?></h3>
        </div>
        <div class="box-body table-responsive">
          <table id="table" class="table" style="width: 100%">
            <thead>
              <tr>
                <th width="5%" class="text-center">No</th>
                <th>Metadata</th>
                <th>Bidang</th>
              </tr>
            </thead>
          </table>
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
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var base_url  = index + 'setting/recover/';
  var url_table = 'setting/recover/show_recover/<?= md5(time()) ?>';

  $(function() {
    set_datatable(url_table);
  });

  function kembalikan_data(key) {
    $.ajax({
      url: base_url + 'update_delete/<?= md5(time()) ?>',
      type: 'POST',
      dataType: 'json',
      data: {
        tb: $('#tb_'+ key +'').val(),
        pk: $('#pk_'+ key +'').val(),
        id: key,
        name: $('#name_'+ key +'').val(),
        type: $('#type_'+ key +'').val(),
        update_at: '<?= md5(time()) ?>'
      },
      success: function(response) {
        if (response.status) {
          Pace.restart();
          table.ajax.reload();
          flashdata(response.message);
        }
      }
    });
  }

  function hapus_permanen(key) {
    Swal.fire({
      title: '<span style="font-family: cambria;">Apakah anda yakin?</span>',
      text: 'Akan menghapus permanen ' + $('#name_'+ key +'').val(),
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
          url: base_url + 'update_delete/<?= md5(time()) ?>',
          type: 'POST',
          dataType: 'json',
          data: {
            tb: $('#tb_'+ key +'').val(),
            pk: $('#pk_'+ key +'').val(),
            id: key,
            name: $('#name_'+ key +'').val(),
            type: $('#type_'+ key +'').val(),
            delete_at: '<?= md5(time()) ?>'
          },
          success: function(response) {
            if (response.status) {
              Pace.restart();
              table.ajax.reload();
              flashdata(response.message);
            }
          }
        });
      }
    })
    
  }

  function jadwal_pelajaran(id) {
    $.getJSON(base_url + 'jadwal_pelajaran/' + id, function(data) {
      Pace.restart();
      $('.modal-title').text('Detail Jadwal Pelajaran');
      $('.modal-body').html(data);
      $('#modal-form').modal('show');
    });
  }

  function presensi_siswa(id) {
    $.getJSON(base_url + 'presensi_siswa/' + id, function(data) {
      Pace.restart();
      $('.modal-title').text('Detail Presensi Siswa');
      $('.modal-body').html(data);
      $('#modal-form').modal('show');
    });
  }

</script>

