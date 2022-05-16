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
	  	      <span class="info-box-icon" style="background-color: #FFFFFF; border-top: 3px solid #00A65A;"><a style="color: #333333;" href="javascript:void(0)" onclick="filter_tingkat_kelas(<?= "'" . md5($row->tingkat_kelas_id) . "'" ?>);"><i class="fa fa-users"></i></a></span>
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
	        <h3 class="box-title">Daftar <?= $title ?></h3>
					<div class="box-tools pull-right">
						<a href="javascript:void(0)" class="btn btn-box-tool" onclick="reload_table();"><i class="fa fa-refresh"></i></a>
						<div class="btn-group">
							<button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></button>
							<ul class="dropdown-menu" role="menu">
							  <li><a href="<?= site_url('user/add/' . md5($user_type_id)) ?>">Tambah Siswa</a></li>
							  <li class="divider"></li>
							  <li><a href="javascript:void(0)" onclick="check_kelas();">Update Kelas</a></li>
							</ul>
						</div>
					</div>
	      </div>
	      <div class="box-body">
	      	<div class="row">
	      		<div class="col-md-4 col-xs-12">
	      			<input type="text" id="id_tingkat_kelas" name="id_tingkat_kelas" value="" style="display: none;">
	      			<div class="form-group">
	      				<label for="id_kelas">Filter Kelas</label>
	      				<select name="id_kelas" id="id_kelas" class="form-control select2" style="width: 50%">
	      					<option value="">-- Kelas --</option>
	      					<?php foreach ($this->mall->get_kelas() as $key) {
	      						echo '<option value="'. md5($key->kelas_id) .'">'. $key->nama_kelas .'</option>';
	      					} ?>
	      				</select>
	      				<small class="help-block"></small>
	      			</div>
	      		</div>
	      		<div class="col-md-4 col-xs-12">
	      			<div class="form-group">
	      				<label for="is_aktif">Filter Status</label>
	      				<select name="is_aktif" id="is_aktif" class="form-control select2" style="width: 50%">
	      					<option value="">-- Status --</option>
	      					<?php foreach ($status as $key => $value) {
	      						echo '<option value="'. $key .'">'. $value .'</option>';
	      					} ?>
	      				</select>
	      				<small class="help-block"></small>
	      			</div>
	      		</div>
	      	</div>
	      </div>
	      <div class="box-body table-responsive">
	      	<table id="table" class="table table-hover" style="width: 100%;">
	      	  <thead>
	      	    <tr>
	      	      <th style="width: 5%; text-align: center;">No</th>
	      	      <th>NIS</th>
	      	      <th>Nama<span style="color: #FFFFFF;">_</span>Lengkap</th>
	      	      <th>Jenis<span style="color: #FFFFFF;">_</span>Kelamin</th>
	      	      <th>Tempat/Tgl<span style="color: #FFFFFF;">_</span>Lahir</th>
	      	      <th style="width: 10%;">Kelas</th>
	      	      <th style="width: 10%;">Status</th>
	      	      <th style="width: 10%; text-align: center;">Aksi</th>
	      	    </tr>
	      	  </thead>
	      	  <tfoot id="tfoot">
	      	  	<tr>
	      	  		<th colspan="4" style="text-align: center;">Jumlah<span style="color: #FFFFFF;">_</span>Siswa</th>
	      	  		<th colspan="2" style="text-align: center;">Laki-Laki</th>
	      	  		<th colspan="2" style="text-align: center;">Perempuan</th>
	      	  	</tr>
	      	  	<tr>
	      	  		<th colspan="4" id="jumlah_siswa" style="text-align: center;">0</th>
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
  	  <input type="text" id="id_siswa" name="id_siswa" value="" style="display: none;">
  	  <input type="text" id="tingkat_kelas_id" name="tingkat_kelas_id" value="" style="display: none;">
      <form action="" method="post" id="form">
        <div class="modal-body">
          <div class="form-group">
            <label for="kelas_id">Kelas</label>
            <select name="kelas_id" id="kelas_id" class="form-control select2" style="width: 50%">
            	<option value="">-- Kelas --</option>
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
	var base_url  					= index + "user/";
	var id_tingkat_kelas 		= $('[name="id_tingkat_kelas"]');
	var id_kelas 						= $('[name="id_kelas"]');
	var kelas_id 						= $('[name="kelas_id"]');
	var id_tahun_pelajaran 	= $('[name="id_tahun_pelajaran"]');
	var is_aktif 						= $('[name="is_aktif"]');

	$(document).ready(function() {
	  datatable();
	  form_validation();

	  id_tingkat_kelas.change(function() {
	  	Pace.restart();
	    table.ajax.reload();
	  });

	  id_kelas.change(function() {
	  	Pace.restart();
	  	table.ajax.reload();
	  	if (id_kelas.val()) {
		  	load_naik_kelas();
	  	}
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

	  is_aktif.change(function() {
	  	Pace.restart();
	    table.ajax.reload();
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
		      "url": base_url + "showSiswa/<?= md5(time()) ?>",
		      "type": "POST",
		      "data": function(data) {
		        data.id_tingkat_kelas = id_tingkat_kelas.val();
		        data.id_kelas 				= id_kelas.val();
		        data.is_aktif 				= is_aktif.val();
		      },
		    },
		    "drawCallback": function(settings) {
		    	$('#id_siswa').val(settings.json.recordsFiltered).trigger('change');
		    	$('#jumlah_siswa').text(settings.json.jumlah_siswa);
		    	$('#laki_laki').text(settings.json.laki_laki);
		    	$('#perempuan').text(settings.json.perempuan);
		    },
		    "columnDefs": [{ 
		      "targets": [0],
		      "orderable": false,
		    }],
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

	function filter_tingkat_kelas(id) {
		$('[name="id_tingkat_kelas"]').val(id).trigger('change');
	}

	function reload_table() {
		var table = $('#table').DataTable();
		table.search('').columns().search('').draw();
		table.ajax.reload();
		$('.select2').val('').trigger('change');
	}

	function check_kelas() {
		if (id_kelas.val()) {
			if ($('[name="id_siswa"]').val() > 0) {
				if ($('[name="tingkat_kelas_id"]').val() == 3) {
					$('[name="kelas_id"]').val('').trigger('change');
					update_kelas();
				} else {
					$('#form').data('bootstrapValidator').resetForm();
					$('.modal-title').text('Update Kelas / Naik Kelas');
					$('#modal-form').modal('show');
				}
			} else {
				var message = 'Kelas Kosong';
				var type 		= 'error';
				flashdata(message, type);
			}
		} else {
			$('[name="id_kelas"]').closest('.form-group').addClass('has-error');
			$('[name="id_kelas"]').nextAll('.help-block').eq(0).text('Kelas harus diisi');
		}
	}

	function load_naik_kelas() {
		$('#kelas_id').find('option').not(':first').remove();

		$.ajax({
			url: base_url + 'getNaikKelas/' + id_kelas.val(),
			type: 'POST',
			dataType: 'json',
			success: function(response) {
				$('[name="tingkat_kelas_id"]').val(response.id_tingkat_kelas).trigger('change');
				var data 	 = response.select2;
		    var option = [];
		    for (let i = 0; i < data.length; i++) {
		        option.push({
		            id: data[i].kelas_id,
		            text: data[i].nama_kelas
		        });
		    }
		    $('#kelas_id').select2({
		        data: option
		    });
			}
		});
	}

	function update_kelas() {
		$.ajax({
		    url: base_url + "updateKelas/<?= md5(time()) ?>",
		    data: {
		      id_kelas: id_kelas.val(),
		      kelas_id: kelas_id.val(),
		    },
		    type: "POST",
		    dataType: "JSON",
		    success: function(response) {
		      $('#modal-form').modal('hide');
		      Pace.restart();
		      reload_table();
		      if (response.message) {
		        flashdata(response.message);
		      }
		    }
		});
	}

	function form_validation() {
	  $('#form')
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

</script>