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
						<a href="javascript:void(0)" class="btn btn-box-tool" onclick="rekap_presensi();"><i class="fa fa-print"></i></a>
					</div>
	      </div>
	      <div class="box-body">
	      	<div class="row">
	      		<div class="col-md-12 col-xs-12">
	      			<div class="callout" style="border-left: 3px solid #00A65A; border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE;">
	      			  <b style="display: block;">Tahun Pelajaran : <?= @$tapel ?></b>
	      			  <b style="display: block;">Semester : <?= @$semester ?></b>
	      			  <b style="display: block;">Kelas : <?= @$nama_kelas ?></b>
	      			</div>
	      		</div>
		    		<div class="col-md-6 col-xs-12">
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
		    							echo '<option value="'. md5($row->user_id) .'">'. $row->no_induk .' - '. $row->full_name .'</option>';
		    						} ?>
		    					</select>
		    				</div>
		    			</form>
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
		    		  <input type="text" id="tgl_awal" name="tgl_awal" value="" style="display: none;">
		    		  <input type="text" id="tgl_akhir" name="tgl_akhir" value="" style="display: none;">
		    		</div>
		    		<!-- <div class="col-md-4 col-xs-12">
		    			<div class="form-group">
		    				<label for="tanggal" class="control-label">Filter Tanggal</label>
	    					<select name="tanggal" id="tanggal" class="form-control select2">
	    						<option value="">-- Pilih Tanggal --</option>
	    					</select>
		    			</div>
		    		</div>
		    		<div class="col-md-4 col-xs-12">
		    			<div class="form-group">
		    				<label for="bulan" class="control-label">Filter Bulan</label>
	    					<select name="bulan" id="bulan" class="form-control select2">
	    						<option value="">-- Pilih Bulan --</option>
	    					</select>
		    			</div>
		    		</div> -->
	      	</div>
	      </div>
	    	<div class="box-body">
		    	<div class="table-responsive" style="border-top: 1px solid #EEEEEE;">
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

</section>

<script type="text/javascript">

	var id_kelas 	= $('[name="id_kelas"]');
	var semester 	= $('[name="id_semester"]');
	var id_user 	= $('[name="id_user"]');
	var id_tahun_pelajaran 	= $('[name="id_tahun_pelajaran"]');
	var tanggal       = $('[name="tanggal"]');
	var bulan         = $('[name="bulan"]');
	var tgl_awal      = $('[name="tgl_awal"]');
	var tgl_akhir     = $('[name="tgl_akhir"]');

	$(function() {

		load_tanggal();
		load_bulan();

		table = $('#table').DataTable({
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
		      "url": "<?= site_url('report/showPresence/' . md5(time())) ?>",
		      "type": "POST",
		      "data": function(data) {
		        data.id_kelas = id_kelas.val();
		        data.id_user  = id_user.val();
		        data.semester = semester.val();
		        data.id_tahun_pelajaran = id_tahun_pelajaran.val();
		        data.tanggal = tanggal.val();
		        data.bulan = bulan.val();
		        data.tgl_awal = tgl_awal.val();
		        data.tgl_akhir = tgl_akhir.val();
		      },
		    },
		    "drawCallback": function(settings) {
		    	$('#records_filtered').val(settings.json.recordsFiltered).trigger('change');
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

		id_user.change(function() {
			Pace.restart();
		  table.ajax.reload();
		});

		tanggal.change(function() {
		  Pace.restart();
		  table.ajax.reload();
		  if (bulan.val()) {
		    bulan.val('').change();
		    bulan.select2(null, false);
		  }
		});

		bulan.change(function() {
		  Pace.restart();
		  table.ajax.reload();
		  if (tanggal.val()) {
		    tanggal.val('').change();
		    tanggal.select2(null, false);
		  }
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

	function rekap_presensi() {
		var records_filtered 	= $('#records_filtered').val();
		if (semester.val()) {
			if (records_filtered > 0) {
				$('#form').attr('action', '<?= site_url('report/rps') ?>');
				$('#form').submit();
			} else {
				var message = 'Kelas Kosong';
				var type    = 'error';
				flashdata(message, type);
			}
		} else {
			var message = 'Semester Kosong';
			var type    = 'error';
			flashdata(message, type);
		}
	}

	function load_tanggal() {
	  var itp = id_tahun_pelajaran.val() ? id_tahun_pelajaran.val() : "<?= time() ?>";

	  $('#tanggal').find('option').not(':first').remove();

	  $.getJSON("<?= site_url('report/getTglPresensi/') ?>" + itp, {
	    id_kelas: id_kelas.val(),
	    semester: semester.val(),
	  }, function(data) {
	    var option = [];
	    for (let i = 0; i < data.length; i++) {
	        option.push({
	            id: data[i].id_tgl,
	            text: data[i].tanggal
	        });
	    }
	    $('#tanggal').select2({
	        data: option
	    })
	  });

	}

	function load_bulan() {
	  var itp = id_tahun_pelajaran.val() ? id_tahun_pelajaran.val() : "<?= time() ?>";
	  var ids = semester.val() ? semester.val() : "<?= time() ?>";

	  $('#bulan').find('option').not(':first').remove();

	  $.getJSON("<?= site_url('report/getBlnPresensi/') ?>" + ids + '/' + itp, function(data) {
	    var option = [];
	    for (let i = 0; i < data.length; i++) {
	        option.push({
	            id: data[i].id_bln,
	            text: data[i].bulan
	        });
	    }
	    $('#bulan').select2({
	        data: option
	    })
	  });

	}

</script>