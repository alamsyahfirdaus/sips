<section class="content-header">
  <h1><?= $folder ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-cogs"></i> <?= $folder ?></a></li>
    <li><a href="javascript:void(0)" onclick="btn_back();"><?= $title ?></a></li>
    <li class="active"><?= $header ?></li>
  </ol>
</section>
<section class="content">
	<div class="row">
	  <div class="col-md-8 col-xs-12">
	  	<div class="box">
	  		<div class="box-header">
	  			<h3 class="box-title"><?= $header . ' ' . $folder ?></h3>
	  		</div>
	  		<form action="" method="post" id="form" enctype="multipart/form-data">
	  		  <input type="text" name="nama_pengaturan" value="<?= $row->nama_pengaturan ?>" style="display: none;">
	  		  <input type="text" name="pengaturan" value="" style="display: none;">
	  		  <div class="box-body">
	  		    <div class="form-group">
	  		      <label for="nama_pengaturan">Nama Pengaturan</label>
	  		      <input type="text" class="form-control" id="nama_pengaturan" value="<?= @$row->nama_pengaturan ?>" readonly="" disabled="" style="font-weight: bold;">
	  		    </div>
	  		    <div class="form-group">
	  		    	<label for="pengaturan">Pengaturan</label>
	  		    	<textarea id="pengaturan" class="form-control"><?= @$row->pengaturan ?></textarea>
	  		    	<small class="help-block" style="color: #DD4B39; font-size: 12px;" id="error-pengaturan"></small>
	  		    </div>
	  		  </div>
	  		 <div class="box-footer">
	  		 	<?= BTN_CANCEL ?>
	  		 	<button type="button" class="btn btn-sm pull-right" style="background-color: #00A65A; color: #FFFFFF; font-weight: bold; font-family: serif;" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
	  		 </div>
	  		</form>
	  	</div>
	  </div>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function() {
		CKEDITOR.replace('pengaturan');

		$('#btn-save').click(function() {
			var pengaturan 	= CKEDITOR.instances['pengaturan'].getData();
			if (pengaturan) {
				$('[name="pengaturan"]').val(pengaturan);
				$('#form').submit();
			} else {
				$('#pengaturan').closest('.form-group').addClass('has-error');
				$('#cke_pengaturan').css("border", "1px solid #DD4B39");
				$('#error-pengaturan').text('Pengaturan harus diisi');
			}

		});
	});
	function btn_back() {
		window.location.href = "<?= site_url('setting/other') ?>";
	}
</script>