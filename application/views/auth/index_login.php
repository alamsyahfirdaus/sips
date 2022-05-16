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
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg" style="margin-top: 50px;">
                                    <div class="card-header">
                                        <p href="<?= site_url() ?>" id="marquee" style="text-align: center; margin-bottom: 0px;"><?= WEBSITE ?></p>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <img class="" src="<?= $logo ?>" alt="" style="width: 85px; margin-bottom: 12px;">
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
                </main>
            </div>
        </div>
        <script src="<?= base_url(JS . 'jquery.min.js') ?>"></script>
        <script src="<?= base_url(JS . 'bootstrap.bundle.min.js') ?>"></script>
        <script src="<?= base_url(JS . 'jquery.validate.min.js') ?>"></script>
        <script src="<?= base_url(JS . 'additional-methods.min.js') ?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#form .form-control').keyup(function() {
                    $(this).removeClass('is-invalid');
                    $(this).nextAll('.help-block').text('');
                });
                $.validator.setDefaults({
                    submitHandler: function() {
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
                    url : "<?= site_url('login') ?>",
                    type: "POST",
                    data: $('#form').serialize(),
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status) {
                            window.location.href = "<?= site_url('role') ?>";
                            // $('#form')[0].reset();
                            // var color = '#06A65A';
                            // flashdata(color, response.message);
                            // setTimeout(function(){ 
                            //     window.location.href = "<?= site_url('role') ?>";
                            // }, 3250);
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
              window.location.href = "<?= site_url('recovery') ?>";
            }
        </script>
    </body>
</html>