$(document).ready(function () {

    $('#form input').on('keyup', function () {
        $(this).removeClass('is-invalid');
        $(this).nextAll('.help-block').text('');
    });

    $.validator.setDefaults({
        submitHandler: function () {
          change_password();
        }
    });

  $('#form').validate({
    rules: {
      password1: {
        required: true,
        minlength: 8
      },
      password2: {
        required: true,
        minlength: 8,
        equalTo : "#password1"
      },
    },
    messages: {
      password1: {
        required: "Password Baru harus diisi",
        minlength: "Password minimal 8 karakter"
      },
      password2: {
        required: "Konfirmasi Password harus diisi",
        minlength: "Konfirmasi Password minimal 8 karakter",
        equalTo : "Konfirmasi Password tidak sama"
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

function change_password() {
    $.ajax({
        url : index + "change",
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(response) {
            if (response.status) {
                $('#form')[0].reset();
                flashdata(response.message);
                setTimeout(function(){ 
                    logged_in();
                }, 3250);
            } else {
                $.each(response.errors, function (key, val) {
                    $('[name="' + key + '"]').addClass('is-invalid');
                    $('[name="'+ key +'"]').next('.help-block').text(val);
                });
            }
        }
    });
}

function flashdata(message) {
  $('<div class="alert" role="alert" style="background-color: #00A65A; color: #FFFFFF; font-weight: bold; height: 38px; padding-top: 6px;">' + message + '</div>').show().appendTo('#response');
  
   $(".alert").delay(2750).slideUp("slow", function(){
    $(this).remove();
  });
}

function logged_in() {
  window.location.href = index + "login";
}