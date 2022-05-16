<section class="content-header">
  <h1><?= $title ?></h1>
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
	  			<h3 class="box-title"><?= $header . ' ' . $title ?></h3>
	  		</div>
	  		<form action="" method="post" id="form" enctype="multipart/form-data">
	  		  <div class="box-body">
	  		    <div class="form-group">
	  		      <label for="judul">Judul</label>
	  		      <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul" autocomplete="off" value="<?= @$row->judul ?>">
	  		      <small class="help-block"></small>
	  		    </div>
   				<div class="form-group">
   				  <label for="gambar">Gambar</label>
   				  	<?php if (!@$row->gambar): ?>
	   				  <button type="button" id="btn-gambar" class="btn btn-flat btn-default form-control" style="text-align: left; background-color: #FFFFFF; border: 1px solid #D2D6DE;">Gambar</button>
				  	<?php else: ?>
	   				  <button type="button" onclick="preview_foto();" class="btn btn-flat btn-default form-control" style="text-align: left; background-color: #FFFFFF; border: 1px solid #D2D6DE;">Gambar</button>
   				  	<?php endif ?>
   				 <small class="help-block"></small>
   				</div>
	  		    <div class="form-group">
	  		      <label for="user_type_id">Jenis Pengguna / Target</label>
	  		      <select name="user_type_id" id="user_type_id" class="form-control select2">
	  		      	<option value="">Semua Pengguna</option>
	  		      	<?php foreach ($user_type as $key): ?>
	  		      		<option value="<?= $key->user_type_id ?>" <?php if($key->user_type_id == @$row->user_type_id) echo 'selected'; ?>><?= $key->type_name ?></option>
	  		      	<?php endforeach ?>
	  		      </select>
	  		      <small class="help-block"></small>
	  		    </div>

	  		    <?php if (@$row->id_pengumuman): ?>
	   				<div class="form-group">
	   				  <label for="tanggal_terbit">Tanggal Terbit</label>
	   				  <input type="text" class="form-control" id="datepicker" name="tanggal_terbit" placeholder="Tanggal Terbit" autocomplete="off" value="<?php if(@$row->tanggal) echo date('m/d/Y', strtotime(@$row->tanggal)) ?>">
		   				<small class="help-block"></small>
	   				</div>
	  		    <?php endif ?>

	  		    <div class="form-group">
	  		    	<label for="pengumuman">Pengumuman</label>
	  		    	<textarea id="pengumuman" name="pengumuman" class="form-control"><?= @$row->pengumuman ?></textarea>
	  		    	<small class="help-block" style="color: #DD4B39; font-size: 12px;" id="error-pengumuman"></small>
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

<form action="" method="post" id="form1" enctype="multipart/form-data" style="display: none;">
	<input type="text" name="judul1" value="">
	<input type="text" name="user_type_id1" value="">
	<input type="file" name="gambar" value="">
	<input type="text" name="pengumuman1" value="">
	<input type="text" name="tanggal" value="<?php if(@$row->tanggal) echo date('m/d/Y', strtotime(@$row->tanggal)) ?>">
</form>

<div class="modal fade" id="modal-form">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <form action="<?= site_url('setting/announcement/changeFoto/' . md5(@$row->id_pengumuman)) ?>" id="form-foto" method="post" enctype="multipart/form-data">
      	<input type="hidden" name="id_pengumuman" value="<?= md5(@$row->id_pengumuman) ?>">
      	<input type="file" name="foto" onchange="edit_image();" style="display: none;">
        <div class="modal-body">
          <div class="form-group">
          	<img class="img-responsive" id="image" src="" alt="Photo" style="display: block; margin-left: auto; margin-right: auto; max-width: 270px; max-height: 200px; display: none; width: 100%; height: 100%;">
          	<button type="button" id="upload-gambar" class="btn btn-flat btn-default form-control edit-foto" style="font-weight: bold; display: none;"><i class="fa fa-image"></i> Upload Gambar</button>
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
	var base_url  = index + "setting/announcement/";
	var id = $('[name="id_pengumuman"]').val();

	$(document).ready(function() {
		CKEDITOR.replace('pengumuman');
		get_image();

		$('#btn-gambar').click(function() {
			$('[name="gambar"]').click();
		});

		$('[name="gambar"]').change(function() {
			$('#btn-gambar').css("border", "1px solid #00A65A");
		});

		$('.edit-foto').click(function() {
			$('[name="foto"]').click();
		});

		$('[name="tanggal_terbit"]').change(function() {
			if ($(this).val()) {
				$('#btn-save').removeAttr('disabled');
				$('[name="tanggal"]').val($(this).val());
				$('[name="tanggal_terbit"]').closest('.form-group').removeClass('has-error').addClass('has-success');
				$('[name="tanggal_terbit"]').nextAll('.help-block').eq(0).text('');
			} else {
				$('#btn-save').attr('disabled', true);
				$('[name="tanggal"]').val('');
				$('[name="tanggal_terbit"]').closest('.form-group').addClass('has-error');
				$('[name="tanggal_terbit"]').nextAll('.help-block').eq(0).text('Tanggal Terbit harus diisi');
			}
		});

		$('#form')
		.bootstrapValidator({
		  excluded: ':disabled',
		  fields: {
		    judul: {
		      validators: {
		          notEmpty: {
		              message: 'Judul harus diisi'
		          },
		      }
		    },
		  }
		})
		.on('success.form.bv', function(e) {
        e.preventDefault();
        form_submit();
    });

	});


	function form_submit() {
		var judul 				= $('[name="judul"]').val();
		var user_type_id 		= $('[name="user_type_id"]').val();
		var pengumuman 			= CKEDITOR.instances['pengumuman'].getData();

		if (pengumuman) {
			$('[name="judul1"]').val(judul);
			$('[name="user_type_id1"]').val(user_type_id);
			$('[name="pengumuman1"]').val(pengumuman);
			$('#form1').submit();
		} else {
			$('#form').data('bootstrapValidator').resetForm();
			$('[name="pengumuman"]').closest('.form-group').addClass('has-error');
			$('#cke_pengumuman').css("border", "1px solid #DD4B39");
			$('#error-pengumuman').text('Pengumuman harus diisi');
			$('#btn-gambar').css("border", "1px solid #D2D6DE");
		}

	}

	function btn_back() {
		window.location.href = index + "setting/announcement";
	}

	function get_image() {
		$.ajax({
		    url: base_url + "getImage/" + id,
		    type: "GET",
		    dataType: "JSON",
		    success: function(response) {
		      if (response.status) {
		      	$('#image').attr('src', response.url);
		      	if (response.image) {
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
		$('.modal-title').text('Gambar');
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
	    text: 'Akan menghapus Gambar',
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
	          url: base_url + "deleteFoto/" + id,
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