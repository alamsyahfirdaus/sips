<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
  	<li><a href="<?= site_url() ?>"><i class="fa fa-users"></i> <?= $folder ?></a></li>
  	<li><a href="javascript:void(0)" onclick="return window.history.back();"><?= $title ?></a></li>
  	<li class="active"><?= $header ?></li>
  </ol>
</section>
<section class="content">
	<div class="box">
		<div class="box-header">
			<h3 class="box-title">Edit Orang Tua/Wali</h3>
		</div>
		<form action="<?= site_url('user/saveOrangTuaWali/' . md5(@$row->siswa_id)) ?>" method="post" id="form">
			<div class="row">
				<div class="col-md-4 col-xs-12">
					<div class="box-body">
						<div class="form-group">
						  <label for="nama_ayah">Nama Ayah</label>
						  <input type="text" class="form-control" id="nama_ayah" name="nama_ayah" placeholder="Nama Ayah" autocomplete="off" value="<?= @$row->nama_ayah ?>">
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="pendidikan_ayah">Pendidikan Terakhir</label>
						  <select name="pendidikan_ayah" id="pendidikan_ayah" class="form-control select2">
						  	<option value="">Pendidikan Terakhir</option>
						  	<?php foreach ($pendidikan as $key): ?>
						  		<option value="<?= $key ?>" <?php if($key == @$row->pendidikan_ayah) echo "selected"; ?>><?= $key ?></option>
						  	<?php endforeach ?>
						  </select>
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="pekerjaan_ayah">Pekerjaan</label>
						  <select name="pekerjaan_ayah" id="pekerjaan_ayah" class="form-control select2">
						  	<option value="">Pekerjaan</option>
						  	<?php foreach ($pekerjaan as $key): ?>
						  		<option value="<?= $key ?>" <?php if($key == @$row->pekerjaan_ayah) echo "selected"; ?>><?= $key ?></option>
						  	<?php endforeach ?>
						  </select>
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="penghasilan_ayah">Pengasilan</label>
						  <select name="penghasilan_ayah" id="penghasilan_ayah" class="form-control select2">
						  	<option value="">Pengasilan</option>
						  	<?php foreach ($penghasilan as $key): ?>
						  		<option value="<?= $key ?>" <?php if($key == @$row->penghasilan_ayah) echo "selected"; ?>><?= $key ?></option>
						  	<?php endforeach ?>
						  </select>
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="nohp_ayah ">No. Handphone</label>
						  <input type="text" class="form-control" id="nohp_ayah" name="nohp_ayah" placeholder="No. Handphone" autocomplete="off" value="<?= @$row->nohp_ayah  ?>">
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="alamat_ayah">Alamat</label>
						  <textarea name="alamat_ayah" id="alamat_ayah" class="form-control" placeholder="Alamat"><?= @$row->alamat_ayah ?></textarea>
						  <small class="help-block"></small>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-xs-12">
					<div class="box-body">
						<div class="form-group">
						  <label for="nama_ibu">Nama Ibu</label>
						  <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" placeholder="Nama Ibu" autocomplete="off" value="<?= @$row->nama_ibu ?>">
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="pendidikan_ibu">Pendidikan Terakhir</label>
						  <select name="pendidikan_ibu" id="pendidikan_ibu" class="form-control select2">
						  	<option value="">Pendidikan Terakhir</option>
						  	<?php foreach ($pendidikan as $key): ?>
						  		<option value="<?= $key ?>" <?php if($key == @$row->pendidikan_ibu) echo "selected"; ?>><?= $key ?></option>
						  	<?php endforeach ?>
						  </select>
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="pekerjaan_ibu">Pekerjaan</label>
						  <select name="pekerjaan_ibu" id="pekerjaan_ibu" class="form-control select2">
						  	<option value="">Pekerjaan</option>
						  	<?php foreach ($pekerjaan_ibu as $key): ?>
						  		<option value="<?= $key ?>" <?php if($key == @$row->pekerjaan_ibu) echo "selected"; ?>><?= $key ?></option>
						  	<?php endforeach ?>
						  </select>
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="penghasilan_ibu">Pengasilan</label>
						  <select name="penghasilan_ibu" id="penghasilan_ibu" class="form-control select2">
						  	<option value="">Pengasilan</option>
						  	<?php foreach ($penghasilan as $key): ?>
						  		<option value="<?= $key ?>" <?php if($key == @$row->penghasilan_ibu) echo "selected"; ?>><?= $key ?></option>
						  	<?php endforeach ?>
						  </select>
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="nohp_ibu ">No. Handphone</label>
						  <input type="text" class="form-control" id="nohp_ibu" name="nohp_ibu" placeholder="No. Handphone" autocomplete="off" value="<?= @$row->nohp_ibu  ?>">
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="alamat_ibu">Alamat</label>
						  <textarea name="alamat_ibu" id="alamat_ibu" class="form-control" placeholder="Alamat"><?= @$row->alamat_ibu ?></textarea>
						  <small class="help-block"></small>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-xs-12">
					<div class="box-body">
						<div class="form-group">
						  <label for="nama_wali">Nama Wali</label>
						  <input type="text" class="form-control" id="nama_wali" name="nama_wali" placeholder="Nama Wali" autocomplete="off" value="<?= @$row->nama_wali ?>">
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="pendidikan_wali">Pendidikan Terakhir</label>
						  <select name="pendidikan_wali" id="pendidikan_wali" class="form-control select2">
						  	<option value="">Pendidikan Terakhir</option>
						  	<?php foreach ($pendidikan as $key): ?>
						  		<option value="<?= $key ?>" <?php if($key == @$row->pendidikan_wali) echo "selected"; ?>><?= $key ?></option>
						  	<?php endforeach ?>
						  </select>
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="pekerjaan_wali">Pekerjaan</label>
						  <select name="pekerjaan_wali" id="pekerjaan_wali" class="form-control select2">
						  	<option value="">Pekerjaan</option>
						  	<?php foreach ($pekerjaan as $key): ?>
						  		<option value="<?= $key ?>" <?php if($key == @$row->pekerjaan_wali) echo "selected"; ?>><?= $key ?></option>
						  	<?php endforeach ?>
						  </select>
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="penghasilan_wali">Pengasilan</label>
						  <select name="penghasilan_wali" id="penghasilan_wali" class="form-control select2">
						  	<option value="">Pengasilan</option>
						  	<?php foreach ($penghasilan as $key): ?>
						  		<option value="<?= $key ?>" <?php if($key == @$row->penghasilan_wali) echo "selected"; ?>><?= $key ?></option>
						  	<?php endforeach ?>
						  </select>
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="nohp_wali ">No. Handphone</label>
						  <input type="text" class="form-control" id="nohp_wali" name="nohp_wali" placeholder="No. Handphone" autocomplete="off" value="<?= @$row->nohp_wali  ?>">
						  <small class="help-block"></small>
						</div>
						<div class="form-group">
						  <label for="alamat_wali">Alamat</label>
						  <textarea name="alamat_wali" id="alamat_wali" class="form-control" placeholder="Alamat"><?= @$row->alamat_wali ?></textarea>
						  <small class="help-block"></small>
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<?= BTN_CANCEL . BTN_SUBMIT ?>
			</div>
		</form>
	</div>
	
</section>

<script type="text/javascript">
	$(document).ready(function() {
		$('#form')
		.bootstrapValidator({
		  excluded: ':disabled',
		  fields: {
		    nohp_ayah: {
		      validators: {
		          numeric: {
		            message: 'No. Handphone tidak valid'
		          },
		      }
		    },
		    nohp_ibu: {
		      validators: {
		          numeric: {
		            message: 'No. Handphone tidak valid'
		          },
		      }
		    },
		    nohp_wali: {
		      validators: {
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
		setTimeout(function() {
			btn_back();
		}, 1575);
	}

	function btn_back() {
		window.location.href = "<?= site_url('user/detail/' . md5(@$row->user_id)) ?>";
	}
</script>

<style type="text/css">
	.nav-tabs-custom>.nav-tabs>li.active {
	    border-top: 3px solid #00A65A;
	}
</style>