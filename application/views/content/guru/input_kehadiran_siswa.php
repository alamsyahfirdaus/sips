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
	  <div class="col-md-8 col-xs-12">
	    <div class="box">
	      <div class="box-header with-border">
	        <h3 class="box-title"><?= @$title ?> Presensi</h3>
	      </div>
	      <div class="box-body">
	      	<div class="callout">
	      		<?= @$content['callout']; ?>
	      	</div>
	      	<div class="row">
	      		<div class="col-md-6 col-xs-12">
      				<div class="form-group">
      					<label for="">Tanggal</label>
      					<div class="input-group">
      						<span class="input-group-addon">
      							<i class="fa fa-calendar-plus-o"></i>
      						</span>
      						<input type="text" id="datepicker" name="tanggal" class="form-control" placeholder="-- Pilih Tanggal --" autocomplete="off">
      					  	<span class="input-group-addon">
      					  		<a href="javascript:void(0)" onclick="reset_date();" style="color: #555555;"><i class="fa fa-refresh"></i></a>
      					  	</span>
      					</div>
      				</div>
	      		</div>
	      		<div class="col-md-6 col-xs-12">
	      			<div class="form-group">
	      				<label for="">Filter Siswa</label>
	      				<div class="input-group">
	      					<span class="input-group-addon">
	      						<i class="fa fa-search"></i>
	      					</span>
	      					<?= @$content['select']; ?>
	      				  	<span class="input-group-addon">
	      				  		<input type="checkbox" id="check-all" value="<?= @$id ?>" style="display: none;">
	      				  		<i class="fa fa-check" id="checked" style="display: none;"></i>
	      				  		<i class="fa fa-user-times" id="user-times" style="display: none;"></i>
	      				  	</span>
	      				</div>
	      			</div>
	      		</div>
	      	</div>
	      </div>
	      <div class="box-body">
	      	<div class="table-responsive" style="border-top: 1px solid #EEEEEE;">
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
	</div>
</section>

<script type="text/javascript">
	var base_url  = index + "teacher/";
	var id_kelas = $('[name="id_kelas"]');
	var id_jadwal_pelajaran = $('[name="id_jadwal_pelajaran"]');
	var semester = $('[name="semester"]');
	var tanggal 	= $('[name="tanggal"]');

	$(document).ready(function() {
		datetable();

		$('#user-times').show();
		$('[name="user_id"]').attr('disabled', true);

		$('#check-all').click(function() {
			change_all(tanggal.val(), $(this).val());
		});
	});
	
	function datetable() {
	  var id_user 	= $('[name="user_id"]');
	  var kelas_id 	= $('[name="kelas_id"]');

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
	          data.id_kelas = kelas_id.val();
	          data.id_jadwal_pelajaran = id_jadwal_pelajaran.val();
	          data.semester = semester.val();
	          data.id_user = id_user.val();
	          data.tanggal = tanggal.val();
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
		  	check_date(tanggal.val());
		  	kelas_id.val(id_kelas.val()).trigger('change');
		  	$('#user-times').hide();
		  	id_user.attr('disabled', false);
	  	} else {
		  	kelas_id.val('').trigger('change');
		  	id_user.attr('disabled', true);
		  	$('#user-times').show();
		  	$('#check-all').hide();
		  	$('#checked').hide();
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
			      $('#check-all').hide();
			      $('#checked').show();
		      } else {
			      $('#check-all').show();
			      $('#checked').hide();
		      }
		    }
		});
	}

	function change_all(tanggal, id) {
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
			      check_date(tanggal);
		      }
		      $('#check-all').prop('checked', false);
		    }
		});
	}

	function change_status(id) {
		status = $('[name="status_' + id + '"]').val();
		user_id = $('[name="user_id_' + id + '"]').val();

		$.ajax({
		    url: base_url + "addStatus/" + id_jadwal_pelajaran.val(),
		    data: {
		    	id_user: user_id,
		    	status: status,
		    	tanggal: tanggal.val(),
		    },
		    type: "POST",
		    dataType: "JSON",
		    success: function(response) {
		      Pace.restart();
		      table.ajax.reload();
		      check_date(tanggal.val());
		    }
		});
	}

	function reset_date() {
		tanggal.val('').trigger('change');
		Pace.restart();
		table.ajax.reload();
	}

</script>