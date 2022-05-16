<section class="content-header">
  <h1><?= @$title ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-folder-open"></i> <?= @$title ?></a></li>
  </ol>
</section>
<section class="content">
	<div class="row">
	  <div class="col-md-12 col-xs-12">
	    <div class="box">
	      <div class="box-header with-border">
	        <h3 class="box-title">Daftar Siswa</h3>
					<div class="box-tools pull-right">
						<a href="<?= site_url('presences/'. $id_kelas) ?>" class="btn btn-box-tool"><i class="fa fa-user-plus"></i></a>
					</div>
	      </div>
	      <div class="box-body" style="border-bottom: 1px solid #EEEEEE;">
	      	<div class="row">
	      		<div class="col-md-12 col-xs-12">
	      			<div class="callout" style="border-left: 3px solid #00A65A; border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE;">
	      			  <b style="display: block;">Tahun Pelajaran : <?= @$tapel ?></b>
	      			  <b style="display: block;">Semester : <?= @$semester ?></b>
	      			  <b style="display: block;">Kelas : <?= @$nama_kelas ?></b>
	      			</div>
	      		</div>
	      		<div class="col-md-4 col-xs-12">
	      		  <div class="form-group">
	      		    <label for="id_mata_pelajaran">Filter Mata Pelajaran</label>
	      		    <select name="id_mata_pelajaran" id="id_mata_pelajaran" class="form-control select2" style="width: 100%">
	      		      <option value="">-- Pilih Mata Pelajaran --</option>
	      		    </select>
	      		    <small class="help-block"></small>
	      		  </div>
	      		</div>
		    		<div class="col-md-4 col-xs-12">
		    			<form action="" method="post" id="form" target="_blank">
		    				<input type="text" id="records_filtered" value="" style="display: none;">
		    				<input type="text" name="id_kelas" value="<?= @$id_kelas ?>" style="display: none;">
		    				<input type="text" name="id_tahun_pelajaran" value="<?= md5(@$tahun_pelajaran_id) ?>" style="display: none;">
		    				<input type="text" name="id_semester" value="<?= @$id_semester ?>" style="display: none;">
		    				<div class="form-group">
		    					<label for="id_user">Filter Siswa</label>
		    					<select name="id_user" id="id_user" class="form-control select2">
		    						<option value="">-- Pilih Siswa --</option>
		    						<?php foreach ($siswa['result'] as $row) {
		    							echo '<option value="'. $row->user_id .'">'. $row->no_induk .' - '. $row->full_name .'</option>';
		    						} ?>
		    					</select>
		    				</div>
		    			</form>
		    		</div>
		    		<div class="col-md-4 col-xs-12">
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
		    		  <input type="text" id="tgl_awal" name="tgl_awal" value="" style="display: none;">
		    		  <input type="text" id="tgl_akhir" name="tgl_akhir" value="" style="display: none;">
		    		</div>
	      	</div>
	      </div>
	    	<div class="box-body">
		    	<div class="table-responsive">
		    		<table id="table" class="table table-condensed" style="width: 100%;">
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
		    		      <th style="width: 25%; text-align: center;">Persentase</th>
		    		    </tr>
		    		  </thead>
		    		  <tfoot id="jumlah" style="display: none;">
		    		    <tr>
		    		      <th colspan="4" style="text-align: center;">Jumlah Siswa</th>
		    		      <th colspan="2" style="text-align: center;">Laki-Laki</th>
		    		      <th colspan="2" style="text-align: center;">Perempuan</th>
		    		      <th></th>
		    		    </tr>
		    		    <tr>
		    		      <th colspan="4" style="text-align: center;" id="jml_siswa">0</th>
		    		      <th colspan="2" style="text-align: center;" id="laki_laki">0</th>
		    		      <th colspan="2" style="text-align: center;" id="perempuan">0</th>
		    		      <th></th>
		    		    </tr>
		    		  </tfoot>
		    		</table>
		    	</div>
	    	</div>
	    </div>
	    <div class="box">
	      <div class="box-header with-border">
	        <h3 class="box-title">Grafik Presensi Siswa</h3>
	      </div>
	      <div class="box-body" id="chart-area" style="display: none;">
	        <div class="chart" id="bar-area">
	          <canvas id="barChart" style="max-height: 300px;"></canvas>
	        </div>
	        <div class="row" id="pie-area">
	          <div class="col-md-6 col-xs-12">
	            <div class="chart">
	              <canvas id="pieChart" style="max-height: 300px;"></canvas>
	            </div>
	          </div>
	          <div class="col-md-6 col-xs-12">
	            <div class="table-responsive" id="tb_mapel"></div>
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>

