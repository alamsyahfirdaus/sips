<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
  	<li><a href="<?= site_url() ?>"><i class="fa fa-users"></i> <?= $folder ?></a></li>
  	<li class="active"><?= $title ?></li>
  </ol>
</section>
<section class="content">
	<div class="row">
	  <div class="col-md-12 col-xs-12">
	    <div class="box">
	      <div class="box-header with-border">
	        <h3 class="box-title">Daftar <?= $title ?></h3>
	        <div class="box-tools pull-right">
	          <a href="<?= site_url('user/add/' . md5($user_type_id)) ?>" class="btn btn-box-tool" data-toggle="tooltip">
	            <i class="fa fa-user-plus"></i></a>
	        </div>
	      </div>
	      <div class="box-body table-responsive">
	      	<table id="table" class="table table-hover" style="width: 100%">
	      	  <thead>
	      	    <tr>
	      	      <th width="5%" class="text-center">No</th>
	      	      <th>NUPTK</th>
	      	      <th>Nama Lengkap</th>
	      	      <th>Jenis Kelamin</th>
	      	      <th>Tempat/Tgl Lahir</th>
	      	      <th>Status Kepegawaian</th>
	      	      <th class="text-center">Aksi</th>
	      	    </tr>
	      	  </thead>
	      	</table>
	      </div>
	    </div>
	  </div>
	</div>
</section>

<script type="text/javascript">
	var base_url  = index + "user/";

	$(document).ready(function() {
	  var url_table = "user/showGuru";
	  var targets = [1, 2, 3, 4, 5, 6];
	  set_datatable(url_table, targets);

	});
	
	function delete_data(id) {
	  var text  = "Akan menghapus " + title;
	  var url   = base_url + "delete/" + id;
	  confirm_delete(text, url);
	}

	function success_delete() {
	  table.ajax.reload();
	}
</script>