<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="icon" href="<?= $logo ?>">
        <title><?= LOGO_SM . ' - ' . TITLE ?></title>
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
                                <div class="card shadow-lg border-0 rounded-lg" style="margin-top: 55px;">
                                    <div class="card-header">
                                        <marquee id="marquee" behavior="" direction=""><?= WEBSITE .' '. TITLE ?></marquee>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <img class="mb-4" src="<?= $logo ?>" alt="" style="width: 85px;">
                                            <div id="response"></div>
                                        </div>
                                        <form action="" method="post" id="form">
                                            <div class="form-group">
                                                <input class="form-control py-4" id="password1" name="password1" type="password" placeholder="Password Baru" autocomplete="off">
                                                <small class="help-block"></small>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control py-4" id="password2" name="password2" type="password" placeholder="Konfirmasi Password (Ulangi)" autocomplete="off">
                                                <small class="help-block"></small>
                                            </div>
                                            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-4">
                                                <input type="hidden" name="passconf" value="<?= @$user_id ?>">
                                                <button type="submit" class="btn btn-block" id="submit">Reset Password</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="javascript:void(0)" onclick="logged_in();">Sudah punya akun? Login!</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script type="text/javascript">let index = "<?= base_url() ?>"</script>
        <script src="<?= base_url(JS . 'jquery.min.js') ?>"></script>
        <script src="<?= base_url(JS . 'jquery.validate.min.js') ?>"></script>
        <script src="<?= base_url(JS . 'additional-methods.min.js') ?>"></script>
        <script src="<?= base_url(JS . 'reset-password.js') ?>"></script>
    </body>
</html>