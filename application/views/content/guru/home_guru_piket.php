<section class="content-header">
  <h1><?= $title ?>
  <?php if (@$tapel) echo '<small>Tahun Pelajaran '. $tapel .'</small>'; ?>
  </h1>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-7 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar Kelas</h3>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table id="table" class="table table-condensed" style="width: 100%;">
              <thead>
                <th style="width: 5%; text-align: center;">No</th>
                <th>Kelas</th>
                <th>Wali<span style="color: #FFFFFF;">_</span>Kelas</th>
                <th>Anggota<span style="color: #FFFFFF;">_</span>Kelas</th>
                <th style="width: 20%; text-align: center;">Presensi<span style="color: #FFFFFF;">_</span>Siswa</th>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-5 col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Profile</h3>
          <div class="box-tools pull-right">
            <div class="btn-group">
              <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-cogs"></i></button>
              <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:void(0)" onclick="edit_profile();">Edit Profile</a></li>
                <li class="divider"></li>
                <li><a href="javascript:void(0)" onclick="edit_password();">Edit Password</a></li>
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
          <?php if (isset($row->profile_pic)): ?>
          <img class="profile-user-img img-responsive img-circle" src="<?= base_url(IMAGE . $this->include->image(@$row->profile_pic)) ?>" alt="User profile picture">
          <?php else: ?>
            <?php
            $full_name = explode(' ', $row->full_name);
            $foto_profile = isset($full_name[0]) ? substr(strtoupper($full_name[0]), 0, 1) : '';
            $foto_profile .= isset($full_name[1]) ? substr(strtoupper($full_name[1]), 0, 1) : '';
            ?>
            <table class="table">
              <tr><td style="border-top: none; text-align: center;">
                  <span class="foto_profile" style="width: 100px; height: 100px; font-size: 28px; border: 1px solid #00A65A;"><?= $foto_profile ?></span>
                </td></tr>
            </table>
          <?php endif ?>
          <h3 class="profile-username text-center"><?= $row->full_name ?></h3>
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item">
              <b>NUPTK</b> <a class="pull-right"><?= $this->include->null(@$row->no_induk) ?></a>
            </li>
            <?php if (@$row->user_type_id == 2): ?>
              <li class="list-group-item">
                <b>Jenis Kelamin</b> <a class="pull-right"><?= $row->gender == 'L' ? 'Laki-Laki' : 'Perempuan' ?></a>
              </li>
              <li class="list-group-item">
                <b>Tempat/Tgl Lahir</b> <a class="pull-right"><?= $row->tempat_lahir . ', ' . date('d-m-Y', strtotime($row->tanggal_lahir)) ?></a>
              </li>
              <li class="list-group-item">
                <b>Status Kepegawaian</b> <a class="pull-right"><?= $row->status_guru ?></a>
              </li>
            <?php endif ?>
            <li class="list-group-item">
              <b>Email</b> <a class="pull-right"><?= $this->include->null($row->email) ?></a>
            </li>
            <li class="list-group-item">
              <b>No. Handphone</b> <a class="pull-right"><?= $this->include->null($row->phone) ?></a>
            </li>
            <li class="list-group-item">
              <div class="form-group">
                <label for="">Alamat</label>
                <textarea name="" id="" class="form-control" disabled=""><?= $this->include->null($row->alamat) ?></textarea>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modal-edit_profile">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <form action="<?= site_url('home/saveProfile/'. md5(@$row->user_id)) ?>" method="post" id="form-edit_profile">
        <div class="modal-body">
            <div class="form-group">
              <label for="no_induk">NUPTK</label>
              <input type="text" class="form-control" id="no_induk" name="no_induk" placeholder="NUPTK" autocomplete="off" value="<?= @$row->no_induk ?>">
              <small class="help-block"></small>
            </div>
            <div class="form-group">
              <label for="full_name">Nama Lengkap</label>
              <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nama Lengkap" autocomplete="off" value="<?= @$row->full_name ?>">
              <small class="help-block"></small>
            </div>
            <div class="form-group">
              <label for="gender">Jenis Kelamin</label>
              <select name="gender" id="gender" class="form-control select2">
                <option value="">Jenis Kelamin</option>
                <?php foreach ($this->include->gender() as $key => $value): ?>
                  <option value="<?= $key ?>" <?php if($key == @$row->gender) echo 'selected'; ?>><?= $value ?></option>
                <?php endforeach ?>
              </select>
              <small class="help-block"></small>
            </div>
            <div class="form-group">
              <label for="tempat_lahir">Tempat Lahir</label>
              <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" autocomplete="off" value="<?= @$row->tempat_lahir ?>">
              <small class="help-block"></small>
            </div>
            <div class="form-group">
              <label for="tanggal_lahir">Tanggal Lahir</label>
              <input type="text" class="form-control" id="datepicker" name="tanggal_lahir" placeholder="Tanggal Lahir" autocomplete="off" value="<?php if(@$row->tanggal_lahir) echo date('m/d/Y', strtotime(@$row->tanggal_lahir)) ?>">
              <small class="help-block"></small>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Email" autocomplete="off" value="<?= @$row->email ?>">
              <small class="help-block"></small>
            </div>
            <div class="form-group">
              <label for="phone">No. Handphone</label>
              <input type="text" class="form-control" id="phone" name="phone" placeholder="No. Handphone" autocomplete="off" value="<?= @$row->phone ?>">
              <small class="help-block"></small>
            </div>
            <div class="form-group">
              <label for="alamat">Alamat</label>
              <textarea name="alamat" id="alamat" class="form-control" placeholder="Alamat"><?= @$row->alamat ?></textarea>
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

