$(document).ready(function () {

    $('#form input').on('keyup', function () {
        $(this).removeClass('is-invalid');
        $(this).nextAll('.help-block').text('');
    });

    $.validator.setDefaults({
        submitHandler: function () {
          password_recovery();
        }
    });

  $('#form').validate({
    rules: {
      email: {
        required: true,
        email: true,
      },
    },
    messages: {
      email: {
        required: "Email harus diisi",
        email: "Email tidak valid"
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

function password_recovery() {
    $.ajax({
        url : index + "recovery",
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(response) {
            if (response.status) {
                $('#form')[0].reset();
                flashdata(response.type, response.message);
            } else {
                $.each(response.errors, function (key, val) {
                    $('[name="' + key + '"]').addClass('is-invalid');
                    $('[name="'+ key +'"]').next('.help-block').text(val);
                });
            }
        }
    });
}

function flashdata(type, message) {
  $('<div class="alert" role="alert" style="background-color: '+ type +'; color: #FFFFFF; font-weight: bold; height: 38px; padding-top: 6px;">' + message + '</div>').show().appendTo('#response');
  
   $(".alert").delay(2750).slideUp("slow", function(){
    $(this).remove();
  });
}

function logged_in() {
  window.location.href = index + "login";
}