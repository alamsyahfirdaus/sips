<section class="content-header">
  <h1><?= @$title ?>
  	<?php if (@$tapel): ?>
  	 <small>Tahun Pelajaran <?= $tapel ?></small>
  	<?php endif ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-folder-open"></i> <?= @$folder ?></a></li>
    <li class="active"><?= @$title ?></li>
  </ol>
</section>
<section class="content">
	<div class="row">
	  <div class="col-md-12 col-xs-12">
	    <div class="box">
	      <?= $jadwal_siswa; ?>
	    </div>
	  </div>
	</div>
</section>