<div class="modal fade" id="modal-edit_password">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <form action="<?= site_url('home/changePassword/'. md5(@$row->user_id)) ?>" method="post" id="form-edit_password">
        <div class="modal-body">
          <div class="form-group">
            <label for="old_password">Password Lama</label>
            <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Password Lama" autocomplete="off">
            <small class="help-block"></small>
          </div>
          <div class="form-group">
            <label for="new_password1">Password Baru</label>
            <input type="password" class="form-control" id="new_password1" name="new_password1" placeholder="Password Baru" autocomplete="off">
            <small class="help-block"></small>
          </div>
          <div class="form-group">
            <label for="new_password2">Konfirmasi Password</label>
            <input type="password" class="form-control" id="new_password2" name="new_password2" placeholder="Konfirmasi Password (Ulangi)" autocomplete="off">
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

<form action="<?= site_url('user/changeFoto/' . md5(@$row->user_id)) ?>" id="form-foto" method="post" enctype="multipart/form-data" style="display: none;">
  <input type="file" name="foto">
  <input type="text" name="action" value="home">
</form>

<form action="<?= site_url('user/deleteFoto/' . md5(@$row->user_id)) ?>" id="delete-foto" method="post" style="display: none;">
  <input type="text" name="action" value="home">
</form>

<script type="text/javascript">
  $(document).ready(function() {
    table = $('#table').DataTable({
        "processing": false,
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
          "url": "<?= site_url('home/showListKelas/'. md5($id_tahun_pelajaran)) ?>",
          "type": "POST",
          "data": function(data) {
            data.semester = "<?= $semester ?>";
          },
        },
        "drawCallback": function(settings) {

        },
        "columnDefs": [{ 
          "targets": [-1],
          "orderable": false,
        }],
    });

    $('#form-edit_profile')
    .bootstrapValidator({
      excluded: ':disabled',
      fields: {
        no_induk: {
          validators: {
              // notEmpty: {
              //     message: 'NUPTK harus diisi'
              // },
              numeric: {
                message: 'NUPTK tidak valid'
              },
          }
        },
        full_name: {
          validators: {
              notEmpty: {
                  message: 'Nama Lengkap harus diisi'
              },
          }
        },
        gender: {
          validators: {
              notEmpty: {
                  message: 'Jenis Kelamin harus diisi'
              },
          }
        },
        tempat_lahir: {
          validators: {
              notEmpty: {
                  message: 'Tempat Lahir harus diisi'
              },
          }
        },
        agama: {
          validators: {
              notEmpty: {
                  message: 'Agama harus diisi'
              },
          }
        },
        email: {
          validators: {
              notEmpty: {
                  message: 'Email harus diisi'
              },
              emailAddress: {
                      message: 'Email tidak valid'
                  }
          }
        },
        phone: {
          validators: {
              notEmpty: {
                  message: 'No. Handphone harus diisi'
              },
              numeric: {
                message: 'No. Handphone tidak valid'
              },
          }
        },
      }
    })
    .on('success.form.bv', function(e) {
        e.preventDefault();
        var form = '#form-edit_profile';
        form_data(form);
    });

    $('#form-edit_password')
    .bootstrapValidator({
      excluded: ':disabled',
      fields: {
        old_password: {
          validators: {
              notEmpty: {
                  message: 'Password Lama harus diisi'
              },
          }
        },
        new_password1: {
          validators: {
              notEmpty: {
                  message: 'Password Baru harus diisi'
              },
          }
        },
        new_password2: {
          validators: {
              notEmpty: {
                  message: 'Konfirmasi Password harus diisi'
              },
              identical: {
                  field: 'new_password1',
                  message: 'Konfirmasi Password salah'
              }
          }
        },
      }
    })
    .on('success.form.bv', function(e) {
        e.preventDefault();
        var form = '#form-edit_password';
        form_data(form);
    });

    $("#edit-foto").click(function() {
        $('[name="foto"]').click();
    });

    $('[name="foto"]').on('change', function() {
      if ($(this).val() != '') {
        $("#form-foto").submit();
      }
    });

  });

  function edit_profile() {
    $('.modal-title').text('Edit Profile');
    $('#modal-edit_profile').modal('show');
  }

  function action_success() {
    $('#modal-edit_profile').modal('hide');
    $('#modal-form').modal('hide');
    $(window).scrollTop(0);
    setTimeout(function(){ 
        window.location.reload();
    }, 1575);
  }

  function edit_password() {
    $('#form-edit_password')[0].reset();
    $('#form-edit_password').data('bootstrapValidator').resetForm();
    $('.modal-title').text('Edit Password');
    $('#modal-edit_password').modal('show');
  }

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