<section class="content-header">
  <h1><?= @$folder ?>
  	<?php if (@$content['tapel']): ?>
  	 <small>Tahun Pelajaran <?= @$content['tapel'] ?></small>
  	<?php endif ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url('schedules') ?>"><i class="fa <?= !@$header ? 'fa-calendar' : 'fa-folder-open' ?>"></i> <?= @$folder ?></a></li>
    <?php if (!@$header): ?>
	    <li class="active"><?= @$title ?></li>
	<?php else: ?>
	    <li><a href="<?= site_url('teacher/schedule') ?>"><?= @$title ?></a></li>
	    <li class="active"><?= @$header ?></li>
    <?php endif ?>
  </ol>
</section>
<section class="content">
	<div class="row">
	  <div class="col-md-12 col-xs-12">
	    <div class="box">
	      <div class="box-header with-border">
	        <h3 class="box-title">Daftar <?= @$header ? $header : $title ?> Siswa</h3>
	        <div class="box-tools pull-right">
	        	<a href="javascript:void(0)" class="btn btn-box-tool" onclick="print_data();"><i class="fa fa-print"></i></a>
	        </div>
	      </div>
	      <div class="box-body">
	      	<div class="row">
	      		<div class="col-md-8 col-xs-12">
			      	<div class="callout">
			      		<?= $content['callout'] ?>
			      	</div>
	      		</div>
	      		<div class="col-md-4 col-xs-12">
	      			<div class="form-group">
	      				<label for="">Filter Siswa</label>
	      				<?= $content['select'] ?>
	      				<!-- <div class="input-group">
	      					<span class="input-group-addon">
	      						<i class="fa fa-search"></i>
	      					</span>
	      					<?= $content['select'] ?>
	      				  	<span class="input-group-addon">
	      				  		<i class="fa fa-users" id="users" style="display: none;"></i>
	      				  		<i class="fa fa-user" id="user" style="display: none;"></i>
	      				  	</span>
	      				</div> -->
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
	      		      <?php foreach ($this->include->opsiPresensi() as $key => $value) {
	      		      	echo '<th class="text-center">'. $value .'</th>';
	      		      } ?>
	      		      <th width="5%" class="text-center">Detail</th>
	      		    </tr>
	      		  </thead>
	      		</table>
	      	</div>
	      </div>
	    </div>
	  </div>
	</div>
</section>

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
          <input type="hidden" id="id_user" name="id_user" value="">
          <table id="table1" class="table table-condensed" style="width: 100%">
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

<script type="text/javascript">
	var base_url  = index + "teacher/";
	var id_kelas = $('[name="id_kelas"]');
	var ijp = $('[name="id_jadwal_pelajaran"]');
	var semester = $('[name="semester"]');
	var table1;

	$(document).ready(function() {
		datetable();
		list_presensi();
	});
	
	function datetable() {
	  var id_user = $('[name="user_id"]');

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
	        "url": base_url + "showKehadiran",
	        "type": "POST",
	        "data": function(data) {
	          data.id_kelas = id_kelas.val();
	          data.id_jadwal_pelajaran = ijp.val();
	          data.id_user = id_user.val();
	          data.semester = semester.val();
	          data.id = "<?= time() ?>";
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

	  icon_code(id_user.val());

	  id_user.change(function() {
		Pace.restart();
		table.ajax.reload();
		icon_code(id_user.val());
	  });
	  
	}

	function icon_code(id) {
		if (id) {
		  $('#users').hide();
		  $('#user').show();
		} else {
		  $('#users').show();
		  $('#user').hide();
		}
	}

	function show_presensi(id) {
		var title = $('#title').val();
		var name = $('[name="name_'+ id +'"]').val();
		$('.modal-title').text(title);
		$('#callout-title').text(name);
		$('#id_user').val(id).trigger('change');
		$('#modal-form').modal('show');
	}

	function list_presensi() {
		var id_user = $('[name="id_user"]');

		table1 = $('#table1').DataTable({
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
		        data.id_jadwal_pelajaran = ijp.val();
		        data.id_user 	= id_user.val();
		        data.semester 	= semester.val();
		        data.id = "<?= !@$header ? time() : '' ?>";
		      },
		    },
		    "columnDefs": [{ 
		      "targets": [-1],
		      "orderable": false,
		    }],
		});

		id_user.on('change', function() {
		  Pace.restart();
		  table1.ajax.reload();
		});
	}

	function change_status(id) {
		status = $('[name="status_' + id + '"]').val();
		presensi_id = $('[name="presensi_id_' + id + '"]').val();

		$.ajax({
		    url: "<?= base_url('schedules/changeStatus/') ?>" + ijp.val(),
		    data: {
		    	id_presensi: presensi_id,
		    	status: status,
		    },
		    type: "POST",
		    dataType: "JSON",
		    success: function(response) {
		      Pace.restart();
		      table.ajax.reload();
		      table1.ajax.reload();
		    }
		});
	}

	function print_data() {
		var records_filtered =  $('#records_filtered').val();
		if (records_filtered > 0) {
			$('[name="kls"]').val(id_kelas.val());
			$('[name="ijp"]').val(ijp.val());
			$('[name="smt"]').val(semester.val());
			$('#form-print').submit();
		} else {
			var message = 'Kelas Kosong';
			var type    = 'error';
			flashdata(message, type);
		}
	}

</script>