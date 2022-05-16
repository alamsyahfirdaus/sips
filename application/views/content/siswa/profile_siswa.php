<section class="content-header">
  <h1><?= @$title ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-home"></i> <?= @$folder ?></a></li>
    <li class="active"><?= @$title ?></li>
  </ol>
</section>
<section class="content">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">Biodata Siswa</h3>
			<!-- <div class="box-tools pull-right">
			  <a href="<?= site_url('home') ?>" class="btn btn-box-tool" data-toggle="tooltip"><i class="fa fa-times"></i></a>
			</div> -->
		</div>
		<div class="row">
			<div class="col-md-3 col-xs-12">
				<div class="box-body box-profile">
					<?php if (isset($row->profile_pic)): ?>
					  <img class="img-responsive" src="<?= base_url(IMAGE . $this->include->image(@$row->profile_pic)) ?>" alt="User profile picture" style="display: block; margin-left: auto; margin-right: auto; max-width: 235px; max-height: 145px; border: 1px solid #DDDDDD; width: 100%; height: 100%;">
						<?php else: ?>
							<?php
							$full_name = explode(' ', $row->full_name);
							$foto_profile = isset($full_name[0]) ? substr(strtoupper($full_name[0]), 0, 1) : '';
							$foto_profile .= isset($full_name[1]) ? substr(strtoupper($full_name[1]), 0, 1) : '';
							?>
							<span id="foto_profile" style="width: 235px; height: 145px; font-size: 75px; border: 1px solid #DDDDDD;"><?= $foto_profile ?></span>
							<style type="text/css">
								#foto_profile{
								  background: #ffffff;
								  color: #00A65A;
								  display: inline-flex;
								  align-items: center;
								  justify-content: center;
								  font-weight: bold;
								}
							</style>
					<?php endif ?>
				  <hr style="border-top: 1px solid #DDDDDD;">
				  <a href="javascript:void(0)" id="edit-foto" class="btn btn-default btn-block btn-sm btn-social" style="font-weight: bold;"><i class="fa fa-image"></i> Edit Foto</a>
				  <br>
				  <a href="<?= site_url('student/edit') ?>" class="btn btn-default btn-block btn-sm btn-social" style="font-weight: bold;"><i class="fa fa-edit"></i> Edit Profile</a>
				  <br>
				  <a href="javascript:void(0)" onclick="edit_password();" class="btn btn-default btn-block btn-sm btn-social" style="font-weight: bold;"><i class="fa fa-key"></i> Edit Password</a>

				  <?php if (@$row->profile_pic): ?>
					  <br>
					  <a href="javascript:void(0)" onclick="delete_foto();" class="btn btn-default btn-block btn-sm btn-social" style="font-weight: bold;"><i class="fa fa-trash"></i> Hapus Foto</a>
				  <?php endif ?>

				</div>
			</div>
			<div class="col-md-9 col-xs-12">
				<div class="box-body box-profile">
					<ul class="list-group list-group-unbordered">
					  <li class="list-group-item">
					    <b>Nomor Induk Siswa (NIS)</b> <a class="pull-right"><?= $this->include->null(@$row->no_induk) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>Nama Lengkap</b> <a class="pull-right"><?= $this->include->null(@$row->full_name) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>Jenis Kelamin</b> <a class="pull-right"><?= @$row->gender == 'L' ? 'Laki-laki' : 'Perempuan' ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>Tempat/Tgl Lahir</b> <a class="pull-right"><?= @$row->tempat_lahir . ', ' . date('d-m-Y', strtotime(@$row->tanggal_lahir)) ?></a>
					  </li>
					  <!-- <li class="list-group-item">
					    <b>Agama</b> <a class="pull-right"><?= $this->include->null(@$row->agama) ?></a>
					  </li> -->
					  <li class="list-group-item">
					    <b>Kelas</b> <a class="pull-right"><?= $this->include->null(@$row->nama_kelas) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>Email</b> <a class="pull-right"><?= $this->include->null(@$row->email) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>No. Handphone</b> <a class="pull-right"><?= $this->include->null(@$row->phone) ?></a>
					  </li>
					  <li class="list-group-item">
					    <div class="form-group">
					      <label for="">Alamat</label>
					      <textarea name="" id="" class="form-control" disabled=""><?= $this->include->null(@$row->alamat) ?></textarea>
					    </div>
					  </li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">Biodata Orang Tua/Wali</h3>
			<!-- <div class="box-tools pull-right">
			  <a href="<?= site_url('student/parents') ?>" class="btn btn-box-tool" data-toggle="tooltip"><i class="fa fa-edit"></i></a>
			</div> -->
		</div>
		<div class="row">
			<div class="col-md-4 col-xs-12">
				<div class="box-body box-profile">
					<ul class="list-group list-group-unbordered">
					  <li class="list-group-item">
					    <b>Nama Ayah</b> <a class="pull-right"><?= $this->include->null(@$row->nama_ayah) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>Pendidikan Terakhir</b> <a class="pull-right"><?= $this->include->null(@$row->pendidikan_ayah) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>Pekerjaan</b> <a class="pull-right"><?= $this->include->null(@$row->pekerjaan_ayah) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>Penghasilan</b> <a class="pull-right"><?= $this->include->null(@$row->penghasilan_ayah) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>No. Handphone</b> <a class="pull-right"><?= $this->include->null(@$row->no_hp_ayah) ?></a>
					  </li>
					  <li class="list-group-item">
					    <div class="form-group">
					      <label for="">Alamat</label>
					      <textarea name="" id="" class="form-control" disabled=""><?= $this->include->null(@$row->alamat_ayah) ?></textarea>
					    </div>
					  </li>
					</ul>
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="box-body box-profile">
					<ul class="list-group list-group-unbordered">
					  <li class="list-group-item">
					    <b>Nama Ibu</b> <a class="pull-right"><?= $this->include->null(@$row->nama_ibu) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>Pendidikan Terakhir</b> <a class="pull-right"><?= $this->include->null(@$row->pendidikan_ibu) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>Pekerjaan</b> <a class="pull-right"><?= $this->include->null(@$row->pekerjaan_ibu) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>Penghasilan</b> <a class="pull-right"><?= $this->include->null(@$row->penghasilan_ibu) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>No. Handphone</b> <a class="pull-right"><?= $this->include->null(@$row->no_hp_ibu) ?></a>
					  </li>
					  <li class="list-group-item">
					    <div class="form-group">
					      <label for="">Alamat</label>
					      <textarea name="" id="" class="form-control" disabled=""><?= $this->include->null(@$row->alamat_ibu) ?></textarea>
					    </div>
					  </li>
					</ul>
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="box-body box-profile">
					<ul class="list-group list-group-unbordered">
					  <li class="list-group-item">
					    <b>Nama Wali</b> <a class="pull-right"><?= $this->include->null(@$row->nama_wali) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>Pendidikan Terakhir</b> <a class="pull-right"><?= $this->include->null(@$row->pendidikan_wali) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>Pekerjaan</b> <a class="pull-right"><?= $this->include->null(@$row->pekerjaan_wali) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>Penghasilan</b> <a class="pull-right"><?= $this->include->null(@$row->penghasilan_wali) ?></a>
					  </li>
					  <li class="list-group-item">
					    <b>No. Handphone</b> <a class="pull-right"><?= $this->include->null(@$row->no_hp_wali) ?></a>
					  </li>
					  <li class="list-group-item">
					    <div class="form-group">
					      <label for="">Alamat</label>
					      <textarea name="" id="" class="form-control" disabled=""><?= $this->include->null(@$row->alamat_wali) ?></textarea>
					    </div>
					  </li>
					</ul>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<?= BTN_CANCEL ?>
			<a href="<?= site_url('student/parents') ?>" class="btn btn-warning btn-sm pull-right" style="font-weight: bold;"><i class="fa fa-edit"></i> Edit</a>
		</div>
	</div>
