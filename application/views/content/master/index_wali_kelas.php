<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
    <?php if (@$folder): ?>
      <li><a href="<?= site_url() ?>"><i class="fa fa-folder-open"></i> <?= $folder ?></a></li>
      <li class="active"><?= $title ?></li>
    <?php else: ?>
      <li><a href="<?= site_url() ?>"><i class="fa fa-user"></i> <?= $title ?></a></li>
    <?php endif ?>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-8 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar <?= $title ?></h3>
          <div class="box-tools pull-right">
            <div id="btn-generate" style="display: none;">
              <a href="javascript:void(0)" onclick="generate_kelas();" class="btn btn-box-tool" data-toggle="tooltip"><i class="fa fa-plus"></i></a>
            </div>
          </div>
        </div>
        <div class="box-body">
          <!-- <div class="row">
            <div class="col-md-8 col-xs-12">
              <div class="form-group row">
                <label for="" class="col-sm-4 control-label">Tahun Pelajaran</label>
                <div class="col-sm-6">
                  <select name="id_tahun_pelajaran" id="id_tahun_pelajaran" class="form-control select2" style="width: 100%">
                    <option value="">-- Tahun Pelajaran --</option>
                      <?php foreach ($this->mall->get_tapel() as $row): ?>
                        <option value="<?= $row->tahun_pelajaran_id ?>" <?php if($row->tahun_pelajaran_id == @$tapel->tahun_pelajaran_id) echo "selected"; ?>><?= $row->tahun_pelajaran ?></option>
                      <?php endforeach ?>
                  </select>
                </div>
              </div>
            </div>
          </div> -->
          <div class="row">
            <div class="col-md-6 col-xs-12">
              <div class="form-group">
                <label for="id_tahun_pelajaran">Filter Tahun Pelajaran</label>
                <select name="id_tahun_pelajaran" id="id_tahun_pelajaran" class="form-control select2" style="width: 100%">
                  <option value="">-- Pilih Tahun Pelajaran --</option>
                    <?php foreach ($this->mall->get_tapel() as $row): ?>
                      <option value="<?= $row->tahun_pelajaran_id ?>" <?php if($row->tahun_pelajaran_id == @$tapel->tahun_pelajaran_id) echo "selected"; ?>><?= $row->tahun_pelajaran ?><?php if($row->tahun_pelajaran_id == @$tapel->tahun_pelajaran_id) echo " - Aktif"; ?></option>
                    <?php endforeach ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="box-body table-responsive">
          <table id="table" class="table table-hover" style="width: 100%">
            <thead>
              <tr>
                <th width="5%" class="text-center">No</th>
                <th class="text-center">Wali Kelas</th>
                <th>Kelas</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
  var base_url  = index + "master/homeroom/";
  var itp       = $('[name="id_tahun_pelajaran"]');

  $(document).ready(function() {
    check_kelas();
    datetable();
  });

  function datetable() {
    table = $('#table').DataTable({
        "processing": true,
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
          "url": base_url + "showDataTables",
          "type": "POST",
          "data": function(data) {
            data.itp = itp.val();
          },
        },
        "columnDefs": [{ 
          "targets": [-1],
          "orderable": false,
        }],
    });

    itp.on('change', function() {
        table.ajax.reload();
        Pace.restart();
        check_kelas();
    });
  }

  function check_kelas() {
    $.ajax({
        url: base_url + "checkKelas/" + itp.val(),
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          if (response.button) {
            $('#btn-generate').show();
          } else {
            $('#btn-generate').hide();
          }
        }
    });
  }

  function change_wakel(id) {
    id_user = $('[name="id_user_'+ id +'"]').val();
    $.ajax({
        url: base_url + "changeWakel",
        data: {
          wali_kelas_id: id,
          id_user: id_user,
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

  function generate_kelas() {
    $.ajax({
        url: base_url + "generateWakel/" + itp.val(),
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          Pace.restart();
          table.ajax.reload();
          check_kelas();
          flashdata(response.message);
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
    check_kelas();
  }

</script>