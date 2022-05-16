<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
      <li><a href="<?= site_url() ?>"><i class="fa fa-cogs"></i> <?= $folder ?></a></li>
      <li class="active"><?= $title ?></li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-8 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar <?= $title ?></h3>
          <div class="box-tools pull-right">
            <a href="javascript:void(0)" onclick="add_data();" class="btn btn-box-tool" data-toggle="tooltip"><i class="fa fa-plus"></i></a>
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table id="table" class="table" style="width: 100%">
              <thead>
                <tr>
                  <th width="5%" class="text-center">No</th>
                  <th class="text-center">Gambar</th>
                  <th class="text-center" width="20%">Status</th>
                  <th class="text-center" width="20%">Aksi</th>
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
        <button type="button" class="close cancel" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <form action="<?= site_url('setting/slider/saveSlider') ?>" id="form-image" method="post" enctype="multipart/form-data">
        <input type="file" name="gambar" style="display: none;">
        <input type="text" name="id" value="" style="display: none;">
        <input type="text" name="image" value="" style="display: none;">
        <div class="modal-body">
          <div class="form-group">
            <img class="img-responsive" id="image" src="" alt="Photo" style="display: block; margin-left: auto; margin-right: auto; max-width: 270px; max-height: 200px; display: none; width: 100%; height: 100%;">
          </div>
          <div class="form-group">
            <button type="button" class="btn btn-default btn-block btn-flat" id="upload"><i class="fa fa-image"></i> <span style="font-weight: bold;">Upload Gambar</span></button>
            <small class="help-block" style="color: #DD4B39;"></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm pull-left cancel" data-dismiss="modal" style="background-color: #6C757D; color: #FFFFFF; font-weight: bold; font-family: serif;"><i class="fa fa-times"></i> Batal</button>
          <button type="button" class="btn btn-sm pull-right" style="background-color: #00A65A; color: #FFFFFF; font-weight: bold; font-family: serif;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  var base_url = index + "setting/slider/";

  $(document).ready(function() {
  
    table = $('#table').DataTable({
        "processing": true,
        "ordering": false,
        "serverSide": true,
        "searching": false,
        "lengthChange": false,
        "info": false,
        "order": [],
        "language": { 
          "infoFiltered": ""
        },
        "ajax": {
          "url": base_url + "showImageSlider",
          "type": "POST",
        },
        "columnDefs": [{ 
          "targets": [-1],
          "orderable": false,
        }],
    });

    $('#upload').click(function() {
      $('[name="gambar"]').click();
    });

    $('[name="gambar"]').change(function() {
      preview_image(this);
      var input = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
      $('[name="image"]').val(input);
      $('#upload').removeAttr("style", "border: 1px solid #DD4B39;");
      $('.help-block').text('');
      $('#image').show();
    });

    $('.cancel').click(function() {
      $('#form-image')[0].reset();
      $('#image').attr('src', '');
      $('#upload').removeAttr("style", "border: 1px solid #DD4B39;");
      $('.help-block').text('');
      $('#image').hide();
    });

    $('#btn-save').click(function() {
      input = $('[name="image"]').val();
      if (input) {
        save_data();
      } else {
        $('#upload').attr("style", "border: 1px solid #DD4B39;");
        $('.help-block').text('Gambar harus diisi');
      }
    });

  });

  function preview_image(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      
      reader.onload = function(e) {
        $('#image').attr('src', e.target.result);
      }
      
      reader.readAsDataURL(input.files[0]);
    }
  }

  function add_data() {
    $('#form-image')[0].reset();
    $('.modal-title').text('Tambah Gambar');
    $('#modal-form').modal('show');
    $('#image').attr('src', '');
    $('#image').hide();
  }

  function edit_data(id) {
    var image = $('[name="image_'+ id +'"]').val();
    $('#form-image')[0].reset();
    $('.modal-title').text('Edit Gambar');
    $('#modal-form').modal('show');
    $('[name="id"]').val(id);
    $('#image').attr('src', image);
    $('#image').show();
  }

  function save_data() {
      var form = $('#form-image');
      $.ajax({
          url: form.attr('action'),
          type: "POST",
          data: new FormData(form[0]),
          processData:false,
          contentType:false,
          dataType: "JSON",
          success: function (response) {
            Pace.restart();
            table.ajax.reload();
            flashdata(response.message);
            $('#modal-form').modal('hide');
            $('#image').attr('src', '');
          }
      });
  }

  function delete_data(id) {
    var text  = "Akan menghapus Gambar";
    var url   = base_url + "deleteSlider/" + id;
    confirm_delete(text, url);
  }

  function success_delete() {
    table.ajax.reload();
  }

  function change_status(id) {
    $.ajax({
        url: base_url + "changeStatus/" + id,
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
</script>