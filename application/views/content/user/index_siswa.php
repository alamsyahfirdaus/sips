<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
  	<li><a href="<?= site_url() ?>"><i class="fa fa-users"></i> <?= $folder ?></a></li>
  	<li class="active"><?= $title ?></li>
  </ol>
</section>
<section class="content">
	<div class="row">
	  <?php foreach ($tingkat_kelas as $row): ?>
  		<div class="col-md-4 col-sm-6 col-xs-12">
	  	    <div class="info-box">
	  	      <span class="info-box-icon" style="background-color: #FFFFFF; border-top: 3px solid #00A65A;"><a style="color: #333333;" href="javascript:void(0)" onclick="filter_siswa(<?= "'" . md5($row->tingkat_kelas_id) . "'" ?>);"><i class="fa fa-users"></i></a></span>
	  	      <div class="info-box-content" style="border-top: 3px solid #00A65A;">
	  	        <span class="info-box-text" style="font-weight: bold; text-transform: capitalize; margin-top: 12px;">Kelas <?= $row->tingkat_kelas ?></span>
	  	        <span class="info-box-number"><?= $this->db->join('kelas k', 'k.kelas_id = s.id_kelas', 'left')->where('k.id_tingkat_kelas', $row->tingkat_kelas_id)->get('siswa s')->num_rows(); ?></span>
	  	      </div>
	  	    </div>
  	  	</div>
	  <?php endforeach ?>
	</div>

	<div class="row">
	  <div class="col-md-12 col-xs-12">
	    <div class="box">
	      <div class="box-header with-border">
	        <h3 class="box-title">Daftar <?= $title ?> <span id="tingkat_kelas"></span></h3>
			<div class="box-tools pull-right">
				<a href="javascript:void(0)" class="btn btn-box-tool" onclick="reset_search();"><i class="fa fa-refresh"></i></a>
				<div class="btn-group">
					<button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
					<ul class="dropdown-menu" role="menu">
					  <li><a href="<?= site_url('user/add/' . md5($user_type_id)) ?>">Tambah Siswa</a></li>
					  <li class="divider"></li>
					  <li><a href="javascript:void(0)" id="btn-update_kelas" title="Naik Kelas">Update Kelas</a></li>
					  <!-- <li class="divider"></li>
					  <li><a href="<?= site_url('user/graduates') ?>">Lulusan</a></li> -->
					</ul>
				</div>
			</div>
	      </div>
	      <div class="box-body">
	      	<div class="row">
	      		<div class="col-md-6 col-xs-12">
	      			<form action="<?= site_url('user/validateKelas/'. md5(time())) ?>" method="post" id="form">
		      			<div class="form-group row">
		      				<label for="id_tingkat_kelas" class="col-sm-4 control-label">Tingkat Kelas</label>
		      				<div class="col-sm-6">
		      					<select name="id_tingkat_kelas" id="id_tingkat_kelas" class="form-control select2" style="width: 50%">
						  		  <option value="">-- Tingkat Kelas --</option>
						  		  <?php foreach ($this->db->get('tingkat_kelas')->result() as $row): ?>
						  		  	<option value="<?= md5($row->tingkat_kelas_id) ?>"><?= $row->tingkat_kelas ?></option>
						  		  <?php endforeach ?>
						  		</select>
						  		<small class="help-block"></small>
		      				</div>
		      			</div>
		      			<div class="form-group row">
		      				<label for="id_kelas" class="col-sm-4 control-label">Kelas</label>
		      				<div class="col-sm-6">
		      				  <select name="id_kelas" id="id_kelas" class="form-control select2" style="width: 50%">
						  		  	<option value="">-- Kelas --</option>
						  			</select>
			      				<small class="help-block"></small>
		      				</div>
		      			</div>
	      			</form>
	      		</div>
	      	</div>
	      	<!-- <div class="row">
	      		<div class="col-md-4 col-xs-12">
	      			<label for="id_kelas">Filter Kelas</label>
	      			<select name="id_kelas" id="id_kelas" class="form-control select2" style="width: 50%">
	      				<option value="">-- Kelas --</option>
	      				<?php foreach ($this->db->get('kelas')->result() as $key) {
	      					echo '<option value="'. $key->kelas_id .'">'. $key->nama_kelas .'</option>';
	      				} ?>
	      			</select>
	      			<small class="help-block"></small>
	      		</div>
	      	</div> -->
	      </div>
	      <div class="box-body table-responsive">
	      	<table id="table" class="table table-hover" style="width: 100%">
	      	  <thead>
	      	    <tr>
	      	      <th width="5%" class="text-center">No</th>
	      	      <th>NIS</th>
	      	      <th>Nama<span style="color: #FFFFFF;">_</span>Lengkap</th>
	      	      <th>Jenis<span style="color: #FFFFFF;">_</span>Kelamin</th>
	      	      <th>Tempat/Tgl<span style="color: #FFFFFF;">_</span>Lahir</th>
	      	      <!-- <th width="5%">Agama</th> -->
	      	      <th width="5%">Kelas</th>
	      	      <th class="text-center">Aksi</th>
	      	    </tr>
	      	  </thead>
	      	  <tfoot id="tfoot">
	      	  	<tr>
	      	  		<th colspan="3" style="text-align: center;">Jumlah<span style="color: #FFFFFF;">_</span>Siswa</th>
	      	  		<th colspan="2" style="text-align: center;">Laki-Laki</th>
	      	  		<th colspan="2" style="text-align: center;">Perempuan</th>
	      	  	</tr>
	      	  	<tr>
	      	  		<th colspan="3" id="jumlah_siswa" style="text-align: center;">0</th>
	      	  		<th colspan="2" id="laki_laki" style="text-align: center;">0</th>
	      	  		<th colspan="2" id="perempuan" style="text-align: center;">0</th>
	      	  	</tr>
	      	  </tfoot>
	      	</table>
	      </div>
	    </div>
	  </div>
	</div>
