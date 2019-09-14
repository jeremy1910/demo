require('../../css/Security/login.scss');


$('#submitForgottenPassword').click(submitFormModal);


function submitFormModal(e) {
    e.preventDefault();
    let $form = $("form[name='forgotten_password']");
    $.ajax({
        url: $form.attr('action'),
        method: 'POST',
        data: $form.serialize(),
        beforeSend: function () {
            /*make som animation*/
            let height = $('#modalForgottenPasswordHook').css('height');
            $('#modalForgottenPasswordHook').empty();
            $( '<div class="spinner" style="height: ' + height + '">' +
                '  <div class="bounce1"></div>' +
                '  <div class="bounce2"></div>' +
                '  <div class="bounce3"></div>' +
                '</div>').appendTo('#modalForgottenPasswordHook');

        }
    }).done(function (data) {

            $('#modalForgottenPasswordHook').empty();
            $(data).appendTo('#modalForgottenPasswordHook');
            $('#submitForgottenPassword').click(submitFormModal);
    })
}