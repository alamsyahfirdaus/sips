<section class="content-header">
  <h1><?= @$title ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-folder-open"></i> <?= @$folder ?></a></li>
    <li class="active"><?= @$title ?></li>
  </ol>
</section>
<section class="content">

    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">Daftar Siswa</a></li>
        <li><a href="#tab_2" data-toggle="tab">Presensi Siswa</a></li>
        <li class="pull-right"><a href="javascript:void(0)" class="text-muted" onclick="rekap_presensi();"><i class="fa fa-print"></i></a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="box-body">
          	<div class="row">
          		<div class="col-md-8 col-xs-12">
          			<div class="callout" style="border-left: 3px solid #00A65A; border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE;">
          			  <b style="display: block;">Tahun Pelajaran : <?= @$tapel ?></b>
          			  <b style="display: block;">Semester : <?= @$semester ?></b>
          			  <b style="display: block;">Kelas : <?= @$nama_kelas ?></b>
          			</div>
          		</div>
          		<div class="col-md-4 col-xs-12">
          			<div class="form-group">
          				<label for="">Filter Siswa</label>
      					<select name="user_id" id="user_id" class="form-control select2">
      						<option value="">-- Pilih Siswa --</option>
      						<?php foreach ($siswa['result'] as $row) {
      							echo '<option value="'. md5($row->user_id) .'">'. $row->full_name .'</option>';
      						} ?>
      					</select>
          			</div>
          		</div>
          	</div>
          	<div class="table-responsive" style="border-top: 1px solid #EEEEEE;">
          		<table id="table" class="table table-condensed" style="width: 100%">
          		  <thead>
          		    <tr>
          		      <th width="5%" class="text-center">No</th>
          		      <th>NIS</th>
          		      <th>Nama</th>
          		      <th>Jenis Kelamin</th>
          		      <th>Tempat/Tgl Lahir</th>
          		      <th>Agama</th>
          		      <th class="text-center" width="5%">Detail</th>
          		    </tr>
          		  </thead>
          		</table>
          	</div>
          </div>
        </div>
        <div class="tab-pane" id="tab_2">
          <div class="box-body">
          	<div class="row">
          		<div class="col-md-8 col-xs-12">
          			<div class="callout" style="border-left: 3px solid #00A65A; border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-top: 1px solid #EEEEEE;">
          			  <b style="display: block;">Tahun Pelajaran : <?= @$tapel ?></b>
          			  <b style="display: block;">Semester : <?= @$semester ?></b>
          			  <b style="display: block;">Kelas : <?= @$nama_kelas ?></b>
          			</div>
          		</div>
          		<div class="col-md-4 col-xs-12">
          			<form action="" method="post" id="form" target="_blank">
          				<input type="text" id="records_filtered" value="" style="display: none;">
          				<input type="text" name="id_kelas" value="<?= @$id_kelas ?>" style="display: none;">
          				<input type="text" name="id_tahun_pelajaran" value="<?= md5(@$tahun_pelajaran_id) ?>" style="display: none;">
          				<input type="text" name="id_semester" value="<?= @$id_semester ?>" style="display: none;">
          				<div class="form-group">
          					<label for="">Filter Siswa</label>
          					<select name="id_user" id="id_user" class="form-control select2">
          						<option value="">-- Pilih Siswa --</option>
          						<?php foreach ($siswa['result'] as $row) {
          							echo '<option value="'. md5($row->user_id) .'">'. $row->full_name .'</option>';
          						} ?>
          					</select>
          				</div>
          			</form>
          		</div>
          	</div>
          	<div class="table-responsive" style="border-top: 1px solid #EEEEEE;">
          		<table id="table1" class="table table-condensed" style="width: 100%">
          		  <thead>
          		    <tr>
          		      <th width="5%" class="text-center">No</th>
          		      <th>NIS</th>
          		      <th>Nama</th>
          		      <th>Jenis Kelamin</th>
          		      <?php foreach ($this->include->opsiPresensi() as $key => $value) {
          		        echo '<th class="text-center">'. $value .'</th>';
          		      } ?>
          		      <th width="5%" class="text-center">Cetak</th>
          		    </tr>
          		  </thead>
          		</table>
          	</div>
          </div>
        </div>
      </div>
    </div>

</section>

<script type="text/javascript">
	var table1;
	var base_url  	= index + "teacher/";
	var id_kelas 	= $('[name="id_kelas"]');
	var semester 	= $('[name="id_semester"]');
	var id_user 	= $('[name="user_id"]');
	var id_siswa 	= $('[name="id_user"]');

	$(document).ready(function() {

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
		      "url": base_url + "showKelasWakel",
		      "type": "POST",
		      "data": function(data) {
		        data.id_kelas = id_kelas.val();
		        data.id_user = id_user.val();
		        data.id_tahun_pelajaran = "<?= @$tahun_pelajaran_id ?>";
		        data.semester = semester.val();
		      },
		    },
		    "columnDefs": [{ 
		      "targets": [1, 2, 3, 4, 5, 6],
		      "orderable": false,
		    }],
		});

		id_user.change(function() {
			Pace.restart();
		    table.ajax.reload();
		});

		datatable_presensi();

		id_siswa.change(function() {
			Pace.restart();
		    table1.ajax.reload();
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

	function datatable_presensi() {
		table1 = $('#table1').DataTable({
		  "dom": "tp",
		  "processing": true,
		  "serverSide": true,
		  "ordering": false,
		  "info": false,
		  "order": [],
		  "language": { 
		    "infoFiltered": "",
		    "sZeroRecords": "<b style='color: #777777;'>TIDAK DITEMUKAN</b>",
		    "sSearch": "Cari:"
		  },
		    "ajax": {
		      "url": index + "report/showPresence",
		      "type": "POST",
		      "data": function(data) {
		        data.id_tahun_pelajaran = "<?= md5(@$tahun_pelajaran_id) ?>";
		        data.semester = semester.val();
		        data.id_kelas = id_kelas.val();
		        data.id_user = id_siswa.val();
		      },
		    },
		    "drawCallback": function(settings) {
		     $('#records_filtered').val(settings.json.recordsFiltered).trigger('change');
		    },
		    "columnDefs": [{ 
		      "targets": [-1],
		      "orderable": false,
		    }],
		});
	}

	function print_data(id) {
	  if (semester.val()) {
	    $.ajax({
	        url: index + "report/checkMapel/<?= md5(@$tahun_pelajaran_id) ?>",
	        data: {
	          id_kelas: id_kelas.val(),
	        },
	        type: "POST",
	        dataType: "JSON",
	        success: function(response) {
	          if (response.status) {
	            $('#form').attr('action', '<?= site_url('report/student/') ?>' + id);
	            $('#form').submit();
	          } else {
	            var message = 'Jadwal Pelajaran Kosong';
	            var type    = 'error';
	            flashdata(message, type);
	          }
	        }
	    });
	  } else {
	  	var message = 'Semester Kosong';
	  	var type    = 'error';
	  	flashdata(message, type);
	  }
	}
</script>

<style type="text/css">
	.nav-tabs-custom>.nav-tabs>li.active {
	    border-top: 3px solid #00A65A;
	}
	.nav-tabs-custom>.nav-tabs>li>a {
	    font-size: 18px;
	    height: 41px;
	    line-height: 18px;
	    font-weight: 500;
	}
</style>