</section>

<script type="text/javascript">

	var id_tahun_pelajaran 	= $('[name="id_tahun_pelajaran"]');
	var id_mata_pelajaran   = $('[name="id_mata_pelajaran"]');
	var semester 						= $('[name="id_semester"]');
	var id_kelas 						= $('[name="id_kelas"]');
	var id_user 						= $('[name="id_user"]');
	var tgl_awal  					= $('[name="tgl_awal"]');
	var tgl_akhir 					= $('[name="tgl_akhir"]');

	$(function() {

		table = $('#table').DataTable({
			"processing": false,
			"serverSide": true,
			"ordering": false,
			"searching": false,
			"info": false,
			"order": [],
	    "language": { 
	      "infoFiltered": "",
	      "sZeroRecords": "<b style='color: #777777;'>TIDAK DITEMUKAN</b>",
	      "sSearch": "Cari:"
	    },
		    "ajax": {
		      "url": "<?= site_url('report/showPresence/' . md5(time())) ?>",
		      "type": "POST",
		      "data": function(data) {
		        data.id_tahun_pelajaran = id_tahun_pelajaran.val();
		        data.id_mata_pelajaran  = id_mata_pelajaran.val();
		        data.semester 					= semester.val();
		        data.id_kelas 					= id_kelas.val();
		        data.id_user  					= id_user.val();
		        data.tgl_awal 					= tgl_awal.val();
		        data.tgl_akhir	 				= tgl_akhir.val();
		      },
		    },
		    "drawCallback": function(settings) {
		    	$('#records_filtered').val(settings.json.recordsFiltered).trigger('change');
		    	$('#jml_siswa').text(settings.json.jml_siswa);
		    	$('#laki_laki').text(settings.json.laki_laki);
		    	$('#perempuan').text(settings.json.perempuan);

		    	if (settings.json.recordsFiltered > 0) {
		    	 $('#jumlah').show();

		    	 if (!id_mata_pelajaran.val()) {
		    	   option_mata_pelajaran(settings.json.mata_pelajaran);
		    	 }

		    	 $('#chart-area').slideDown('slow', function() {
		    	   $(this).show();
		    	 });
		    	 if (settings.json.arr_mapel != false) {
		    	   $('#bar-area').hide();
		    	   $('#pie-area').show();
		    	   pie_chart(settings.json.arr_mapel);
		    	   $('#tb_mapel').html(settings.json.tb_mapel);
		    	 } else {
		    	   $('#bar-area').show();
		    	   $('#pie-area').hide();
		    	   bar_chart(settings.json.arr_siswa, settings.json.arr_hadir, settings.json.arr_color);
		    	 }
		    	 
		    	} else {
		    	 $('#jumlah').hide();
		    	 option_mata_pelajaran(settings.json.mata_pelajaran);
		    	 $('#chart-area').slideUp('slow', function() {
		    	   $(this).hide();
		    	 });
		    	}
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

		id_mata_pelajaran.change(function() {
			Pace.restart();
		  table.ajax.reload();
		});

		tgl_awal.change(function() {
		  Pace.restart();
		  table.ajax.reload();
		});

		tgl_akhir.change(function() {
		  Pace.restart();
		  table.ajax.reload();
		});

		$('.applyBtn').click(function() {
		  Pace.restart();
		});

		$('#btn-refresh').click(function() {
		  $('#daterange').val('').change();
		  $('#tgl_awal').val('').change();
		  $('#tgl_akhir').val('').change();
		});

	});

	function option_mata_pelajaran(data) {
	  $('#id_mata_pelajaran').find('option').not(':first').remove();
	  var option = [];
	    for (let i = 0; i < data.length; i++) {
	        option.push({
	            id: data[i].id_mata_pelajaran,
	            text: data[i].mata_pelajaran
	        });
	    }
	    $('#id_mata_pelajaran').select2({
	        data: option
	    });
	}



	// function rekap_presensi() {
	// 	var records_filtered 	= $('#records_filtered').val();
	// 	if (semester.val()) {
	// 		if (records_filtered > 0) {
	// 			$('#form').attr('action', '<?= site_url('report/rps') ?>');
	// 			$('#form').submit();
	// 		} else {
	// 			var message = 'Kelas Kosong';
	// 			var type    = 'error';
	// 			flashdata(message, type);
	// 		}
	// 	} else {
	// 		var message = 'Semester Kosong';
	// 		var type    = 'error';
	// 		flashdata(message, type);
	// 	}
	// }

</script>