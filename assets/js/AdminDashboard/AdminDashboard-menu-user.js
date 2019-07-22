import {eventSuppr} from "./AdminDashboard";
import {displayFlashMessageSuccess, displayPagination} from '../globalFunctions';

const NB_COL = 3;
const COL_WIDTH = 100/NB_COL;

$(document).ready(function () {


    $('#selecter-user').selectpicker().change(function () {
        $('#user_filter_nbResult').val('');

        $('#user_filter_nbResult').val($("#selecter-user option:selected").text());
        menuUserSendAjaxFormFilter();
    });

    $("form[name='user_filter']").submit(function (e) {
        e.preventDefault();
        menuUserSendAjaxFormFilter();

    });

    $('#user_add_submit').click(function (e) {
        e.preventDefault();
        if ($('#user_add_username').val() != '')
        {
            $.get('/addUserA?name='+$('#user_add_username').val()+'&role='+$('#user_add_roles option:selected').val()+'&mail='+$('#user_add_adresseMail').val()+'&enable='+$("input[name='user_add[enable]']:checked").val())
                .done(function (data, textStatus, jqXDR) {
                    if (data[0] === true){
                        displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');
                        menuUserSendAjaxFormFilter()
                    }
                    else{
                        displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');
                    }
                });
        }

    });



    function menuUserSendAjaxFormFilter() {
        let $form = $("form[name='user_filter']");

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize()
        })
            .done(function (data, textStatus, jqXDR) {
                menuUserDisplayResult(data)
            });
    }

    function menuUserDisplayResult(data) {
        let pageActive = $('#user_filter_pageSelected').val();
        let result = JSON.parse(data);

        $('#table-body-user').empty();
        $.each(result.result, function (i, item) {
            menuUserCreateTableLine(item);

        });
        $('.js-btn-suppr-user').each(function () {
            $(this).click(function () {
                $('#buttonValidDelete').attr('href', $(this).attr('href'));
                $('#buttonValidDelete').click(function (e) {
                    e.preventDefault();
                    eventSuppr('user');

                    menuUserSendAjaxFormFilter();

                });

            });
        });
        $('.js-btn-edit-user').each(function () {
            $(this).click(function (e) {
                e.preventDefault();
                let href = $(this).attr('href');
                let num = $(this).attr('num');
                let text = $('#userName'+num).text();


                $('#userName'+num).text('');
                let $btn = $('<a href="'+ href +'" class="btn btn-primary js-btn-valide-edit">Modifier</a>');
                $btn.click(function (e) {
                    e.preventDefault();
                    let newLibele = $('#inputNewUser'+ num).val();
                    $.getJSON(href+'&name='+newLibele)
                        .done(function (data, textStatus, jqXDR) {
                            if (data[0] === true){
                                displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');
                                menuUserSendAjaxFormFilter();
                            }
                        });
                });
                $btn.appendTo($('<div class="col-2"></div></div>').appendTo($('<div class="row"><div class="col-10"><input id="inputNewUser'+ num +'" type="text" class="form-control" placeholder="'+ text +'"></div>').appendTo('#userName'+num)));
                //$('<a href="'+ href +'" class="btn btn-primary js-btn-valide-edit">Modifier</a>').appendTo('#categoryLibele'+num);
            })
        });


        displayPagination(result.nbPage, pageActive, function (e) {
            e.preventDefault();
            $('#user_filter_pageSelected').val($(this).children().text());
            menuUserSendAjaxFormFilter();
        }, function (e) {
            e.preventDefault();
            $('#user_filter_pageSelected').val(Number(pageActive) - 1);
            menuUserSendAjaxFormFilter();
        }, function (e) {
            e.preventDefault();
            $('#user_filter_pageSelected').val(Number(pageActive) + 1);
            menuUserSendAjaxFormFilter();
        }, 'user', $('#user_filter_pageSelected'))
    }


    function menuUserCreateTableLine(item) {
        console.log(item);
        let $t = $('#table-body-user');

        $('<tr>' +
            '<th style="width:' + COL_WIDTH + '%" scope="row">' + item.id + '</th>' +
            '<td style="width:' + COL_WIDTH + '%" id="userName' + item.id + '">' + item.username + '</td>' +
            '<td style="width:' + COL_WIDTH + '%" ><div class="btn-group"><a href="/edtUserA?id=' + item.id + '" num="' + item.id + '" class="btn btn-secondary js-btn-edit-user">Modifier le nom</a>' +
            '<a href="/delUserA?id=' + item.id + '"class="btn btn-danger js-btn-suppr-user" data-toggle="modal" data-target="#modalValiddelete">supprimer</a></div></td>' +
            '</tr>').appendTo($t).hide().fadeIn(500);

    }

});



