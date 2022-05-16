<section class="content-header">
  <h1><?= @$title ?></h1>
  <ol class="breadcrumb">
  	<li><a href="<?= site_url() ?>"><i class="fa fa-folder-open"></i> <?= @$folder ?></a></li>
  	<li><a href="javascript:void(0)" onclick="return window.history.back();"><?= @$title ?></a></li>
  	<li class="active"><?= @$header ?></li>
  </ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="box">
				<div class="box-header with-border">
				  <h3 class="box-title">Detail Siswa</h3>
				</div>
				<div class="row">
					<div class="col-md-6 col-xs-12">
						<div class="box-body box-profile">
							<img class="profile-user-img img-responsive img-circle" src="<?= base_url(IMAGE . $this->include->image(@$row->profile_pic)) ?>" alt="User profile picture">
							<h3 class="profile-username text-center"><?= @$row->full_name ?></h3>
							<ul class="list-group list-group-unbordered">
							  <li class="list-group-item">
							    <b>Nomor Induk Siswa (NIS)</b> <a class="pull-right"><?= $this->include->null(@$row->no_induk) ?></a>
							  </li>
							  <li class="list-group-item">
							    <b>Jenis Kelamin</b> <a class="pull-right"><?= @$row->gender == 'L' ? 'Laki-laki' : 'Perempuan' ?></a>
							  </li>
							  <li class="list-group-item">
							    <b>Tempat/Tgl Lahir</b> <a class="pull-right"><?= @$row->tempat_lahir . ', ' . date('d-m-Y', strtotime(@$row->tanggal_lahir)) ?></a>
							  </li>
							  <li class="list-group-item">
							    <b>Kelas</b> <a class="pull-right"><?= $this->include->null(@$row->nama_kelas) ?></a>
							  </li>
							  <li class="list-group-item">
							    <b>Status</b> <a class="pull-right"><?= $this->include->statusSiswa(@$row->is_aktif) ?></a>
							  </li>
							  <li class="list-group-item">
							    <b>Email</b> <a class="pull-right"><?= $this->include->null(@$row->email) ?></a>
							  </li>
							  <li class="list-group-item">
							    <b>No. Handphone</b> <a class="pull-right"><?= $this->include->null(@$row->phone) ?></a>
							  </li>
							  <li class="list-group-item">
							    <div class="form-group">
							      <label for="">Alamat</label>
							      <textarea name="" id="" class="form-control" disabled=""><?= $this->include->null(@$row->alamat) ?></textarea>
							    </div>
							  </li>
							</ul>
						</div>
					</div>
					<div class="col-md-6 col-xs-12">
						<div class="nav-tabs-custom">
						  <ul class="nav nav-tabs pull-left">
						    <li class="active"><a href="#tab1" data-toggle="tab">Biodata Ayah</a></li>
						    <li><a href="#tab2" data-toggle="tab">Biodata Ibu</a></li>
						    <li><a href="#tab3" data-toggle="tab">Biodata Wali</a></li>
						  </ul>
						  <div class="tab-content no-padding">
						  	<div class="chart tab-pane active" id="tab1">
						  		<div class="box-body box-profile">
						  			<ul class="list-group list-group-unbordered">
						  			  <li class="list-group-item">
						  			    <b>Nama Ayah</b> <a class="pull-right"><?= $this->include->null(@$row->nama_ayah) ?></a>
						  			  </li>
						  			  <li class="list-group-item">
						  			    <b>Pendidikan Terakhir</b> <a class="pull-right"><?= $this->include->null(@$row->pendidikan_ayah) ?></a>
						  			  </li>
						  			  <li class="list-group-item">
						  			    <b>Pekerjaan</b> <a class="pull-right"><?= $this->include->null(@$row->pekerjaan_ayah) ?></a>
						  			  </li>
						  			  <li class="list-group-item">
						  			    <b>Penghasilan</b> <a class="pull-right"><?= $this->include->null(@$row->penghasilan_ayah) ?></a>
						  			  </li>
						  			  <li class="list-group-item">
						  			    <b>No. Handphone</b> <a class="pull-right"><?= $this->include->null(@$row->no_hp_ayah) ?></a>
						  			  </li>
						  			  <li class="list-group-item">
						  			    <div class="form-group">
						  			      <label for="">Alamat</label>
						  			      <textarea name="" id="" class="form-control" disabled=""><?= $this->include->null(@$row->alamat_ayah) ?></textarea>
						  			    </div>
						  			  </li>
						  			</ul>
						  		</div>
						  	</div>
						    <div class="chart tab-pane" id="tab2">
						    	<div class="box-body box-profile">
						    		<ul class="list-group list-group-unbordered">
						    		  <li class="list-group-item">
						    		    <b>Nama Ibu</b> <a class="pull-right"><?= $this->include->null(@$row->nama_ibu) ?></a>
						    		  </li>
						    		  <li class="list-group-item">
						    		    <b>Pendidikan Terakhir</b> <a class="pull-right"><?= $this->include->null(@$row->pendidikan_ibu) ?></a>
						    		  </li>
						    		  <li class="list-group-item">
						    		    <b>Pekerjaan</b> <a class="pull-right"><?= $this->include->null(@$row->pekerjaan_ibu) ?></a>
						    		  </li>
						    		  <li class="list-group-item">
						    		    <b>Penghasilan</b> <a class="pull-right"><?= $this->include->null(@$row->penghasilan_ibu) ?></a>
						    		  </li>
						    		  <li class="list-group-item">
						    		    <b>No. Handphone</b> <a class="pull-right"><?= $this->include->null(@$row->no_hp_ibu) ?></a>
						    		  </li>
						    		  <li class="list-group-item">
						    		    <div class="form-group">
						    		      <label for="">Alamat</label>
						    		      <textarea name="" id="" class="form-control" disabled=""><?= $this->include->null(@$row->alamat_ibu) ?></textarea>
						    		    </div>
						    		  </li>
						    		</ul>
						    	</div>
						    </div>
						    <div class="chart tab-pane" id="tab3">
						    	<div class="box-body box-profile">
						    		<ul class="list-group list-group-unbordered">
						    		  <li class="list-group-item">
						    		    <b>Nama Wali</b> <a class="pull-right"><?= $this->include->null(@$row->nama_wali) ?></a>
						    		  </li>
						    		  <li class="list-group-item">
						    		    <b>Pendidikan Terakhir</b> <a class="pull-right"><?= $this->include->null(@$row->pendidikan_wali) ?></a>
						    		  </li>
						    		  <li class="list-group-item">
						    		    <b>Pekerjaan</b> <a class="pull-right"><?= $this->include->null(@$row->pekerjaan_wali) ?></a>
						    		  </li>
						    		  <li class="list-group-item">
						    		    <b>Penghasilan</b> <a class="pull-right"><?= $this->include->null(@$row->penghasilan_wali) ?></a>
						    		  </li>
						    		  <li class="list-group-item">
						    		    <b>No. Handphone</b> <a class="pull-right"><?= $this->include->null(@$row->no_hp_wali) ?></a>
						    		  </li>
						    		  <li class="list-group-item">
						    		    <div class="form-group">
						    		      <label for="">Alamat</label>
						    		      <textarea name="" id="" class="form-control" disabled=""><?= $this->include->null(@$row->alamat_wali) ?></textarea>
						    		    </div>
						    		  </li>
						    		</ul>
						    	</div>
						    </div>
						  </div>
						</div>
						<div class="box-body">
							<ul class="list-group list-group-unbordered">
							  <li class="list-group-item">
							    <b>Tanggal Registrasi</b> <a class="pull-right"><?= $this->include->date(@$row->date_created) ?></a>
							  </li>
							  <li class="list-group-item">
							    <b>Terakhir Login</b> <a class="pull-right"><?= $this->include->datetime(@$row->last_active) ?></a>
							  </li>
							</ul>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<?= BTN_CANCEL ?>
					<div class="btn-group pull-right">
					  <button type="button" class="btn btn-warning btn-sm" style="font-weight: bold;"><i class="fa fa-edit"></i> Edit</button>
					  <button type="button" class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown">
					    <span class="caret"></span>
					    <span class="sr-only">Toggle Dropdown</span>
					  </button>
					  <ul class="dropdown-menu dropdown-menu-right" role="menu">
					    <li><a href="<?= site_url('teacher/update/' . md5(@$row->user_id)) ?>">Siswa</a></li>
					    <li class="divider"></li>
					    <li><a href="<?= site_url('teacher/parents/' . md5(@$row->user_id)) ?>">Orang Tua/Wali</a></li>
					  </ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	function btn_back() {
		window.location.href = "<?= site_url('teacher/classes') ?>"
	}
</script>

<style type="text/css">
	.nav-tabs-custom>.nav-tabs>li.active {
	    border-top: 0px solid #FFFFFF;
	    border-bottom: 3px solid #DDDDDD;
	}
</style>