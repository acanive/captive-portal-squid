/**
 * Created by ivan on 10-ene-17.
 */
$('.imagenFile').on('change', function () {
    var file = $(this)[0].files[0];
    var mime = file.type;
    var tipo = mime.split("/");
    if (typeof (FileReader) != "undefined") {
        var reader = new FileReader();
        reader.onload = function (e) {
            if (tipo[0] === 'image') {
                $('#img').attr('src', e.target.result);
            }
            else {
                alert('Error');
            }
        };
        reader.readAsDataURL(file);
    }
    else {
        alert("Lo sentimos, su navegador no soporta HTML 5.");
    }
});

$('.message').fadeOut(6000);

// BOOTSTRAP VALIDATOR
jQuery(document).ready(function() {
    $('#changePassword').bootstrapValidator({
        feedbackIcons: {
            valid: 'fa fa-check',
            invalid: 'fa fa-warning',
            validating: 'fa glyphicon-refresh'
        },
        fields: {
            'password[password][first]': {
                validators: {
                    regexp: {
                        regexp: /(?=^.{8,}$)((?=.*\d)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/,
                        message: 'Su contraseña debe contener Letras mayúsculas y minúsculas, Números y Caracteres Especiales.'
                    },
                    identical: {
                        field: 'password[password][second]',
                        message: 'Recuerde que debe repetir su nueva contraseña.'
                    }
                }
            },
            'password[password][second]': {
                validators: {
                    regexp: {
                        regexp: /(?=^.{8,}$)((?=.*\d)(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/,
                        message: 'Su contraseña debe contener Letras mayúsculas y minúsculas, Números y Caracteres Especiales.'
                    },
                    identical: {
                        field: 'password[password][first]',
                        message: 'Recuerde que debe repetir su nueva contraseña.'
                    }
                }
            }
        }
    });
});

$(function () {
    $('[data-tooltip="tooltip"]').tooltip()
});

$('#delete').click(function(){

    $('#myModal1').modal('show');
    pathdelete = $(this).data('pathdelete');
});

$('.optiondelete').click(function(){

    if($(this).data('option')== 'yesdelete'){
        window.location.href = pathdelete;
    }else{
        $('#myModal1').modal('hidden');
    }

});