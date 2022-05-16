<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
  	<li><a href="<?= site_url() ?>"><i class="fa fa-home"></i> <?= $folder ?></a></li>
  	<li><a href="javascript:void(0)" onclick="return window.history.back();"><?= $title ?></a></li>
  	<li class="active"><?= $header ?></li>
  </ol>
</section>
<section class="content">
	<div class="box">
		<div class="box-header with-border">
		  <h3 class="box-title"><?= $header ?> <?= $title ?></h3>
		</div>
		<div class="row">
			<div class="col-md-6 col-lg-offset-3 col-xs-12">
				<form action="<?= site_url('student/saveProfile/' . md5($row->user_id)) ?>" method="post" id="form" enctype="multipart/form-data">
					<div class="box-body">
						<div class="form-group">
						  <label for="full_name">Nama</label>
						  <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nama" autocomplete="off" value="<?= @$row->full_name ?>">
						  <small class="help-block"></small>
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
						<!-- <div class="form-group">
						  <label for="agama">Agama</label>
						  <select name="agama" id="agama" class="form-control select2">
						  	<option value="">Agama</option>
						  	<?php foreach ($this->include->agama() as $key): ?>
						  		<option value="<?= $key ?>" <?php if($key == @$row->agama) echo 'selected'; ?>><?= $key ?></option>
						  	<?php endforeach ?>
						  </select>
						  <small class="help-block"></small>
						</div> -->
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
						  <label for="alamat">Alamat</label>
						  <textarea name="alamat" id="alamat" class="form-control" placeholder="Alamat"><?= @$row->alamat ?></textarea>
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

<script type="text/javascript">
	$(document).ready(function() {
		$('.select2').select2();
		$('#datepicker').datepicker({
		  autoclose: true
		});

		form_validation();

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
		    full_name: {
		      validators: {
		          notEmpty: {
		              message: 'Nama harus diisi'
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
		  }
		})
		.on('success.form.bv', function(e) {
		    e.preventDefault();
		    validate_form();
		});
	}

	function validate_form() {
		var tanggal = $('[name="tanggal_lahir"]').val();
		if (tanggal) {
			submit_form();
		} else {
			$('#form').data('bootstrapValidator').resetForm();
			$('[name="tanggal_lahir"]').closest('.form-group').addClass('has-error');
			$('[name="tanggal_lahir"]').nextAll('.help-block').eq(0).text('Tanggal Lahir harus diisi');
		}
	}

	function submit_form() {
		$.ajax({
		    url: $('#form').attr('action'),
		    type: "POST",
		    data: new FormData($('#form')[0]),
		    contentType: false,
		    processData: false,
		    dataType: "JSON",
		    success: function(response) {
		      if (response.status) {
		        Pace.restart();
		        if (response.message) {
			        setTimeout(function() {
			        	btn_back();
			        }, 1575);
		        	Swal.fire({
		        	  type: 'success',
		        	  title: '<span style="font-weight: bold; color: #595959; font-size: 16px; font-family: serif;">' + response.message + '</span>',
		        	  showConfirmButton: false,
		        	  timer: 1500
		        	});
		        } else {
		        	btn_back();
		        }
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
		window.location.href = "<?= site_url('student') ?>";
	}
</script>