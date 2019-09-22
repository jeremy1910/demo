import {eventSuppr} from "../AdminDashboard/AdminDashboard";

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




});

