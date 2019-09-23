import {eventSuppr} from "../AdminDashboard/AdminDashboard";
import {displayFlashMessageSuccess} from "../globalFunctions";

require('../../css/profil/profil.css');

$(document).ready(function () {

    $('.js-popover').on('shown.bs.popover', function(){

        console.log('toto');
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
                        menuUserSendAjaxFormFilter()

                    }else {
                        $('#modal_edt_user').empty();
                        $('#modal_edt_user').append(data);
                        $('#resetUserModel').modal('toggle')
                        $('#reset_password_user_submit').click(function (e) {
                            e.preventDefault();
                            menuUserSendAjaxFormUserReset();
                        })
                    }
                });
        })
    });


});

