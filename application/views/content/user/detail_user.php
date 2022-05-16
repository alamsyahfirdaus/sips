<section class="content-header">
  <h1><?= $title ?></h1>
  <ol class="breadcrumb">
  	<li><a href="<?= site_url() ?>"><i class="fa fa-users"></i> <?= $folder ?></a></li>
  	<li><a href="javascript:void(0)" onclick="return window.history.back();"><?= $title ?></a></li>
  	<li class="active"><?= $header ?></li>
  </ol>
</section>
<section class="content">
	<div class="row">
	    <div class="col-md-6">
	      <div class="box">
	      	<div class="box-header with-border">
	      	  <h3 class="box-title"><?= $header ?> <?= $title ?></h3>
	      	  <input type="hidden" name="title" value="<?= $title ?>">
	      	  <div class="box-tools pull-right">
	  	      <div class="btn-group">
	  	        <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
	  	          <i class="fa fa-cogs"></i></button>
	  	        <ul class="dropdown-menu" role="menu">
	  	          <li><a href="<?= site_url('user/edit/' . md5($row->user_id)) ?>">Edit Profile</a></li>
	              <li class="divider"></li>
	              <li><a href="javascript:void(0)" id="edit-foto">Edit Foto</a></li>
	              <?php if (@$row->profile_pic): ?>
	    	          <li class="divider"></li>
	    	          <li><a href="javascript:void(0)" onclick="delete_foto();">Hapus Foto</a></li>
	              <?php endif ?>
	  	        </ul>
	  	      </div>
	      	  </div>
	      	</div>
	        <div class="box-body box-profile">
	          <img class="profile-user-img img-responsive img-circle" src="<?= base_url(IMAGE . $this->include->image(@$row->profile_pic)) ?>" alt="User profile picture">
	          <h3 class="profile-username text-center"><?= $row->full_name ?></h3>
	          <!-- <p class="text-muted text-center"><?= $row->type_name ?></p> -->
	          <ul class="list-group list-group-unbordered">
	            <li class="list-group-item">
	              <b>NUPTK</b> <a class="pull-right"><?= $this->include->null($row->no_induk) ?></a>
	            </li>
	            <?php if ($row->user_type_id == 2): ?>
	            	<li class="list-group-item">
	            	  <b>Jenis Kelamin</b> <a class="pull-right"><?= $row->gender == 'L' ? 'Laki-laki' : 'Perempuan' ?></a>
	            	</li>
		            <li class="list-group-item">
		              <b>Tempat/Tgl Lahir</b> <a class="pull-right"><?= $row->tempat_lahir . ', ' . date('d-m-Y', strtotime($row->tanggal_lahir)) ?></a>
		            </li>
	            <?php endif ?>
	            <li class="list-group-item">
	              <b>Email</b> <a class="pull-right"><?= $this->include->null($row->email) ?></a>
	            </li>
	            <li class="list-group-item">
	              <b>No. Handphone</b> <a class="pull-right"><?= $this->include->null($row->phone) ?></a>
	            </li>
	            <?php if ($row->user_type_id == 2): ?>
	            	<li class="list-group-item">
		              <b>Status Kepegawaian</b> <a class="pull-right"><?= $this->include->null($row->status_guru) ?></a>
		            </li>
		            <li class="list-group-item">
		              <div class="form-group">
		                <label for="">Alamat</label>
		                <textarea name="" id="" class="form-control" disabled=""><?= $this->include->null($row->alamat) ?></textarea>
		              </div>
		            </li>
	            <?php endif ?>
	            <!-- <li class="list-group-item">
	              <b>Agama</b> <a class="pull-right"><?= $this->include->null($row->agama) ?></a>
	            </li> -->
	            <li class="list-group-item">
	              <b>Terakhir Login</b> <a class="pull-right"><?= $this->include->date($row->last_active) ?></a>
	            </li>
	          </ul>
	        </div>
	        <div class="box-footer">
	        	<p class="text-center">
	        	   <small class="text-muted">Registrasi Tanggal, <?= $this->include->date($row->date_created) ?></small>
	        	 </p>
	        </div>
	      </div>
	  	</div>
	</div>
</section>

<form action="<?= site_url('user/changeFoto/' . md5(@$row->user_id)) ?>" id="form-foto" method="post" enctype="multipart/form-data" style="display: none;">
  <input type="file" name="foto">
  <input type="text" name="action" value="user/detail/<?= md5(@$row->user_id) ?>">
</form>

<form action="<?= site_url('user/deleteFoto/' . md5(@$row->user_id)) ?>" id="delete-foto" method="post" style="display: none;">
  <input type="text" name="action" value="user/detail/<?= md5(@$row->user_id) ?>">
</form>

<script type="text/javascript">

	$(document).ready(function() {

    $("#edit-foto").click(function() {
        $('[name="foto"]').click();
    });

    $('[name="foto"]').on('change', function() {
      if ($(this).val() != '') {
        $("#form-foto").submit();
      }
    });

	});

	function delete_foto() {
		Swal.fire({
		  title: '<span style="font-family: serif;">Apakah anda yakin?</span>',
		  text: 'Akan menghapus Foto',
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#00A65A',
		  cancelButtonColor: '#6C757D',
		  confirmButtonText: '<span style="font-family: serif;"><i class="fa fa-angle-double-right"></i> Ya</span>',
		  cancelButtonText: '<span style="font-family: serif;"><i class="fa fa-angle-double-left"></i> Tidak</span>',
		  reverseButtons: true,
		}).then((result) => {
		  if (result.value) {
		    $("#delete-foto").submit();
		  }
		})
	}
</script>