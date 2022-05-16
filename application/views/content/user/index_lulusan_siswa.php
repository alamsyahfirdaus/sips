<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
  	<li><a href="javascript:void(0)"><i class="fa fa-users"></i> <?= $folder ?></a></li>
  	<li><a href="javascript:void(0)" onclick="history_back();"><?= $title ?></a></li>
  	<li class="active"><?= $header ?></li>
  </ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="box">
			  <div class="box-header with-border">
			    <h3 class="box-title">Daftar <?= $header ?></h3>
			    <div class="box-tools pull-right">
			    	<a href="javascript:void(0)" class="btn btn-box-tool" onclick="history_back();"><i class="fa fa-times"></i></a>
			    </div>
			  </div>
			  <div class="box-body">
			  	<div class="row">
			  		<div class="col-md-3 col-xs-6">
			  			<div class="form-group">
			  				<label for="">Jumlah Lulusan</label>
			  				<input type="text" id="lulusan" class="form-control" disabled="">
			  			</div>
			  		</div>
			  		<div class="col-md-3 col-xs-6">
			  			<div class="form-group">
			  				<label for="">Laki-Laki</label>
			  				<input type="text" id="laki_laki" class="form-control" disabled="">
			  			</div>
			  		</div>
			  		<div class="col-md-3 col-xs-6">
			  			<div class="form-group">
			  				<label for="">Perempuan</label>
			  				<input type="text" id="perempuan" class="form-control" disabled="">
			  			</div>
			  		</div>
			  		<div class="col-md-3 col-xs-6">
			  			<div class="form-group">
			  				<label for="id_tahun_pelajaran"><span class="hidden-xs">Tahun Pelajaran/</span>Angkatan</label>
			  				<select name="id_tahun_pelajaran" id="id_tahun_pelajaran" class="form-control select2">
			  					<option value="">-- Tahun Pelajaran --</option>
			  					<?php foreach ($tahun_pelajaran as $row) {
			  						echo '<option value="'. md5($row->tahun_pelajaran_id) .'">'. $row->tahun_pelajaran .'</option>';
			  					} ?>
			  				</select>
			  			</div>
			  		</div>
			  	</div>
			  </div>
			  <div class="box-body">
			  	<div class="table-responsive">
			  		<table id="table" class="table table-hover" style="width: 100%">
			  		  <thead>
			  		    <tr>
			  		      <th width="5%" class="text-center">No</th>
			  		      <th>NIS</th>
			  		      <th>Nama</th>
			  		      <th>Jenis Kelamin</th>
			  		      <th>Tempat/Tgl Lahir</th>
			  		      <th>Agama</th>
			  		      <th class="text-center">Tahun Pelajaran/Angkatan</th>
			  		      <th class="text-center">Aksi</th>
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
	var id_tahun_pelajaran 	= $('[name="id_tahun_pelajaran"]');

	$(document).ready(function() {
		table = $('#table').DataTable({
		    "processing": true,
		    "serverSide": true,
		    "ordering": false,
		    "order": [],
		    "info": false,
		    "language": { 
		      "infoFiltered": "",
		      "sZeroRecords": "<b style='color: #777777;'>TIDAK DITEMUKAN</b>",
		      "sSearch": "Cari:"
		    },
		    "ajax": {
		      "url": index + "user/showLulusan",
		      "type": "POST",
		      "data": function(data) {
		        data.id_tahun_pelajaran = id_tahun_pelajaran.val();
		      },
		    },
		    "drawCallback": function(settings) {
		     $('#lulusan').val(settings.json.lulusan);
		     $('#laki_laki').val(settings.json.laki_laki);
		     $('#perempuan').val(settings.json.perempuan);
		    },
		    "columnDefs": [{ 
		      "targets": [-1],
		      "orderable": false,
		    }],
		});

		id_tahun_pelajaran.change(function() {
			Pace.restart();
			table.ajax.reload();
		});

	});

	function delete_data(id) {
	  var text  = "Akan menghapus " + title;
	  var url   = index + "user/delete/" + id;
	  confirm_delete(text, url);
	}

	function success_delete() {
	  table.ajax.reload();
	}

	function history_back() {
		window.location.href = "<?= site_url('user/student') ?>"
	}

	function change_angkatan(id) {
		itp = $('[name="itp_'+ id +'"]').val();
		$.ajax({
		    url: index + "user/changeAngkatan/" + id,
		    data: {
		      itp: itp,
		    },
		    type: "POST",
		    dataType: "JSON",
		    success: function(response) {
		      Pace.restart();
		      table.ajax.reload();
		    }
		});
	}
</script>