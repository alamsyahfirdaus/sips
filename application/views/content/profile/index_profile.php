<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-home"></i> <?= $title ?></a></li>
    <li class="active"><?= $sub_title ?></li>
  </ol>
</section>
<section class="content">
	<div class="row">
    <div class="col-md-6">
      <div class="box">
      	<div class="box-header with-border">
      	  <h3 class="box-title">Profile</h3>
      	  <input type="hidden" name="title" value="<?= $title ?>">
      	  <div class="box-tools pull-right">
    	      <div class="btn-group">
    	        <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
    	          <i class="fa fa-cogs"></i></button>
    	        <ul class="dropdown-menu" role="menu">
    	          <li><a href="<?= site_url('home/update') ?>">Edit Profile</a></li>
                <li class="divider"></li>
                <li><a href="javascript:void(0)" onclick="edit_password();">Edit Password</a></li>
                <li class="divider"></li>
                <li><a href="javascript:void(0)" id="edit-foto">Edit Foto</a></li>
                <?php if (@$row->profile_pic): ?>
      	          <li class="divider"></li>
      	          <li><a href="javascript:void(0)" onclick="delete_foto();">Hapus Foto</a></li>
                <?php endif ?>
                <?php if (count($guru_piket) > 0): ?>
                  <li class="divider"></li>
                  <li><a href="<?= site_url('role') ?>">Pindah Role</a></li>
                <?php endif ?>
    	        </ul>
    	      </div>
      	  </div>
      	</div>
        <div class="box-body box-profile">
          <?php if (isset($row->profile_pic)): ?>
          <img class="profile-user-img img-responsive img-circle" src="<?= base_url(IMAGE . $this->include->image(@$row->profile_pic)) ?>" alt="User profile picture">
          <?php else: ?>
            <?php
            $full_name = explode(' ', $row->full_name);
            $foto_profile = isset($full_name[0]) ? substr(strtoupper($full_name[0]), 0, 1) : '';
            $foto_profile .= isset($full_name[1]) ? substr(strtoupper($full_name[1]), 0, 1) : '';
            ?>
            <table class="table">
              <tr><td style="border-top: none; text-align: center;">
                  <span class="foto_profile" style="width: 100px; height: 100px; font-size: 28px; border: 1px solid #00A65A;"><?= $foto_profile ?></span>
                </td></tr>
            </table>
          <?php endif ?>
          <h3 class="profile-username text-center"><?= $row->full_name ?></h3>
          <!-- <p class="text-muted text-center"><?= $row->type_name ?></p> -->
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>NUPTK</b> <a class="pull-right"><?= $this->include->null(@$row->no_induk) ?></a>
            </li>
            <?php if (@$row->user_type_id == 2): ?>
              <li class="list-group-item">
                <b>Jenis Kelamin</b> <a class="pull-right"><?= $row->gender == 'L' ? 'Laki-Laki' : 'Perempuan' ?></a>
              </li>
              <li class="list-group-item">
                <b>Tempat/Tgl Lahir</b> <a class="pull-right"><?= $row->tempat_lahir . ', ' . date('d-m-Y', strtotime($row->tanggal_lahir)) ?></a>
              </li>
              <li class="list-group-item">
                <b>Status Kepegawaian</b> <a class="pull-right"><?= $row->status_guru ?></a>
              </li>
            <?php endif ?>
            <li class="list-group-item">
              <b>Email</b> <a class="pull-right"><?= $this->include->null($row->email) ?></a>
            </li>
            <li class="list-group-item">
              <b>No. Handphone</b> <a class="pull-right"><?= $this->include->null($row->phone) ?></a>
            </li>
            <?php if (@$row->user_type_id == 2): ?>
              <li class="list-group-item">
                <div class="form-group">
                  <label for="">Alamat</label>
                  <textarea name="" id="" class="form-control" disabled=""><?= $this->include->null($row->alamat) ?></textarea>
                </div>
              </li>
            <?php endif ?>
          </ul>
        </div>
        <div class="box-footer">
        	<p class="text-center">
        	   <small class="text-muted">Registrasi Tanggal, <?= $this->include->date($row->date_created) ?></small>
        	 </p>
        </div>
      </div>
  	</div>
	</div>
</section>

<div class="modal fade" id="modal-form">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <form action="<?= site_url('home/changePassword') ?>" method="post" id="form">
        <div class="modal-body">
          <div class="form-group">
            <label for="old_password">Password Lama</label>
            <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Password Lama" autocomplete="off">
            <small class="help-block"></small>
          </div>
          <div class="form-group">
            <label for="new_password1">Password Baru</label>
            <input type="password" class="form-control" id="new_password1" name="new_password1" placeholder="Password Baru" autocomplete="off">
            <small class="help-block"></small>
          </div>
          <div class="form-group">
            <label for="new_password2">Konfirmasi Password</label>
            <input type="password" class="form-control" id="new_password2" name="new_password2" placeholder="Konfirmasi Password (Ulangi)" autocomplete="off">
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

<form action="<?= site_url('user/changeFoto/' . md5(@$row->user_id)) ?>" id="form-foto" method="post" enctype="multipart/form-data" style="display: none;">
  <input type="file" name="foto">
  <input type="text" name="action" value="home/profile">
</form>

<form action="<?= site_url('user/deleteFoto/' . md5(@$row->user_id)) ?>" id="delete-foto" method="post" style="display: none;">
  <input type="text" name="action" value="home/profile">
</form>

<script type="text/javascript">

	$(document).ready(function() {
		form_validation();

    $("#edit-foto").click(function() {
        $('[name="foto"]').click();
    });

    $('[name="foto"]').on('change', function() {
      if ($(this).val() != '') {
        $("#form-foto").submit();
      }
    });

	});
	
	function edit_password() {
		$('#form')[0].reset();
		$('#form').data('bootstrapValidator').resetForm();
		$('.modal-title').text('Edit Password');
		$('#modal-form').modal('show');
	}

	function form_validation() {
	  $('#form')
	  .bootstrapValidator({
	    excluded: ':disabled',
	    fields: {
	      old_password: {
	        validators: {
	            notEmpty: {
	                message: 'Password Lama harus diisi'
	            },
	        }
	      },
	      new_password1: {
	        validators: {
	            notEmpty: {
	                message: 'Password Baru harus diisi'
	            },
	        }
	      },
	      new_password2: {
	        validators: {
	            notEmpty: {
	                message: 'Konfirmasi Password harus diisi'
	            },
	            identical: {
	                field: 'new_password1',
	                message: 'Konfirmasi Password salah'
	            }
	        }
	      },
	    }
	  })
	  .on('success.form.bv', function(e) {
	      e.preventDefault();
	      form_data();
	  });
	}

	function action_success() {
		$('#modal-form').modal('hide');
	}

  function delete_foto() {
    Swal.fire({
      title: '<span style="font-family: serif;">Apakah anda yakin?</span>',
      text: 'Akan menghapus Foto',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#00A65A',
      cancelButtonColor: '#6C757D',
      confirmButtonText: '<span style="font-family: serif;"><i class="fa fa-angle-double-right"></i> Ya</span>',
      cancelButtonText: '<span style="font-family: serif;"><i class="fa fa-angle-double-left"></i> Tidak</span>',
      reverseButtons: true,
    }).then((result) => {
      if (result.value) {
        $("#delete-foto").submit();
      }
    })
  }
</script>