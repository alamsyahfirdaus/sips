<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-cogs"></i> <?= $folder ?></a></li>
    <li><a href="<?= site_url($this->uri->segment(1) . '/' . $this->uri->segment(2)) ?>"><?= $title ?></a></li>
    <li class="active"><?= $sub_title ?></li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-12 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar <?= $header ?></h3>
          <div class="box-tools pull-right">
            <input type="hidden" name="user_type_id" value="<?= md5($user_type_id) ?>">
            <?php if ($user_type_id == 1): ?>
              <div class="btn-group">
                <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i></button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="javascript:void(0)" onclick="add_menu();">Tambah Menu</a></li>
                  <li class="divider"></li>
                  <li><a href="javascript:void(0)" onclick="add_sub_menu();">Tambah Sub Menu</a></li>
                </ul>
              </div>
            <?php endif ?>
          </div>
        </div>
        <div class="box-body table-responsive">
          <table id="table" class="table" style="width: 100%">
            <thead>
              <tr>
                <th width="5%" class="text-center">No</th>
                <th>Menu</th>
                <th>Sub Menu</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modal-menu">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <form action="<?= site_url('setting/menu/saveMenu') ?>" method="post" id="form-menu">
        <input type="text" name="menu_id" value="" style="display: none;">
        <div class="modal-body">
          <div class="form-group">
            <label for="menu">Menu</label>
            <input type="text" class="form-control" id="menu" name="menu" placeholder="Menu" autocomplete="off">
            <small class="help-block"></small>
          </div>
          <div class="form-group">
            <label for="icon">Icon</label>
            <input type="text" class="form-control" id="icon" name="icon" placeholder="Icon" autocomplete="off">
            <small class="help-block"></small>
          </div>
          <div class="form-group">
            <label for="url_menu">Url</label>
            <input type="text" class="form-control" id="url_menu" name="url_menu" placeholder="Url" autocomplete="off">
            <small class="help-block"></small>
          </div>
        </div>
        <div class="modal-footer">
          <?= BTN_CLOSE . BTN_SUBMIT ?>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-sub-menu">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <form action="<?= site_url('setting/menu/saveSubMenu') ?>" method="post" id="form-sub-menu">
        <input type="text" name="sub_menu_id" value="" style="display: none;">
        <div class="modal-body">
          <div class="form-group">
            <label for="id_menu">Menu</label>
            <select class="form-control select2" style="width: 100%;" name="id_menu" id="id_menu">
              <option value="">Menu</option>
            </select>
            <small class="help-block"></small>
          </div>
          <div class="form-group">
            <label for="sub_menu">Sub Menu</label>
            <input type="text" class="form-control" id="sub_menu" name="sub_menu" placeholder="Sub Menu" autocomplete="off">
            <small class="help-block"></small>
          </div>
          <div class="form-group">
            <label for="url">Url</label>
            <input type="text" class="form-control" id="url" name="url" placeholder="Url" autocomplete="off">
            <small class="help-block"></small>
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
  var base_url      = index + "setting/menu/";
  var user_type_id  = $('[name="user_type_id"]').val();

  $(document).ready(function() {
    var url_table = "setting/menu/showAccessMenu/" + user_type_id;
    set_datatable(url_table);
    validation_menu();
    validation_sub_menu();
    load_menu();

  });

  function checked_menu(menu_id) {
    $("#menu_" + menu_id).change(function () {
      $(".sub_menu_" + menu_id).prop('checked', $(this).prop('checked'));
    });

    $.ajax({
        url: base_url + "changeMenu",
        data: {
          user_type_id: user_type_id,
          menu_id: menu_id,
        },
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          if (response.uti == 1) {
            window.location.reload();
          } else {
            table.ajax.reload();
          }
        }
    });

  }

  function checked_sub_menu(sub_menu_id) {
    $.ajax({
        url: base_url + "changeSubMenu",
        data: {
          user_type_id: user_type_id,
          sub_menu_id: sub_menu_id,
        },
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          if (response.uti == 1) {
            window.location.reload();
          } else {
            table.ajax.reload();
          }
        }
    });
  }

  function sort_menu(menu_id) {
   $.ajax({
       url: base_url + "sortMenu/" + menu_id,
       type: "POST",
       dataType: "JSON",
       success: function(response) {
         window.location.reload();
       }
   });
  }

  function sort_sub_menu(sub_menu_id) {
    $.ajax({
        url: base_url + "sortSubMenu/" + sub_menu_id,
        type: "POST",
        dataType: "JSON",
        success: function(response) {
          window.location.reload();
        }
    });
  }

  function delete_sub_menu(sub_menu_id) {
    var text  = "Akan menghapus Sub Menu";
    var url   = base_url + "deleteSubMenu/" + sub_menu_id;
    confirm_delete(text, url);
  }

  function delete_menu(menu_id) {
    var text  = "Akan menghapus Menu";
    var url   = base_url + "deleteMenu/" + menu_id;
    confirm_delete(text, url);
  }

  function success_delete() {
    table.ajax.reload();
  }

  function add_menu() {
    $('#form-menu')[0].reset();
    $('#form-menu').data('bootstrapValidator').resetForm();
    $('.modal-title').text('Tambah Menu');
    $('#modal-menu').modal('show');
  }

  function edit_menu(menu_id) {
    $('#form-menu')[0].reset();
    $('#form-menu').data('bootstrapValidator').resetForm();
    $.ajax({
        url : base_url + "getDataMenu/" + menu_id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="menu_id"]').val(menu_id);
            $('[name="menu"]').val(data.menu);
            $('[name="icon"]').val(data.icon);
            $('[name="url_menu"]').val(data.url_menu);
            $('.modal-title').text('Edit Menu');
            $('#modal-menu').modal('show');
        }
    });
  }

  function validation_menu() {
    $('#form-menu')
    .bootstrapValidator({
      excluded: ':disabled',
      fields: {
        menu: {
          validators: {
              notEmpty: {
                  message: 'Menu harus diisi'
              },
          }
        },
        icon: {
          validators: {
              notEmpty: {
                  message: 'Icon harus diisi'
              },
          }
        },
      }
    })
    .on('success.form.bv', function(e) {
        e.preventDefault();
        var form = "#form-menu";
        form_data(form);
    });
  }

  function action_success() {
    $('#modal-menu').modal('hide');
    $('#modal-sub-menu').modal('hide');
    table.ajax.reload();
    load_menu();
  }

  function add_sub_menu() {
    $('#form-sub-menu')[0].reset();
    $('#form-sub-menu').data('bootstrapValidator').resetForm();
    $('.modal-title').text('Tambah Sub Menu');
    $('#modal-sub-menu').modal('show');
  }

  function edit_sub_menu(sub_menu_id) {
    $('#form-sub-menu')[0].reset();
    $('#form-sub-menu').data('bootstrapValidator').resetForm();
    $.ajax({
        url : base_url + "getDataSubMenu/" + sub_menu_id,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $('[name="sub_menu_id"]').val(sub_menu_id);
            $('[name="sub_menu"]').val(data.sub_menu);
            $('[name="id_menu"]').val(data.menu_id).select2();
            $('[name="url"]').val(data.url);
            $('.modal-title').text('Edit Sub Menu');
            $('#modal-sub-menu').modal('show');
        }
    });
  }

  function load_menu() {
      $('#id_menu').find('option').not(':first').remove();

      $.getJSON(base_url + "getMenu", function (data) {
          var option = [];
          for (let i = 0; i < data.length; i++) {
              option.push({
                  id: data[i].menu_id,
                  text: data[i].menu
              });
          }
          $('#id_menu').select2({
              data: option
          })
      });
  }

  function validation_sub_menu() {
    $('#form-sub-menu')
    .bootstrapValidator({
      excluded: ':disabled',
      fields: {
        id_menu: {
          validators: {
              notEmpty: {
                  message: 'Menu harus diisi'
              },
          }
        },
        sub_menu: {
          validators: {
              notEmpty: {
                  message: 'Sub Menu harus diisi'
              },
          }
        },
        url: {
          validators: {
              notEmpty: {
                  message: 'Url harus diisi'
              },
          }
        },
      }
    })
    .on('success.form.bv', function(e) {
        e.preventDefault();
        var form   = "#form-sub-menu";
        form_data(form);
    });
  }



</script>