</section>

<form action="<?= site_url('student/editFoto/' . md5(@$row->user_id)) ?>" id="form-foto" method="post" enctype="multipart/form-data" style="display: none;">
	<input type="file" name="foto">
</form>

<div class="modal fade" id="modal-form">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <form action="<?= site_url('student/editPassword/' . md5(@$row->user_id)) ?>" method="post" id="form-password">
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

<script type="text/javascript">
	$(document).ready(function() {

		$('#edit-foto').click(function() {
			$('[name="foto"]').click();
		});

		$('[name="foto"]').change(function() {
			if ($(this).val()) {
			  $("#form-foto").submit();
			}
		});

		form_password();
	});

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
	      window.location.href = "<?= site_url('student/deleteFoto/' . md5(@$row->user_id)) ?>";
	    }
	  })
	}

	function edit_password() {
		$('#form-password')[0].reset();
		$('#form-password').data('bootstrapValidator').resetForm();
		$('.modal-title').text('Edit Password');
		$('#modal-form').modal('show');
	}

	function form_password() {
	  $('#form-password')
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
	      submit_password();
	  });
	}

	function submit_password() {
		$.ajax({
		    url: $('#form-password').attr('action'),
		    type: "POST",
		    data: new FormData($('#form-password')[0]),
		    contentType: false,
		    processData: false,
		    dataType: "JSON",
		    success: function(response) {
		      if (response.status) {
		        Pace.restart();
		        $('#modal-form').modal('hide');
		        Swal.fire({
		          type: 'success',
		          title: '<span style="font-weight: bold; color: #595959; font-size: 16px; font-family: serif;">' + response.message + '</span>',
		          showConfirmButton: false,
		          timer: 1500
		        });
		      } else {
		        $.each(response.errors, function (key, val) {
		            $('[name="' + key + '"]').closest('.form-group').addClass('has-error');
		            $('[name="' + key + '"]').nextAll('.help-block').eq(0).text(val);
		            if (val === '') {
		                $('[name="' + key + '"]').closest('.form-group').removeClass('has-error').addClass('has-success');
		                $('[name="' + key + '"]').nextAll('.help-block').eq(0).text('');
		            }

		            $('[name="' + key + '"]').on('keyup change', function () {
		              $('[name="' + key + '"]').closest('.form-group').removeClass('has-error').addClass('has-success');
		              $('[name="' + key + '"]').nextAll('.help-block').eq(0).text('');
		            });

		        });
		    }
		  }
		});
	}

	function btn_back() {
		window.location.href = "<?= site_url('home') ?>";
	}
</script>