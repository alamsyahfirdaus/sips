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
	    	  <h3 class="box-title">Daftar Presensi Siswa</h3>
	    	  <div class="box-tools pull-right">
	    	    <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-box-tool" data-toggle="tooltip"><i class="fa fa-times"></i></a>
	    	  </div>
	    	</div>
	    	<div class="box-body">
	    		<div class="callout">
	    			<?= $content['callout']; ?>
	    		</div>
	    		<div class="nav-tabs-custom">
	    		  <ul class="nav nav-tabs">
	    		    <li class="active"><a href="#input" data-toggle="tab" style="font-weight: bold;">Input Presensi Siswa</a></li>
	    		    <li><a href="#rekap" data-toggle="tab" style="font-weight: bold;">Rekap Presensi Siswa</a></li>
	    		  </ul>
	    		  <div class="tab-content">
	    		    <div class="tab-pane active" id="input">
	    		    	<div class="row">
	    		    		<div class="col-md-4 col-xs-12">
	    		    			<div class="form-group">
			    		    		<label for="">Filter Siswa</label>
		    		    			<?= $content['select']; ?>
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
	    		    				<table id="table1" class="table table-condensed" style="width: 100%">
	    		    				  <thead>
	    		    				    <tr>
	    		    				      <th style="width: 5%; text-align: center;">No</th>
	    		    				      <th>NIS</th>
	    		    				      <th>Nama<span style="color: #FFFFFF;">_</span>Lengkap</th>
	    		    				      <th>Jenis<span style="color: #FFFFFF;">_</span>Kelamin</th>
	    		    				      <th style="width: 25%; text-align: center;">Status</th>
	    		    				    </tr>
	    		    				  </thead>
	    		    				</table>
	    		    			</div>
	    		    		</div>
	    		    	</div>
	    		    </div>
	    		    <div class="tab-pane" id="rekap">

	    		    	<div class="row">
	    		    		<div class="col-md-6 col-xs-12">
	    		    			<div class="form-group">
	    		    				<label for="filter_siswa" class="control-label">Filter Siswa</label>
    		    					<select name="filter_siswa" id="filter_siswa" class="form-control select2">
    		    						<option value="">-- Pilih Siswa --</option>
    		    						<?php foreach ($content['siswa'] as $row) {
    		    							echo '<option value="'. md5($row->user_id) .'">'. $row->no_induk .' - '. $row->full_name .'</option>';
    		    						} ?>
    		    					</select>
    		    					<small class="help-block"></small>
	    		    			</div>
	    		    		</div>
	    		    		<div class="col-md-6 col-xs-12">
	    		    			<label for="">Filter Tanggal</label>
	    		    			<div class="input-group">
	    		    				<div class="input-group-btn">
	    		    				  <button type="button" class="btn btn-success pull-right" id="daterange-btn"><i class="fa fa-calendar"></i></button>
	    		    				</div>
	    		    				<input type="text" id="daterange" class="form-control" value="" disabled="" placeholder="-- Pilih Tanggal --">
	    		    				<div class="input-group-btn">
	    		    				  <button type="button" class="btn btn-default" id="btn-refresh"><i class="fa fa-refresh"></i></button>
	    		    				</div>
	    		    			</div>
	    		    		</div>
	    		    		<!-- <div class="col-md-4 col-xs-12">
	    		    			<div class="form-group">
	    		    				<label for="filter_tanggal" class="control-label">Filter Tanggal</label>
    		    					<select name="filter_tanggal" id="filter_tanggal" class="form-control select2">
    		    						<option value="">-- Pilih Tanggal --</option>
    		    					</select>
		    		    			<small class="help-block"></small>
	    		    			</div>
	    		    		</div>
	    		    		<div class="col-md-4 col-xs-12">
	    		    			<div class="form-group">
	    		    				<label for="filter_bulan" class="control-label">Filter Bulan</label>
    		    					<select name="filter_bulan" id="filter_bulan" class="form-control select2">
    		    						<option value="">-- Pilih Bulan --</option>
    		    					</select>
		    		    			<small class="help-block"></small>
	    		    			</div>
	    		    		</div> -->
	    		    	</div>

	    		    	<div class="table-responsive" style="border-top: 1px solid #EEEEEE;">
	    		    		<input type="text" id="tgl_awal" name="tgl_awal" value="" style="display: none;">
	    		    		<input type="text" id="tgl_akhir" name="tgl_akhir" value="" style="display: none;">
	    		    		<table id="table3" class="table table-condensed" style="width: 100%;">
	    		    		  <thead>
	    		    		    <tr>
	    		    		      <th style="width: 5%; text-align: center;">No</th>
	    		    		      <th>NIS</th>
	    		    		      <th>Nama<span style="color: #FFFFFF;">_</span>Lengkap</th>
	    		    		      <th>Jenis<span style="color: #FFFFFF;">_</span>Kelamin</th>
	    		    		      <?php foreach ($this->include->opsiPresensi() as $key => $value) {
	    		    		      	if ($key != 4) {
		    		    		      	echo '<th style="text-align: center;">'. $value .'</th>';
	    		    		      	} else {
		    		    		      	echo '<th style="text-align: center;">Tanpa<span style="color: #FFFFFF;">_</span>Keterangan</th>';
	    		    		      	}
	    		    		      } ?>
	    		    		    </tr>
	    		    		  </thead>
	    		    		  <tfoot id="jumlah">
	    		    		    <tr>
	    		    		      <th colspan="4" style="text-align: center;">Rekap Presensi Siswa</th>
	    		    		      <th style="text-align: center;" id="hadir">0</th>
	    		    		      <th style="text-align: center;" id="sakit">0</th>
	    		    		      <th style="text-align: center;" id="izin">0</th>
	    		    		      <th style="text-align: center;" id="alpa">0</th>
	    		    		    </tr>
	    		    		    <tr>
	    		    		      <th colspan="4" style="text-align: center;">Jumlah Siswa</th>
	    		    		      <th colspan="2" style="text-align: center;">Laki-Laki</th>
	    		    		      <th colspan="2" style="text-align: center;">Perempuan</th>
	    		    		    </tr>
	    		    		    <tr>
	    		    		      <th colspan="4" style="text-align: center;" id="jml_siswa">0</th>
	    		    		      <th colspan="2" style="text-align: center;" id="laki_laki">0</th>
	    		    		      <th colspan="2" style="text-align: center;" id="perempuan">0</th>
	    		    		    </tr>
	    		    		  </tfoot>
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
      		<table id="table2" class="table table-condensed" style="width: 100%;">
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

