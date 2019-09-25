import {eventSuppr} from "../AdminDashboard/AdminDashboard";
import {displayFlashMessageSuccess} from "../globalFunctions";

require('../../css/profil/profil.css');

$(document).ready(function () {

    $('.js-popover').on('shown.bs.popover', function(){


        $('.js-btn-suppr').click(function (e) {
            e.preventDefault();
            let url = $(this).attr('href');


            $('#modalValiddelete').modal({
                keyboard: false
            });
            $('#buttonValidDelete').attr('href', url);
            $('#buttonValidDelete').click(function (e) {
                e.preventDefault();
                eventSuppr('article');

                let id = url.substr(16);
                $('#card-'+id).remove();


            });
        });

    });


    $('.js-btn-reset-user').each(function (e) {
        $(this).click(function (e) {
            e.preventDefault();
            let href = $(this).attr('href');
            $.ajax(href)
                .done(function (data, textStatus, jqXDR) {
                    if (data[0] === false){
                        displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');

                    }else {
                        $('#modal_encre_user').empty();
                        $('#modal_encre_user').append(data);
                        $('#resetUserModel').modal('toggle')
                        $('#reset_password_user_submit').click(function (e) {
                            e.preventDefault();
                            menuUserSendAjaxFormUserReset();
                        })
                    }
                });
        })
    });

    function menuUserSendAjaxFormUserReset(){
        let $form = $("form[name='reset_password_user']");
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize()
        })
            .done(function (data, textStatus, jqXDR) {
                if (data[0] === true){
                    displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');
                    $('#resetUserModel').modal('hide');
                    menuUserSendAjaxFormFilter()

                }
                else{

                    $('#modal_edt_user').empty();
                    $('#modal_edt_user').append(data);
                    $('#resetUserModel').modal('toggle');
                    $('#reset_password_user_submit').click(function (e) {
                        e.preventDefault();
                        menuUserSendAjaxFormUserReset();
                    })
                }
            });
    }

    $('.js-btn-change-mail').each(function (e) {
        $(this).click(function (e) {
            e.preventDefault();
            let href = $(this).attr('href');
            $.ajax(href)
                .done(function (data, textStatus, jqXDR) {
                    if (data[0] === false){
                        displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');

                    }else {
                        $('#modal_encre_user').empty();
                        $('#modal_encre_user').append(data);
                        $('#changeUserMailModel').modal('toggle')
                        $('#user_add_submit').click(function (e) {
                            e.preventDefault();
                            menuUserSendAjaxFormUserChangeMail();
                        })
                    }
                });
        })
    });


    function menuUserSendAjaxFormUserChangeMail(){
        let $form = $("form[name='user_add']");
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize()
        })
            .done(function (data, textStatus, jqXDR) {
                if (data[0] === true){
                    displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');
                    $('#resetUserModel').modal('hide');


                }
                else{
                    $('#modal_edt_user').empty();
                    $('#modal_edt_user').append(data);
                    $('#changeUserMailModel').modal('toggle');
                    $('#user_add_submit').click(function (e) {
                        e.preventDefault();
                        menuUserSendAjaxFormUserReset();
                    })
                }
            });
    }

});

