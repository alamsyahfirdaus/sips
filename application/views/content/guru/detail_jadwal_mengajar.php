<section class="content-header">
  <h1><?= @$folder ?>
  	<?php if (@$content['tapel']): ?>
  	 <small>Tahun Pelajaran <?= @$content['tapel'] ?></small>
  	<?php endif ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url('schedules') ?>"><i class="fa fa-calendar"></i> <?= @$folder ?></a></li>
    <li class="active"><?= @$title ?></li>
  </ol>
</section>
<section class="content">
	<div class="row">
	  <div class="col-md-12 col-xs-12">
	    <div class="box">
	    	<div class="box-header with-border">
	    	  <h3 class="box-title">Presensi Siswa</h3>
	    	  <div class="box-tools pull-right">
	    	    <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-box-tool" data-toggle="tooltip"><i class="fa fa-times"></i></a>
	    	  </div>
	    	</div>
	    	<div class="box-body">
	    		<div class="callout">
	    			<?= @$content['callout']; ?>
	    		</div>
	    		<div class="nav-tabs-custom">
	    		  <ul class="nav nav-tabs">
	    		    <li class="active"><a href="#input" data-toggle="tab" style="font-weight: bold;">Input</a></li>
	    		    <li><a href="#rekap" data-toggle="tab" style="font-weight: bold;">Rekap</a></li>
	    		  </ul>
	    		  <div class="tab-content">
	    		    <div class="tab-pane active" id="input">
	    		    	<div class="row">
	    		    		<div class="col-md-4 col-xs-12">
	    		    			<div class="form-group">
			    		    		<label for="">Filter Siswa</label>
		    		    			<?= @$content['select']; ?>
	    		    			</div>
	    		    			<div class="form-group">
			    		    		<label for="">Tanggal</label>
		    		    			<input type="text" id="datepicker" name="tanggal" autocomplete="off" class="form-control" placeholder="-- Pilih Tanggal --">
		    		    			<small class="help-block"></small>
	    		    			</div>
	    		    			<div class="form-group">
			    		    		<button type="button" id="btn-tanggal" onclick="lihat_tanggal();" class="btn btn-sm btn-default" style="font-weight: bold;"><i class="fa fa-calendar-plus-o"></i> Lihat Tanggal</button>
			    		    		<?= BTN_SUBMIT ?>
	    		    			</div>
	    		    		</div>
	    		    		<div class="col-md-8 col-xs-12">
	    		    			<div class="table-responsive">
	    		    				<table id="table" class="table table-condensed" style="width: 100%">
	    		    				  <thead>
	    		    				    <tr>
	    		    				      <th width="5%" class="text-center">No</th>
	    		    				      <th>NIS</th>
	    		    				      <th>Nama</th>
	    		    				      <th width="5%" class="text-center">JK</th>
	    		    				      <th class="text-center">Status</th>
	    		    				    </tr>
	    		    				  </thead>
	    		    				</table>
	    		    			</div>
	    		    		</div>
	    		    	</div>
	    		    </div>
	    		    <div class="tab-pane" id="rekap">
	    		    	<div class="row">
	    		    		<div class="col-md-4 col-xs-12">
	    		    			<div class="form-group">
	    		    				<label for="" class="control-label">Filter Siswa</label>
    		    					<select name="id_user" id="id_user" class="form-control select2">
    		    						<option value="">-- Pilih Siswa --</option>
    		    						<?php foreach (@$content['siswa'] as $row) {
    		    							echo '<option value="'. md5($row->user_id) .'">'. $row->full_name .'</option>';
    		    						} ?>
    		    					</select>
	    		    			</div>
	    		    		</div>
	    		    		<div class="col-md-8 col-xs-12">
    		    				<a href="javascript:void(0)" class="btn btn-sm pull-right" style="font-weight: bold; background-color: #00A65A; color: #FFFFFF;" onclick="print_data();"><i class="fa fa-print"></i> Rekap Presensi Siswa</a>
	    		    		</div>
	    		    	</div>
	    		    	<div style="margin-top: 15px;" class="hidden-md"></div>
	    		    	<div class="table-responsive" style="border-top: 1px solid #EEEEEE;">
	    		    		<table id="table2" class="table table-condensed" style="width: 100%;">
	    		    		  <thead>
	    		    		    <tr>
	    		    		      <th style="width: 5%; text-align: center;">No</th>
	    		    		      <th>NIS</th>
	    		    		      <th>Nama</th>
	    		    		      <th style="width: 5%; text-align: center;">JK</th>
	    		    		      <?php foreach ($this->include->opsiPresensi() as $key => $value) {
	    		    		      	echo '<th style="text-align: center;">'. $value .'</th>';
	    		    		      } ?>
	    		    		      <th style="width: 5%; text-align: center;">Detail</th>
	    		    		    </tr>
	    		    		  </thead>
	    		    		</table>
	    		    	</div>
	    		    </div>
	    		  </div>
	    		</div>
	    	</div>
	    </div>
	  </div>
	</div>
