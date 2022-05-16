<section class="content-header">
  <h1><?= $title ?>
    <?php if (@$tapel): ?>
     <small>Tahun Pelajaran <?= $tapel ?></small>
    <?php endif ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="javascript:void(0)"><i class="fa fa-home"></i> <?= $title ?></a></li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="<?= $col ?> col-xs-12">
      <div class="box">
        <?= $jadwal_mengajar; ?>
      </div>
    </div>
    <div class="col-md-4 col-xs-12" <?= $style ?>>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-bullhorn"></i> Pengumuman</h3>
        </div>
        <div class="box-body">
          <?= $pengumuman; ?>
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
          <b id="judul"></b>
        </div>
        <div id="modal-img">
          <img class="img-responsive" id="image" src="" alt="Photo" style="display: block; margin-left: auto; margin-right: auto; max-width: 575px; max-height: 175px;">
          <hr style="color: #333333; border-top: 1px solid #F4F4F4; margin-top: 15px; margin-bottom: 15px;">
        </div>
        <p id="pengumuman"></p>
      </div>
    </div>
  </div>
</div>

<style type="text/css">
  .callout{
    border-left: 3px solid #00A65A; 
    border-right: 1px solid #EEEEEE; 
    border-top: 1px solid #EEEEEE; 
    border-bottom: 1px solid #EEEEEE;
  } 
  .callout b{
      display: block;
    }
</style>


<script type="text/javascript">
  function lihat_pengumuman(id) {
    var judul       = $('[name="judul_'+ id +'"]').val();
    var gambar      = $('[name="gambar_'+ id +'"]').val();
    var src         = $('[name="img_src_'+ id +'"]').val();
    var pengumuman  = $('[name="pengumuman_'+ id +'"]').val();
    $('#image').attr('src', src);
    $('#judul').text(judul);
    $('#pengumuman').html(pengumuman);
    $('.modal-title').text('Pengumuman');
    if (gambar) {
      $('#modal-img').show();
    } else {
      $('#modal-img').hide();
    }
    $('#modal-form').modal('show');
    Pace.restart();
  }
</script>