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
                                <div style="margin-top: 20%;">
                                    <div class="card shadow-lg border-0 rounded-lg">
                                        <div class="card-header">
                                            <p href="<?= site_url() ?>" id="marquee" style="text-align: center; margin-bottom: 0px;"><?= WEBSITE ?></p>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center">
                                                <img class="" src="<?= $logo ?>" alt="" style="width: 85px; margin-bottom: 12px;">
                                                <div id="response"></div>
                                            </div>
                                            <?php
                                            $loginas = '<hr>';
                                            foreach ($role as $row) {
                                                $type_name = $row->user_type_id == 2 ? 'Guru / Wali Kelas' : $row->type_name;

                                                $loginas .= '<div class="form-group d-flex align-items-center justify-content-between">';
                                                $loginas .= '<button type="button" class="btn btn-block" onclick="submit_form('. "'" . md5($row->user_type_id) . "'" .')">'. $type_name .'</button>';
                                                $loginas .= '</div>';
                                            }
                                            $loginas .= '<form action="'. site_url('role') .'" method="post" id="form" style="display: none;">';
                                            $loginas .= '<input type="text" name="user_type_id" value="">';
                                            $loginas .= '</form>';
                                            echo $loginas;
                                            ?>
                                            <script src="<?= base_url(JS . 'jquery.min.js') ?>"></script>
                                            <script type="text/javascript">
                                                function submit_form(id) {
                                                    $('[name="user_type_id"]').val(id);
                                                    $('.btn-block').attr('disabled', true);
                                                    var color = '#06A65A';
                                                    var message = 'LOGIN BERHASIL!';
                                                    flashdata(color, message);
                                                    setTimeout(function(){ 
                                                        $('#form').submit();
                                                    }, 3525);
                                                }
                                                function flashdata(color, message) {
                                                  $('<div class="alert" role="alert" style="background-color: '+ color +'; color: #FFFFFF; font-weight: bold; height: 50px; padding-top: 12px;  padding-left: 12px; text-align: center;">' + message + '</div>').show().appendTo('#response');
                                                   $(".alert").delay(2750).slideUp("slow", function(){
                                                    $(this).remove();
                                                  });
                                                }
                                            </script>
                                        </div>
                                        <div class="card-footer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>