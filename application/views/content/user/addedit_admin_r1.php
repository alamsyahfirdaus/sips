<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
  	<li><a href="<?= site_url() ?>"><i class="fa fa-users"></i> <?= $folder ?></a></li>
  	<li><a href="javascript:void(0)" onclick="btn_back();"><?= $title ?></a></li>
  	<li class="active"><?= $header ?></li>
  </ol>
</section> 
<section class="content">
   	<div class="row">
   		<div class="col-md-6 col-xs-12">
		   	<div class="box">
		   		<div class="box-header with-border">
		   			<h3 class="box-title"><?= $header .' '. $title ?></h3>
		   			<div class="box-tools pull-right"></div>
		   		</div>
		   		<form action="<?= site_url('user/save/' . @$id) ?>" method="post" id="form" enctype="multipart/form-data">
			   		<div class="box-body">
			   			<input type="text" name="user_type_id" value="<?php if(empty($row->user_id)) echo $user_type_id ?>" style="display: none;">
			   			<div class="form-group">
			   			  <label for="no_induk">NUPTK</label>
			   			  <input type="text" class="form-control" id="no_induk" name="no_induk" placeholder="NUPTK" autocomplete="off" value="<?= @$row->no_induk ?>">
			   			  <small class="help-block"></small>
			   			</div>
			   			<div class="form-group">
			   			  <label for="full_name">Nama Lengkap</label>
			   			  <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nama Lengkap" autocomplete="off" value="<?= @$row->full_name ?>">
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
		   				  <label for="profile_pic">Foto</label>
			   				<input type="file" name="profile_pic" style="display: none;">

		   				  	<?php if (!@$row->profile_pic): ?>
				   				 <button type="button" id="btn-pp" class="btn btn-flat btn-default form-control" style="text-align: left; background-color: #FFFFFF; border: 1px solid #D2D6DE;">Upload Foto</button>
	   				  		<?php else: ?>
				   				 <button type="button" class="btn btn-flat btn-default form-control" onclick="preview_foto();" style="text-align: left; background-color: #FFFFFF; border: 1px solid #D2D6DE;"><?= isset($row->profile_pic) ? 'Lihat Foto' : 'Upload Foto' ?></button>
		   				  	<?php endif ?>

		   				 <small class="help-block"></small>
		   				</div>
		   				<div class="form-group">
		   				  <label for="password1">Password</label>
		   				  <input type="password" class="form-control" id="password1" name="password1" placeholder="Password <?php if(empty($row->user_id)) echo '(Default: NUPTK)' ?>" autocomplete="off">
		   				  <small class="help-block"></small>
		   				</div>
		   				<div class="form-group">
		   					<label for="password2">Konfirmasi Password</label>
		   					<input type="password" class="form-control" id="password2" name="password2" placeholder="Konfirmasi Password (Ulangi)" autocomplete="off">
		   					<small class="help-block"></small>
		   				</div>
			   		</div>
			   		<div class="box-footer">
			   			<?= BTN_CANCEL . BTN_SUBMIT ?>
			   		</div>
		   		</form>
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
      <form action="<?= site_url('user/changeFoto/' . @$id) ?>" id="form-foto" method="post" enctype="multipart/form-data">
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

	});

	function form_validation() {
		$('#form')
		.bootstrapValidator({
		  excluded: ':disabled',
		  fields: {
		  	no_induk: {
		  	  validators: {
		  	      notEmpty: {
		  	          message: 'NUPTK harus diisi'
		  	      },
		  	      numeric: {
		  	        message: 'NUPTK tidak valid'
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
		    email: {
		      validators: {
		          notEmpty: {
		              message: 'Email harus diisi'
		          },
		          emailAddress: {
                  message: 'Email tidak valid'
              }
		      }
		    },
		    phone: {
		      validators: {
		          notEmpty: {
		              message: 'No. Handphone harus diisi'
		          },
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
	          stringLength: {
	              min: 6,
	              message: 'Password minimal 6 karakter'
	          }
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
		    form_data();
		});
	}

	function action_success() {
		setTimeout(function(){ 
		    btn_back();
		}, 1575);
	}

	function btn_back() {
		window.location.href = index + 'user/administration';
	}

		function get_image() {
			$.ajax({
			    url: "<?= site_url('user/getImage/' . @$id) ?>",
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
		          url: "<?= site_url('user/deleteFoto/' . @$id) ?>",
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