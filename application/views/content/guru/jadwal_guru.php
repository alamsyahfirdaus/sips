<section class="content-header">
  <h1><?= @$title ?>
  	<?php if (@$tapel): ?>
  	 <small>Tahun Pelajaran <?= $tapel ?></small>
  	<?php endif ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url() ?>"><i class="fa fa-calendar"></i> <?= @$title ?></a></li>
  </ol>
</section>
<section class="content">
	<div class="row">
	  <div class="col-md-12 col-xs-12">
	    <div class="box box-success">
	      <?= $jadwal; ?>
	    </div>
	  </div>
	</div>
</section>