</section>

<div class="modal fade" id="modal-lihat-tanggal">
  <div class="modal-dialog">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title"></h4>
		</div>
        <div class="modal-body">
        	<div id="response"></div>
        	<div class="table-responsive">
        		<input type="text" name="presensi_id" value="" style="display: none;">
        		<table id="table1" class="table table-condensed" style="width: 100%;">
        		  <thead>
        		    <tr>
        		      <th style="text-align: center; width: 5%;">No</th>
        		      <th style="text-align: center;">Tanggal</th>
        		      <th style="text-align: center; width: 5%;">Aksi</th>
        		    </tr>
        		  </thead>
        		</table>
        	</div>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-form">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="callout">
          <b id="callout-title"></b>
        </div>
        <div class="table-responsive" style="border-top: 1px solid #EEEEEE;">
          <input type="hidden" id="id_user-detail" name="id_user-detail" value="">
          <table id="table3" class="table table-condensed" style="width: 100%">
            <thead>
              <tr>
                <th width="5%" class="text-center">No</th>
                <th width="20%">Tanggal</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<form action="<?= site_url('report/rpsg') ?>" method="post" id="form-print" target="_blank" style="display: none;">
	<input type="text" name="kls" value="">
	<input type="text" name="ijp" value="">
	<input type="text" name="smt" value="">
	<input type="text" id="records_filtered" value="">
</form>

<style type="text/css">
	.nav-tabs-custom>.nav-tabs>li.active {
	    border-top: 3px solid #00A65A;
	}

	#table1_paginate>ul>li>a {
		height: 30px;
		font-size: 12px;
	}

	.nav-tabs-custom>.nav-tabs>li>a:hover {
	    color: #333333;
	    border-radius: 0;
	}
</style>