<script type="text/javascript">

	var table1;
	var table2;
	var table3;
	var base_url 	= index + "teacher/";
	var id_kelas 	= $('[name="id_kelas"]');
	var semester 	= $('[name="semester"]');
	var user_id 	= $('[name="user_id"]');
	var tanggal 	= $('[name="tanggal"]');
	var id_jadwal_pelajaran = $('[name="id_jadwal_pelajaran"]');
	var filter_siswa  			= $('[name="filter_siswa"]');
	var filter_tanggal  		= $('[name="filter_tanggal"]');
	var filter_bulan  			= $('[name="filter_bulan"]');
	var tgl_awal  					= $('[name="tgl_awal"]');
	var tgl_akhir  					= $('[name="tgl_akhir"]');

	$(function() {

		datetable_input();
		table_lihat_tanggal();
		datatable_rekap();
		load_tanggal();
		load_bulan();

		$('#btn-save').click(function() {
			if (tanggal.val()) {
				check_date(tanggal.val());
			} else {
				$('[name="tanggal"]').closest('.form-group').addClass('has-error');
				$('[name="tanggal"]').nextAll('.help-block').eq(0).text('Tanggal harus diisi');
			}
		});

		user_id.attr('disabled', true);

	  user_id.change(function() {
	  	Pace.restart();
	  	table1.ajax.reload();
	  });

	  id_kelas.change(function() {
	  	Pace.restart();
	  	table1.ajax.reload();
	  });

	  tanggal.change(function() {
	  	if (tanggal.val()) {
		  	id_kelas.val('<?= $content['id_kelas']; ?>').trigger('change');
		  	user_id.removeAttr('disabled');
		  	$('[name="tanggal"]').closest('.form-group').removeClass('has-error');
		  	$('[name="tanggal"]').nextAll('.help-block').eq(0).text('');
	  	} else {
		  	id_kelas.val('').trigger('change');
		  	user_id.attr('disabled', true);
		  	$('[name="tanggal"]').closest('.form-group').removeClass('has-error');
		  	$('[name="tanggal"]').nextAll('.help-block').eq(0).text('');
	  	}
	  });

	  filter_siswa.change(function() {
	  	Pace.restart();
	  	table3.ajax.reload();
	  });

	  filter_tanggal.change(function() {
	  	Pace.restart();
	  	table3.ajax.reload();
	  	if (filter_bulan.val()) {
	  	  filter_bulan.val('').change();
	  	  filter_bulan.select2(null, false);
	  	}
	  });

	  filter_bulan.change(function() {
	  	Pace.restart();
	  	table3.ajax.reload();
	  	if (filter_tanggal.val()) {
	  	  filter_tanggal.val('').change();
	  	  filter_tanggal.select2(null, false);
	  	}
	  });

	  tgl_awal.change(function() {
	  	Pace.restart();
	  	table3.ajax.reload();
	  });

	  tgl_akhir.change(function() {
	  	Pace.restart();
	  	table3.ajax.reload();
	  });
	  
	  // $('#daterange').change(function() {
	  // 		Pace.restart();
	  // 		table3.ajax.reload();
	  // });

	  $('.applyBtn').click(function() {
	  	Pace.restart();
	  	table3.ajax.reload();
	  });

	  $('#btn-refresh').click(function() {
	  	$('#daterange').val('').change();
	  	$('#tgl_awal').val('').change();
	  	$('#tgl_akhir').val('').change();
	  });

	});

	function datetable_input() {
	  table1 = $('#table1').DataTable({
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
	        "url": base_url + "show_input_presensi_siswa/" + "<?= md5(time()) ?>",
	        "type": "POST",
	        "data": function(data) {
	          data.id_kelas 						= id_kelas.val();
	          data.id_jadwal_pelajaran 	= id_jadwal_pelajaran.val();
	          data.semester 						= semester.val();
	          data.user_id 							= user_id.val();
	          data.tanggal 							= tanggal.val();
	        },
	      },
	      "columnDefs": [{ 
	        "targets": [0],
	        "orderable": false,
	      }],
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
			      table1.ajax.reload();
			      table2.ajax.reload();
			      table3.ajax.reload();
			      $('#btn-refresh').click();
			      $('#btn-refresh').click();
			      load_tanggal();
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
		      table1.ajax.reload();
		      table2.ajax.reload();
		      table3.ajax.reload();
		      $('#btn-refresh').click();
		      load_tanggal();
		      var message = 'Berhasil Mengubah Status';
		      flashdata(message);
		    }
		});
	}

	function lihat_tanggal() {
		$('.modal-title').text('Tanggal Presensi Siswa');
		$('#modal-lihat-tanggal').modal('show');
		$('[name="presensi_id"]').val('').trigger('change');
		$('[name="tanggal"]').closest('.form-group').removeClass('has-error');
		$('[name="tanggal"]').nextAll('.help-block').eq(0).text('');
		Pace.restart();
		table2.ajax.reload();
	}

	function table_lihat_tanggal() {
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
	        "url": base_url + "show_tanggal_input_presensi_siswa/" + "<?= md5(time()) ?>",
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
		table2.ajax.reload();
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
			    			table2.ajax.reload();
			    		}
			    	} else {
			    		change_tanggal(tanggal_old, tanggal_new);
			    	}
			    }
			});
		} else {
			$('#tanggal_new_'+ id +'').attr('style',  'border: 1px solid #DD4B39;');
			$('#error-tanggal_new_'+ id +'').text('Tanggal harus diisi').show();
		}
	}

	function change_tanggal(tanggal_old, tanggal_new) {
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
			      table1.ajax.reload();
			      table2.ajax.reload();
			      table3.ajax.reload();
			      $('#btn-refresh').click();
			      load_tanggal();
			      var message = 'Berhasil Mengubah Tanggal';
			      flashdata(message);
		      }
		    }
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
		  			table1.ajax.reload();
		  			table2.ajax.reload();
		  			table3.ajax.reload();
		  			$('#btn-refresh').click();
		  			load_tanggal();
		  			if (response.message) {
		  			  flashdata(response.message);
		  			}
		  		}
		  	})
		  }
		})
		
	}

	function datatable_rekap() {
		table3 = $('#table3').DataTable({
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
		      "url": base_url + "show_rekap_presensi_siswa/" + '<?= md5(time()) ?>',
		      "type": "POST",
		      "data": function(data) {
		        data.id_kelas		 					= '<?= $content['id_kelas']; ?>';
		        data.id_jadwal_pelajaran 	= id_jadwal_pelajaran.val();
		        data.user_id 							= filter_siswa.val();
		        data.semester 						= semester.val();
		        data.tanggal 							= filter_tanggal.val();
		        data.bulan 								= filter_bulan.val();
		        data.tgl_awal 						= tgl_awal.val();
		        data.tgl_akhir 						= tgl_akhir.val();
		      },
		    },
		    "drawCallback": function(settings) {
		    	$('#hadir').text(settings.json.hadir);
		    	$('#sakit').text(settings.json.sakit);
		    	$('#izin').text(settings.json.izin);
		    	$('#alpa').text(settings.json.alpa);
		    	$('#jml_siswa').text(settings.json.jml_siswa);
		    	$('#laki_laki').text(settings.json.laki_laki);
		    	$('#perempuan').text(settings.json.perempuan);
		    },
		    "columnDefs": [{ 
		      "targets": [0],
		      "orderable": false,
		    }],
		});
	}

	function load_tanggal() {

	  // $('#filter_tanggal').find('option').not(':first').remove();

	  // $.getJSON(base_url + 'get_tgl_by_jadwal/' + semester.val() + '/' + id_jadwal_pelajaran.val(), function(data) {
	  //   var option = [];
	  //   for (let i = 0; i < data.length; i++) {
	  //       option.push({
	  //           id: data[i].id_tgl,
	  //           text: data[i].tanggal
	  //       });
	  //   }

	  //   $('#filter_tanggal').select2({
	  //       data: option
	  //   });

	  // });

	}

	function load_bulan() {

	  // $('#filter_bulan').find('option').not(':first').remove();

	  // $.getJSON('<?= site_url('report/getBlnPresensi/') ?>' + semester.val() + '/<?= $content['id_tahun_pelajaran'] ?>', function(data) {
	  //   var option = [];
	  //   for (let i = 0; i < data.length; i++) {
	  //       option.push({
	  //           id: data[i].id_bln,
	  //           text: data[i].bulan
	  //       });
	  //   }

	  //   $('#filter_bulan').select2({
	  //       data: option
	  //   });

	  // });

	}

</script>

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