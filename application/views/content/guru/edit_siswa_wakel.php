<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
  	<li><a href="<?= site_url() ?>"><i class="fa fa-folder-open"></i> <?= $folder ?></a></li>
  	<li><a href="javascript:void(0)" onclick="btn_back();"><?= $title ?></a></li>
  	<li class="active"><?= $header ?></li>
  </ol>
</section> 
<section class="content">
   	<div class="box">
   		<div class="box-header with-border">
   			<h3 class="box-title">Edit Siswa</h3>
   		</div>
   		<form action="<?= site_url('user/save/' . $id) ?>" method="post" id="form" enctype="multipart/form-data">
	   		<div class="box-body">
		   		<div class="row">
		   			<input type="text" name="id_kelas" value="<?= @$row->id_kelas ?>" style="display: none;">
		   			<div class="col-md-6 col-xs-12">
		   				<div class="form-group">
		   				  <label for="no_induk">Nomor Induk Siswa (NIS)</label>
		   				  <input type="text" class="form-control" id="no_induk" name="no_induk" placeholder="Nomor Induk Siswa (NIS)" autocomplete="off" value="<?= @$row->no_induk ?>">
		   				  <small class="help-block"></small>
		   				</div>
		   				<div class="form-group">
		   				  <label for="full_name">Nama Lengkap</label>
		   				  <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nama Lengkap" autocomplete="off" value="<?= @$row->full_name ?>">
		   				</div>
		   				<div class="form-group">
		   				  <label for="gender">Jenis Kelamin</label>
		   				  <select name="gender" id="gender" class="form-control select2">
		   				  	<option value="">Jenis Kelamin</option>
		   				  	<?php foreach ($this->include->gender() as $key => $value): ?>
		   				  		<option value="<?= $key ?>" <?php if($key == @$row->gender) echo 'selected'; ?>><?= $value ?></option>
		   				  	<?php endforeach ?>
		   				  </select>
		   				  <small class="help-block"></small>
		   				</div>
		   				<div class="form-group">
		   				  <label for="tempat_lahir">Tempat Lahir</label>
		   				  <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" autocomplete="off" value="<?= @$row->tempat_lahir ?>">
		   				  <small class="help-block"></small>
		   				</div>
		   				<div class="form-group">
		   				  <label for="tanggal_lahir">Tanggal Lahir</label>
		   				  <input type="text" class="form-control" id="datepicker" name="tanggal_lahir" placeholder="Tanggal Lahir" autocomplete="off" value="<?php if(@$row->tanggal_lahir) echo date('m/d/Y', strtotime(@$row->tanggal_lahir)) ?>">
			   				<small class="help-block"></small>
		   				</div>
		   				<div class="form-group">
		   				  <label for="alamat">Alamat</label>
		   				  <textarea name="alamat" id="alamat" class="form-control" placeholder="Alamat"><?= @$row->alamat ?></textarea>
		   				  <small class="help-block"></small>
		   				</div>
		   				<!-- <div class="form-group">
		   				  <label for="agama">Agama</label>
		   				  <select name="agama" id="agama" class="form-control select2">
		   				  	<option value="">Agama</option>
		   				  	<?php foreach ($this->include->agama() as $key): ?>
		   				  		<option value="<?= $key ?>" <?php if($key == @$agama) echo 'selected'; ?>><?= $key ?></option>
		   				  	<?php endforeach ?>
		   				  </select>
		   				  <small class="help-block"></small>
		   				</div> -->
		   			</div>
		   			<div class="col-md-6 col-xs-12">

		   				<div class="form-group">
		   				  <label for="is_aktif">Status</label>
		   				  <select name="is_aktif" id="is_aktif" class="form-control select2">
		   				  	<option value="">Status</option>
		   				  	<?php foreach ($this->include->statusSiswa() as $key => $value): ?>
		   				  		<option value="<?= $key ?>" <?php if($key == @$row->is_aktif) echo 'selected'; ?>><?= $value ?></option>
		   				  	<?php endforeach ?>
		   				  </select>
		   				  <small class="help-block"></small>
		   				</div>

		   				<div class="form-group">
		   				  <label for="profile_pic">Foto</label>
			   				<input type="file" name="profile_pic" style="display: none;">

		   				  	<?php if (!@$row->profile_pic): ?>
				   				 <button type="button" id="btn-pp" class="btn btn-flat btn-default form-control" style="text-align: left; background-color: #FFFFFF; border: 1px solid #D2D6DE;">Foto</button>
	   				  		<?php else: ?>
				   				 <button type="button" class="btn btn-flat btn-default form-control" onclick="preview_foto();" style="text-align: left; background-color: #FFFFFF; border: 1px solid #D2D6DE;">Foto</button>
		   				  	<?php endif ?>

		   				 <small class="help-block"></small>
		   				</div>
		   				<div class="form-group">
		   				  <label for="email">Email</label>
		   				  <input type="text" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off" value="<?= @$row->email ?>">
		   				  <small class="help-block"></small>
		   				</div>
		   				<div class="form-group">
		   				  <label for="phone">No. Handphone</label>
		   				  <input type="text" class="form-control" id="phone" name="phone" placeholder="No. Handphone" autocomplete="off" value="<?= @$row->phone ?>">
		   				  <small class="help-block"></small>
		   				</div>
		   				<div class="form-group">
		   				  <label for="password1">Password</label>
		   				  <input type="password" class="form-control" id="password1" name="password1" placeholder="Password" autocomplete="off">
		   				  <small class="help-block"></small>
		   				</div>
		   				<div class="form-group">
							<label for="password2">Konfirmasi Password</label>
							<input type="password" class="form-control" id="password2" name="password2" placeholder="Konfirmasi Password (Ulangi)" autocomplete="off">
							<small class="help-block"></small>
	   				    </div>
		   			</div>
		   			<!-- <div class="col-md-12 col-xs-12">
		   				<div class="form-group">
		   				  <label for="alamat">Alamat</label>
		   				  <textarea name="alamat" id="alamat" class="form-control" placeholder="Alamat"><?= @$row->alamat ?></textarea>
		   				  <small class="help-block"></small>
		   				</div>
		   				<?php if (!@$row->user_id): ?>
			   				<div class="form-group">
			   					<small style="color: #333333;">Keterangan : jika password tidak diisi maka dibuat dari tanggal lahir dengan format <?= date('dmY') ?></small>
			   				</div>
		   				<?php endif ?>
		   			</div> -->
		   		</div>
	   		</div>
	   		<div class="box-footer">
	   			<?= BTN_CANCEL . BTN_SUBMIT ?>
	   		</div>
   		</form>
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
      <form action="<?= site_url('user/changeFoto/' . $id) ?>" id="form-foto" method="post" enctype="multipart/form-data">
      	<input type="file" name="foto" onchange="edit_image();" style="display: none;">
        <div class="modal-body">
          <div class="form-group">
          	<img class="img-responsive" id="image" src="" alt="Photo" style="display: block; margin-left: auto; margin-right: auto; max-width: 270px; max-height: 200px; display: none; width: 100%; height: 100%;">
          	<button type="button" id="upload-gambar" class="btn btn-flat btn-default form-control edit-foto" style="font-weight: bold; display: none;"><i class="fa fa-image"></i> Upload Foto</button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm pull-left" style="background-color: #DC3545; color: #FFFFFF; font-weight: bold; font-family: serif;" onclick="delete_image()"><i class="fa fa-times"></i> Hapus</button>
          <button type="button" class="btn btn-sm edit-foto" style="background-color: #FFC107; color: #FFFFFF; font-weight: bold; font-family: serif;"><i class="fa fa-image"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		form_validation();
		get_image();

		$('#btn-pp').click(function() {
			$('[name="profile_pic"]').click();
		});

		$('[name="profile_pic"]').change(function() {
			$('#btn-pp').css("border", "1px solid #00A65A");
		});

		$('.edit-foto').click(function() {
			$('[name="foto"]').click();
		});

		$('[name="tanggal_lahir"]').change(function() {
			if ($(this).val()) {
				$('#btn-save').removeAttr('disabled');
				$('[name="tanggal_lahir"]').closest('.form-group').removeClass('has-error').addClass('has-success');
				$('[name="tanggal_lahir"]').nextAll('.help-block').eq(0).text('');
			} else {
				$('#btn-save').attr('disabled', true);
				$('[name="tanggal_lahir"]').closest('.form-group').addClass('has-error');
				$('[name="tanggal_lahir"]').nextAll('.help-block').eq(0).text('Tanggal Lahir harus diisi');
			}
		});

	});

	function form_validation() {
		$('#form')
		.bootstrapValidator({
		  excluded: ':disabled',
		  fields: {
		  	no_induk: {
		  	  validators: {
		  	      notEmpty: {
		  	          message: 'NIS harus diisi'
		  	      },
		  	      numeric: {
		  	        message: 'NIS tidak valid'
		  	      },
		  	  }
		  	},
		    full_name: {
		      validators: {
		          notEmpty: {
		              message: 'Nama Lengkap harus diisi'
		          },
		      }
		    },
		    gender: {
		      validators: {
		          notEmpty: {
		              message: 'Jenis Kelamin harus diisi'
		          },
		      }
		    },
		    tempat_lahir: {
		      validators: {
		          notEmpty: {
		              message: 'Tempat Lahir harus diisi'
		          },
		      }
		    },
		    is_aktif: {
		      validators: {
		          notEmpty: {
		              message: 'Status harus diisi'
		          },
		      }
		    },
		    // agama: {
		    //   validators: {
		    //       notEmpty: {
		    //           message: 'Agama harus diisi'
		    //       },
		    //   }
		    // },
		    email: {
		      validators: {
		          // notEmpty: {
		          //     message: 'Email harus diisi'
		          // },
		          emailAddress: {
                      message: 'Email tidak valid'
                  }
		      }
		    },
		    phone: {
		      validators: {
		          // notEmpty: {
		          //     message: 'No. Handphone harus diisi'
		          // },
		          numeric: {
		            message: 'No. Handphone tidak valid'
		          },
		      }
		    },
		    password1: {
		      validators: {
		          // notEmpty: {
		          //     message: 'Password harus diisi'
		          // },
		      }
		    },
		    password2: {
		      validators: {
		          identical: {
		              field: 'password1',
		              message: 'Konfirmasi Password salah'
		          }
		      }
		    },
		  }
		})
		.on('success.form.bv', function(e) {
		    e.preventDefault();
		    submit_form();
		});
	}

	function submit_form() {
		var tanggal = $('[name="tanggal_lahir"]').val();
		if (tanggal) {
			form_data();
		} else {
			$('#form').data('bootstrapValidator').resetForm();
			$('[name="tanggal_lahir"]').closest('.form-group').addClass('has-error');
			$('[name="tanggal_lahir"]').nextAll('.help-block').eq(0).text('Tanggal Lahir harus diisi');
		}
	}

	function action_success() {
		setTimeout(function(){ 
		    btn_back();
		}, 1575);
	}

	function btn_back() {
		window.history.back();
	}

	function reset_validator() {
		$('#form')[0].reset();
		$('.select2').val('').trigger('change');
		$('#form').data('bootstrapValidator').resetForm();
	}

	function get_image() {
		$.ajax({
		    url: "<?= site_url('user/getImage/' . $id) ?>",
		    type: "GET",
		    dataType: "JSON",
		    success: function(response) {
		      if (response.status) {
		      	$('#image').attr('src', response.url);
		      	if (response.profile_pic) {
		      		$('#image').show();
		      		$('#upload-gambar').hide();
		      		$('.modal-footer').show();
		      	} else {
		      		$('#image').hide();
		      		$('#upload-gambar').show();
		      		$('.modal-footer').hide();
		      	}
		      } 
		    }
		});
	}

	function preview_foto() {
		$('.modal-title').text('Foto');
		$('#modal-form').modal('show');
	}

	function edit_image() {
		var form = $('#form-foto');
		$.ajax({
		    url: form.attr('action'),
		    type: "POST",
		    data: new FormData(form[0]),
		    processData:false,
		    contentType:false,
		    dataType: "JSON",
		    success: function (response) {
		    	Pace.restart();
	    		get_image();
		    }
		});
	}

	function delete_image() {
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
	      $.ajax({
	          url: "<?= site_url('user/deleteFoto/' . $id) ?>",
	          type: "GET",
	          dataType: "JSON",
	          success: function(response) {
  		    	Pace.restart();
  	    		get_image();
	          }
	      });

	    }
	  })
	}
</script>