<script type="text/javascript">
	var table1;
	var table2;
	var table3;
	var base_url 				= index + "teacher/";
	var id_kelas 				= $('[name="id_kelas"]');
	var id_jadwal_pelajaran 	= $('[name="id_jadwal_pelajaran"]');
	var semester 				= $('[name="semester"]');
	var tanggal 				= $('[name="tanggal"]');
	var id_user 				= $('[name="user_id"]');
	var user_id 				= $('[name="id_user"]');
	var kelas_id 				= $('[name="kelas_id"]');

	$(document).ready(function() {
		datetable_input();
		table_lihat_tanggal();
		table_rekap();
		list_detail_presensi();

		$('[name="user_id"]').attr('disabled', true);

		$('#btn-save').click(function() {
			if (tanggal.val()) {
				check_date(tanggal.val());
			} else {
				$('[name="tanggal"]').closest('.form-group').addClass('has-error');
				$('[name="tanggal"]').nextAll('.help-block').eq(0).text('Tanggal harus diisi');
			}
		});

	});
	
	function datetable_input() {
	  table = $('#table').DataTable({
	  	"dom": "tp",
	  	"processing": true,
	  	"serverSide": true,
	  	"order": [],
	  	"ordering": false,
	  	"language": { 
	  	  "infoFiltered": "",
	  	  "sZeroRecords": "<b style='color: #777777;'>HARUS MEMILIH TANGGAL</b>",
	  	  "sSearch": "Cari:"
	  	},
	      "ajax": {
	        "url": base_url + "showInputKehadiran",
	        "type": "POST",
	        "data": function(data) {
	          data.id_kelas 			= kelas_id.val();
	          data.id_jadwal_pelajaran 	= id_jadwal_pelajaran.val();
	          data.semester 			= semester.val();
	          data.id_user 				= id_user.val();
	          data.tanggal 				= tanggal.val();
	        },
	      },
	      "columnDefs": [{ 
	        "targets": [0],
	        "orderable": false,
	      }],
	  });

	  id_user.change(function() {
		Pace.restart();
		table.ajax.reload();
	  });

	  kelas_id.change(function() {
		Pace.restart();
		table.ajax.reload();
	  });

	  tanggal.change(function() {
	  	if (tanggal.val()) {
		  	kelas_id.val(id_kelas.val()).trigger('change');
		  	id_user.attr('disabled', false);
		  	$('[name="tanggal"]').closest('.form-group').removeClass('has-error');
		  	$('[name="tanggal"]').nextAll('.help-block').eq(0).text('');
	  	} else {
		  	kelas_id.val('').trigger('change');
		  	id_user.attr('disabled', true);
		  	$('[name="tanggal"]').closest('.form-group').removeClass('has-error');
		  	$('[name="tanggal"]').nextAll('.help-block').eq(0).text('');
	  	}
	  });
	  
	}

	function check_date(tanggal) {
		$.ajax({
		    url: base_url + "getDate/" + id_jadwal_pelajaran.val(),
		    type: "POST",
		    data: {
		    	tanggal: tanggal,
		    },
		    dataType: "JSON",
		    success: function(response) {
		    	if (response.status) {
		    		$('[name="tanggal"]').closest('.form-group').addClass('has-error');
		    		$('[name="tanggal"]').nextAll('.help-block').eq(0).text('Tanggal sudah diinput');
		    	} else {
		    		check_all(tanggal, id_jadwal_pelajaran.val());
		    		Swal.fire({
		    		  title: '<span style="font-family: serif;">Berhasil Melakukan Presensi Siswa</span>',
		    		  text: 'Silahkan Pilih Siswa, Jika Akan Mengubah Status',
		    		  type: 'success',
		    		  showConfirmButton: false,
		    		  timer: 3500
		    		});
		    	}
		    }
		});
	}

	function check_all(tanggal, id) {
		$.ajax({
		    url: base_url + "changeAll/" + id,
		    type: "POST",
		    data: {
		    	tanggal: tanggal,
		    },
		    dataType: "JSON",
		    success: function(response) {
		      if (response.status) {
			      Pace.restart();
			      table.ajax.reload();
			      table1.ajax.reload();
			      table2.ajax.reload();
		      }
		    }
		});
	}

	function change_status(id) {
		status = $('[name="status_' + id + '"]').val();
		user_user_id = $('[name="user_id_' + id + '"]').val();

		$.ajax({
		    url: base_url + "addStatus/" + id_jadwal_pelajaran.val(),
		    data: {
		    	id_user: user_user_id,
		    	status: status,
		    	tanggal: tanggal.val(),
		    },
		    type: "POST",
		    dataType: "JSON",
		    success: function(response) {
		      Pace.restart();
		      table.ajax.reload();
		      table2.ajax.reload();
		      var message = 'Berhasil Mengubah Status';
		      flashdata(message);
		    }
		});
	}

	function lihat_tanggal() {
		$('.modal-title').text('Daftar Tanggal');
		$('#modal-lihat-tanggal').modal('show');
		$('#btn-tanggal').attr('disabled', true);
		$('<div class="alert" role="alert alert-dismissible" style="background-color: #00A65A; color: #FFFFFF; font-weight: bold; text-align: justify;">DAFTAR TANGGAL YANG SUDAH DILAKUKAN PRESENSI SISWA</div>').show().appendTo('#response');
		$('.alert').delay(3500).slideUp('slow', function(){
			$(this).remove();
			$('#btn-tanggal').removeAttr('disabled');
		});
		$('[name="presensi_id"]').val('').trigger('change');
		$('[name="tanggal"]').closest('.form-group').removeClass('has-error');
		$('[name="tanggal"]').nextAll('.help-block').eq(0).text('');
		Pace.restart();
		table1.ajax.reload();
	}

	function table_lihat_tanggal() {

	  table1 = $('#table1').DataTable({
	  	"dom": "tp",
	  	"processing": true,
	  	"serverSide": true,
	  	"order": [],
	  	"ordering": false,
	  	"language": { 
	  	  "infoFiltered": "",
	  	  "sZeroRecords": "<b style='color: #777777;'>TIDAK DITEMUKAN</b>",
	  	  "sSearch": "Cari:"
	  	},
	      "ajax": {
	        "url": base_url + "showTanggalInput",
	        "type": "POST",
	        "data": function(data) {
	          data.id_jadwal_pelajaran 	= id_jadwal_pelajaran.val();
	          data.semester 						= semester.val();
	          data.presensi_id 					= $('[name="presensi_id"]').val();
	        },
	      },
	      "drawCallback": function(settings) {

	      },
	      "columnDefs": [{ 
	        "targets": [0],
	        "orderable": false,
	      }],
	  });
	  
	}

	function edit_tanggal(id) {
		$('[name="presensi_id"]').val(id).trigger('change');
		table1.ajax.reload();
	}

	function save_tanggal(id) {
		$('[name="presensi_id"]').val('').trigger('change');
		var tanggal_old = $('#tanggal_old_'+ id +'').val();
		var tanggal_new = $('#tanggal_new_'+ id +'').val();

		if (tanggal_new) {
			$.ajax({
			    url: base_url + "getDate/" + id_jadwal_pelajaran.val(),
			    type: "POST",
			    data: {
			    	tanggal: tanggal_new,
			    },
			    dataType: "JSON",
			    success: function(response) {
			    	if (response.status) {
			    		if (tanggal_old != tanggal_new) {
				    		$('#tanggal_new_'+ id +'').attr('style',  'border: 1px solid #DD4B39;');
				    		$('#error-tanggal_new_'+ id +'').text('Tanggal sudah diinput').show();
				    		$('#tanggal_new_'+ id +'').change(function() {
				    			$('#tanggal_new_'+ id +'').attr('style',  'border: 1px solid #D2D6DE;');
				    			$('#error-tanggal_new_'+ id +'').text('').hide();
				    		});
			    		} else {
			    			$('[name="presensi_id"]').val('').trigger('change');
			    			table1.ajax.reload();
			    		}
			    	} else {
			    		update_tanggal(tanggal_old, tanggal_new);
			    	}
			    }
			});
		} else {
			$('#tanggal_new_'+ id +'').attr('style',  'border: 1px solid #DD4B39;');
			$('#error-tanggal_new_'+ id +'').text('Tanggal harus diisi').show();
		}
	}

	function update_tanggal(tanggal_old, tanggal_new) {
		$.ajax({
		    url: base_url + "UpdateDate/" + id_jadwal_pelajaran.val(),
		    type: "POST",
		    data: {
		    	tanggal_old: tanggal_old,
		    	tanggal_new: tanggal_new,
		    	id_jadwal_pelajaran: id_jadwal_pelajaran.val(),
		    	semester: semester.val(),
		    },
		    dataType: "JSON",
		    success: function(response) {
		      if (response.status) {
			      Pace.restart();
			      table.ajax.reload();
			      table1.ajax.reload();
			      var message = 'Berhasil Mengubah Tanggal';
			      flashdata(message);
		      }
		    }
		});
	}

	function table_rekap() {
		table2 = $('#table2').DataTable({
			"dom": "tp",
			"processing": true,
			"serverSide": true,
			"order": [],
			"ordering": false,
			"language": { 
			  "infoFiltered": "",
			  "sZeroRecords": "<b style='color: #777777;'>TIDAK DITEMUKAN</b>",
			  "sSearch": "Cari:"
			},
		    "ajax": {
		      "url": base_url + "showKehadiran",
		      "type": "POST",
		      "data": function(data) {
		        data.id_kelas		 					= id_kelas.val();
		        data.id_jadwal_pelajaran 	= id_jadwal_pelajaran.val();
		        data.id_user 							= user_id.val();
		        data.semester 						= semester.val();
		        data.id 									= "<?= time() ?>";
		      },
		    },
		    "drawCallback": function(settings) {
		    	$('#records_filtered').val(settings.json.recordsFiltered).trigger('change');
		    },
		    "columnDefs": [{ 
		      "targets": [0],
		      "orderable": false,
		    }],
		});

		user_id.change(function() {
		 	Pace.restart();
			table2.ajax.reload();
		});
	}

	function print_data() {
		var records_filtered =  $('#records_filtered').val();
		if (records_filtered > 0) {
			$('[name="kls"]').val(id_kelas.val());
			$('[name="ijp"]').val(id_jadwal_pelajaran.val());
			$('[name="smt"]').val(semester.val());
			$('#form-print').submit();
		} else {
			var message = 'Kelas Kosong';
			var type    = 'error';
			flashdata(message, type);
		}
	}

	function show_presensi(id) {
		var title = $('#title').val();
		var name = $('[name="name_'+ id +'"]').val();
		$('.modal-title').text(title);
		$('#callout-title').text(name);
		$('#id_user-detail').val(id).trigger('change');
		$('#modal-form').modal('show');
	}

	function list_detail_presensi() {
		var id_user = $('[name="id_user-detail"]');

		table3 = $('#table3').DataTable({
		    "processing": true,
		    "serverSide": true,
		    "order": [],
		    "lengthChange": false,
		    "searching": false,
		    "ordering": false,
		    "info": false,
		    "language": { 
		      "infoFiltered": "",
		      "sZeroRecords": "<b style='color: #777777;'>TIDAK DITEMUKAN</b>",
		      "sSearch": "Cari:"
		    },
		    "ajax": {
		      "url": "<?= base_url('schedules/showPresensi') ?>",
		      "type": "POST",
		      "data": function(data) {
		        data.id_jadwal_pelajaran 	= id_jadwal_pelajaran.val();
		        data.id_user 				= id_user.val();
		        data.semester 				= semester.val();
		        data.id 					= "<?= !@$header ? time() : '' ?>";
		      },
		    },
		    "columnDefs": [{ 
		      "targets": [-1],
		      "orderable": false,
		    }],
		});

		id_user.on('change', function() {
		  Pace.restart();
		  table3.ajax.reload();
		});
	}

	function delete_tanggal(id) {
		Swal.fire({
		  title: '<span style="font-family: serif;">Apakah anda yakin?</span>',
		  text: 'Akan menghapus Tanggal Presensi',
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
		  		"url": base_url + "deletePresence/" + id,
		  		type: 'POST',
		  		dataType: 'json',
		  		success: function(response) {
		  			Pace.restart();
		  			table.ajax.reload();
		  			table1.ajax.reload();
		  			if (response.message) {
		  			  flashdata(response.message);
		  			}
		  		}
		  	})
		  }
		})
		
	}

</script>
