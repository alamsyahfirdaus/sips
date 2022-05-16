<section class="content-header">
  <h1><?= @$title ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-home"></i> <?= @$folder ?></a></li>
    <li class="active"><?= @$title ?></li>
  </ol>
</section>
<section class="content">
	<div class="row">
	  <div class="col-md-12 col-xs-12">
	    <div class="box">
	      <div class="box-header with-border">
	        <h3 class="box-title">Daftar <?= $title ?></h3>
	        <div class="box-tools pull-right">
	          <a href="<?= site_url('home') ?>" class="btn btn-box-tool" data-toggle="tooltip"><i class="fa fa-times"></i></a>
	        </div>
	      </div>
	      <div class="box-body">
	      	<div class="callout">
	      	  <?= @$content['callout'] ?>
	      	</div>
	      	<div class="row">
	      		<div class="col-md-4 col-xs-12">
	      			<div class="form-group">
	      				<label for="">Filter Siswa</label>
	      				<?= @$content['select'] ?>
	      			</div>
	      			<!-- <div class="form-group">
	      				<label for="">Presensi Siswa</label>
	      				<div class="input-group">
			                <input type="text" class="form-control" value="<?= $this->include->date(date('Y-m-d')) ?>" disabled="">
			                <span class="input-group-btn" id="input-btn" style="display: none;">
			                	<button type="button" id="btn-check" class="btn btn-success btn-flat"><i class="fa fa-save"></i></button>
			                </span>
			                <span class="input-group-addon" id="input-addon" style="display: none;">
			                	<i class="fa fa-check"></i>
			                </span>
			            </div>
	      			</div> -->
	      			<div class="form-group">
	      				<label for="">Presensi Siswa</label>
	      				<input type="text" class="form-control" value="<?= $this->include->date(date('Y-m-d')) ?>" disabled="">
	      			</div>
	      			<div class="form-group">
	      				<button type="button" id="btn-check" class="btn btn-success btn-sm" style="font-weight: bold; float: right; display: none;"><i class="fa fa-save"></i> Simpan</button>
	      			</div>
	      		</div>
	      		<div class="col-md-8 col-xs-12">
			      	<div class="table-responsive">
			      		<table id="table" class="table table-condensed" style="width: 100%">
			      		  <thead>
			      		    <tr>
			      		      <th width="5%" class="text-center">No</th>
			      		      <th>NIS</th>
			      		      <th>Nama<span style="color: #FFFFFF;">_</span>Lengkap</th>
			      		      <th>Jenis<span style="color: #FFFFFF;">_</span>Kelamin</th>
			      		      <th style="width: 25%; text-align: center;">Status</th>
			      		    </tr>
			      		  </thead>
			      		  <tfoot id="jumlah">
			      		    <tr>
			      		      <th colspan="2" style="text-align: center;">Hadir</th>
			      		      <th style="text-align: center;">Sakit</th>
			      		      <th style="text-align: center;">Izin</th>
			      		      <th style="text-align: center;">Tanpa Keterangan</th>
			      		    </tr>
			      		    <tr>
			      		      <th colspan="2" style="text-align: center;" id="hadir">0</th>
			      		      <th style="text-align: center;" id="sakit">0</th>
			      		      <th style="text-align: center;" id="izin">0</th>
			      		      <th style="text-align: center;" id="alpa">0</th>
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
</section>

<script type="text/javascript">
	var base_url  = index + "teacher/";
	var id_kelas = $('[name="id_kelas"]');
	var id_jadwal_pelajaran = $('[name="id_jadwal_pelajaran"]');

	$(document).ready(function() {
		datatable();
		check_date();

		$('#btn-check').click(function() {
			change_all('<?= @$id ?>');
		});

	});
	
	function datatable() {
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
	        "url": base_url + "showKehadiran/<?= md5(time()) ?>",
	        "type": "POST",
	        "data": function(data) {
	          data.id_kelas = '<?= @$content['id_kelas'] ?>';
	          data.id_jadwal_pelajaran = id_jadwal_pelajaran.val();
	          data.id_user = id_user.val();
	        },
	      },
	      "drawCallback": function(settings) {
	       $('#hadir').text(settings.json.hadir);
	       $('#sakit').text(settings.json.sakit);
	       $('#izin').text(settings.json.izin);
	       $('#alpa').text(settings.json.alpa);
	       if (settings.json.recordsFiltered > 0) {
	        $('#jumlah').show();
	       } else {
	        $('#jumlah').hide();
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
	  
	}

	function check_date() {
		$.ajax({
		    url: base_url + "getDate/" + id_jadwal_pelajaran.val(),
		    type: "POST",
		    dataType: "JSON",
		    success: function(response) {
		      if (response.status) {
		      	$('#btn-check').hide();
			      // $('#check-all').hide();
			      // $('#checked').show();
			      // $('#input-btn').hide();
			      // $('#input-addon').show();
		      } else {
		      	$('#btn-check').show();
			      // $('#check-all').show();
			      // $('#checked').hide();
			      // $('#input-btn').show();
			      // $('#input-addon').hide();
		      }
		    }
		});
	}

	function change_all(id) {
		$.ajax({
		    url: base_url + "changeAll/" + id,
		    type: "POST",
		    dataType: "JSON",
		    success: function(response) {
		      if (response.status) {
			      Pace.restart();
			      table.ajax.reload();
			      check_date();
			      Swal.fire({
			        title: '<span style="font-family: serif;">Berhasil Melakukan Presensi Siswa</span>',
			        text: 'Silahkan Pilih Siswa, Jika Akan Mengubah Status',
			        type: 'success',
			        showConfirmButton: false,
			        timer: 3500
			      });
		      }
		      // $('#check-all').prop('checked', false);
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
		    },
		    type: "POST",
		    dataType: "JSON",
		    success: function(response) {
		      Pace.restart();
		      table.ajax.reload();
		      check_date();
		      var message = 'Berhasil Mengubah Status';
		      flashdata(message);
		    }
		});
	}
</script>