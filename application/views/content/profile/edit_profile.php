<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-home"></i> <?= $title ?></a></li>
    <li><a href="javascript:void(0)" onclick="btn_back();"><?= $sub_title ?></a></li>
    <li class="active"><?= $header ?></li>
  </ol>
</section>
<section class="content">
	<div class="row">
	  <div class="col-md-6 col-xs-12">
	  	<div class="box">
	  		<div class="box-header">
	  			<h3 class="box-title"><?= $header ?></h3>
	  		</div>
	  		<form action="<?= site_url('home/saveProfile/' . md5(@$row->user_id)) ?>" method="post" id="form">
	  		  <div class="box-body">
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
		$('#form')
		.bootstrapValidator({
		  excluded: ':disabled',
		  fields: {
		  	no_induk: {
		  	  validators: {
		  	      // notEmpty: {
		  	      //     message: 'NUPTK harus diisi'
		  	      // },
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
		    agama: {
		      validators: {
		          notEmpty: {
		              message: 'Agama harus diisi'
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
		  }
		})
		.on('success.form.bv', function(e) {
		    e.preventDefault();
		    form_data();
		});
	});

	function action_success() {
		setTimeout(function(){ 
		    btn_back();
		}, 1575);
	}

	function btn_back() {
		window.location.href = index + 'home/profile';
	}
</script>