</section>

<div class="modal fade" id="modal-form">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
  	  <input type="text" id="id_siswa" value="" style="display: none;">
      <form action="" method="post" id="form-modal">
        <div class="modal-body">
          <div class="form-group">
            <label for="kelas_id">Kelas</label>
            <select name="kelas_id" id="kelas_id" class="form-control select2" style="width: 50%">
            	<option value="">-- Kelas --</option>
            </select>
            <small class="help-block"></small>
          </div>
          <div class="form-group" id="form-itp" style="display: none;">
          	<label for="id_tahun_pelajaran">Tahun Pelajaran/Angkatan</label>
          	<select name="id_tahun_pelajaran" id="id_tahun_pelajaran" class="form-control select2">
          		<option value="">-- Tahun Pelajaran --</option>
          	</select>
          	<small class="help-block"></small>
          </div>
        </div>
        <div class="modal-footer">
          <?= BTN_CLOSE . BTN_SUBMIT ?>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
	var base_url  = index + "user/";
	var id_tingkat_kelas 	= $('[name="id_tingkat_kelas"]');
	var id_kelas 			= $('[name="id_kelas"]');
	var kelas_id 			= $('[name="kelas_id"]');
	var id_tahun_pelajaran 	= $('[name="id_tahun_pelajaran"]');

	$(document).ready(function() {
	  datatable();
	  form_multipart();
	  form_validation();

	  $('#btn-update_kelas').click(function() {
	  	$('#form').submit();
	  });

	});

	function datatable() {
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
		      "url": base_url + "showSiswa",
		      "type": "POST",
		      "data": function(data) {
		        data.id_tingkat_kelas = id_tingkat_kelas.val();
		        data.id_kelas = id_kelas.val();
		      },
		    },
		    "drawCallback": function(settings) {
		     $('#id_siswa').val(settings.json.recordsFiltered);
		     $('#jumlah_siswa').text(settings.json.jumlah_siswa);
		     $('#laki_laki').text(settings.json.laki_laki);
		     $('#perempuan').text(settings.json.perempuan);
		    },
		    "columnDefs": [{ 
		      "targets": [-1],
		      "orderable": false,
		    }],
		});

		id_tingkat_kelas.change(function() {
			Pace.restart();
			load_kelas(id_tingkat_kelas.val());
			load_naik_kelas(id_tingkat_kelas.val());
			load_tahun_pelajaran(id_tingkat_kelas.val());
		  table.ajax.reload();
		  $('[name="id_tingkat_kelas"]').closest('.form-group').removeClass('has-error');
		  $('[name="id_tingkat_kelas"]').nextAll('.help-block').eq(0).text('');
		});

		id_kelas.change(function() {
			Pace.restart();
			table.ajax.reload();
			$('[name="id_kelas"]').closest('.form-group').removeClass('has-error');
			$('[name="id_kelas"]').nextAll('.help-block').eq(0).text('');
		});

		$('#table').on('draw.dt', function() {
		  if (table.data().any()) {
		     $('#tfoot').show();
		  } else {
		     $('#tfoot').hide();
		  }
		});
	}

	function delete_data(id) {
	  var text  = "Akan menghapus " + title;
	  var url   = base_url + "delete/" + id;
	  confirm_delete(text, url);
	}

	function success_delete() {
	  table.ajax.reload();
	}

	function filter_siswa(id) {
		$('[name="id_tingkat_kelas"]').val(id).trigger('change');
	}

	function reset_search() {
		var table = $('#table').DataTable();
		table.search('').columns().search('').draw();
		table.ajax.reload();
		$('.select2').val('').trigger('change');
		$('.form-group').removeClass('has-error has-success');
		$('[name="id_tingkat_kelas"]').nextAll('.help-block').eq(0).text('');
		$('[name="id_kelas"]').nextAll('.help-block').eq(0).text('');
	}

	function load_kelas(id_tingkat_kelas) {
	    $('#id_kelas').find('option').not(':first').remove();

	    $.getJSON(base_url + "getKelas/" + id_tingkat_kelas, function (data) {
	        var option = [];
	        for (let i = 0; i < data.length; i++) {
	            option.push({
	                id: data[i].kelas_id,
	                text: data[i].nama_kelas
	            });
	        }
	        $('#id_kelas').select2({
	            data: option
	        })
	    });
	}

	function load_naik_kelas(id_tingkat_kelas) {
		$('#kelas_id').find('option').not(':first').remove();

		$.getJSON(base_url + "getNaikKelas/" + id_tingkat_kelas, function (data) {
		    var option = [];
		    for (let i = 0; i < data.length; i++) {
		        option.push({
		            id: data[i].kelas_id,
		            text: data[i].nama_kelas
		        });
		    }
		    $('#kelas_id').select2({
		        data: option
		    })
		});
	}

	function load_tahun_pelajaran(id_tingkat_kelas) {
		$('#id_tahun_pelajaran').find('option').not(':first').remove();

		$.getJSON(base_url + "getTapel/" + id_tingkat_kelas, function (data) {
		    var option = [];
		    for (let i = 0; i < data.length; i++) {
		        option.push({
		            id: data[i].tahun_pelajaran_id,
		            text: data[i].tahun_pelajaran
		        });
		    }
		    $('#id_tahun_pelajaran').select2({
		        data: option
		    })
		});
	}

	function update_kelas() {
		$.ajax({
		    url: base_url + "updateKelas",
		    data: {
		      id_kelas: id_kelas.val(),
		      kelas_id: kelas_id.val(),
		      id_tahun_pelajaran: id_tahun_pelajaran.val(),
		    },
		    type: "POST",
		    dataType: "JSON",
		    success: function(response) {
		      Pace.restart();
		      $('#modal-form').modal('hide');
		      table.ajax.reload();
		      reset_search();
		      if (response.message) {
		        flashdata(response.message);
		      }
		    }
		});
	}

	function form_validation() {
	  $('#form-modal')
	  .bootstrapValidator({
	    excluded: ':disabled',
	    fields: {
	      kelas_id: {
	        validators: {
	            notEmpty: {
	                message: 'Kelas harus diisi'
	            },
	        }
	      },
	      id_tahun_pelajaran: {
	        validators: {
	            notEmpty: {
	                message: 'Tahun Pelajaran harus diisi'
	            },
	        }
	      },
	    }
	  })
	  .on('success.form.bv', function(e) {
	      e.preventDefault();
	      update_kelas();
	  });
	}

	function action_result(result) {
		$('#form-modal').data('bootstrapValidator').resetForm();
		var id_siswa = $('#id_siswa').val();
		// if (result > 1) {
		// 	$('#form-itp').hide();
			$('[name="id_tahun_pelajaran"]').val(result).trigger('change');
		// } else {
		// 	$('#form-itp').show();
		// }
		if (id_siswa > 0) {
			$('.modal-title').text('Update Kelas / Naik Kelas');
			$('#modal-form').modal('show');
		} else {
			var message = 'Kelas Kosong';
			var type 	= 'error';
			flashdata(message, type);
		}
	}

</script>