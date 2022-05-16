<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="icon" href="<?= $logo ?>">
        <title><?= LOGO_SM . ' ' . TITLE ?></title>
        <link rel="stylesheet" href="<?= base_url(CSS . 'styles.css') ?>">
        <link rel="stylesheet" href="<?= base_url(CSS . 'auth-style.css') ?>">
    </head>
    <body>
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-9">
                                <div class="card shadow-lg border-0 rounded-lg" style="margin-top: 40px;">
                                    <div class="card-header">
                                        <marquee id="marquee" behavior="" direction=""><?= WEBSITE .' '. TITLE ?></marquee>
                                        <!-- <p href="<?= site_url() ?>" id="marquee" style="text-align: center; margin-bottom: 0px;"><?= WEBSITE .' '. TITLE ?></p> -->
                                    </div>
                                    <div class="row" style="height: 480.5px;">
                                        <div class="col-lg-6 d-none d-lg-block">
                                            <div id="<?= strtolower(date('l')) ?>" class="carousel slide" data-ride="carousel">
                                              <ol class="carousel-indicators">
                                                <?php for ($i=0; $i < $slider->num_rows(); $i++) { 
                                                    $active = $i < 1 ? 'class="active"' : '';
                                                    echo '<li data-target="#'. strtolower(date('l')) .'" data-slide-to="'. $i .'" '. $active .'></li>';
                                                  } ?>
                                              </ol>
                                              <div class="carousel-inner">
                                                <?php 
                                                  $no = 0;
                                                  foreach ($slider->result() as $row) {
                                                  $active = $no < 1 ? 'active' : '';
                                                  $image = '<div class="carousel-item '. $active .'">';
                                                  $image .= '<img src="'. base_url(IMAGE . $this->include->image($row->gambar)) .'" style="width: 100%; height: 480.5px; display: block; margin-left: auto; margin-right: auto; border-bottom-left-radius: 1%;">';
                                                  $image .= '</div>';
                                                  echo $image;
                                                  $no++;
                                                } ?>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <img class="mb-4" src="<?= $logo ?>" alt="" style="width: 85px;">
                                                    <div id="response"></div>
                                                </div>
                                                <form action="" method="post" id="form">
                                                    <div class="form-group">
                                                        <input class="form-control py-4" id="no_induk" name="no_induk" type="text" placeholder="NUPTK / NIS / Email" autocomplete="off">
                                                        <small class="help-block"></small>
                                                    </div>
                                                    <div class="form-group">
                                                        <input class="form-control py-4" id="password" name="password" type="password" placeholder="Password">
                                                        <small class="help-block"></small>
                                                    </div>
                                                    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-4">
                                                        <button type="submit" class="btn btn-block" id="submit">Login</button>
                                                    </div>
                                                    <hr>
                                                    <div class="text-center">
                                                        <a id="forgot" class="small" href="javascript:void(0)" onclick="forgot_password();">Lupa Password?</a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script type="text/javascript">let index = "<?= base_url() ?>"; var input = "<?= @$label ?>";</script>
        <script src="<?= base_url(JS . 'jquery.min.js') ?>"></script>
        <script src="<?= base_url(JS . 'bootstrap.bundle.min.js') ?>"></script>
        <script src="<?= base_url(JS . 'jquery.validate.min.js') ?>"></script>
        <script src="<?= base_url(JS . 'additional-methods.min.js') ?>"></script>
        <!-- <script src="<?= base_url(JS . 'login.js') ?>"></script> -->
        <script type="text/javascript">
            $(document).ready(function () {

                $('#form input').on('keyup', function () {
                    $(this).removeClass('is-invalid');
                    $(this).nextAll('.help-block').text('');
                });

                $.validator.setDefaults({
                    submitHandler: function () {
                      logged_in();
                    }
                });

              $('#form').validate({
                rules: {
                    no_induk: {
                      required: true,
                    },
                    password: {
                        required: true,
                    },
                },
                messages: {
                    no_induk: {
                        required: "Username harus diisi",
                    },
                    password: {
                        required: "Password harus diisi",
                    },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                  error.addClass('invalid-feedback');
                  element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                  $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                  $(element).removeClass('is-invalid');
                }
              });
            });

            function logged_in() {
                $.ajax({
                    url : index + "login",
                    type: "POST",
                    data: $('#form').serialize(),
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status) {
                            var color = '#06A65A';
                            $('#form')[0].reset();
                            flashdata(color, response.message);
                            setTimeout(function(){ 
                                window.location.href = index + 'home';
                            }, 3250);
                        } else {
                            if (response.errors) {
                                $.each(response.errors, function (key, val) {
                                    $('[name="' + key + '"]').addClass('is-invalid');
                                    $('[name="'+ key +'"]').next('.help-block').text(val);
                                });
                            } else {
                                var color = '#DC3545';
                                $('#form')[0].reset();
                                flashdata(color, response.message);
                            }
                        }
                    }
                });
            }

            function flashdata(color, message) {
              $('<div class="alert" role="alert" style="background-color: '+ color +'; color: #FFFFFF; font-weight: bold; height: 50px; padding-top: 12px;  padding-left: 12px; text-align: left;">' + message + '</div>').show().appendTo('#response');
              
               $(".alert").delay(2750).slideUp("slow", function(){
                $(this).remove();
              });
            }

            function forgot_password() {
              window.location.href = index + "login/recovery";
            }
        </script>
    </body>
</html>