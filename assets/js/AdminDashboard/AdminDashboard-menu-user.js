import {eventSuppr} from "./AdminDashboard";
import {collapseOnWidthScreen, displayFlashMessageSuccess, displayPagination} from '../globalFunctions';

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

        menuUserSendAjaxFormUserAdd();

    });

    $('#addUserButton').click(function (e) {
        e.preventDefault();
        $.ajax('/addUserA')
            .done(function (data, textStatus, jqXDR) {
                $('#modal_edt_user').empty();
                $('#modal_edt_user').append(data);
                $('#user_add_roles').selectpicker();
                //$('#edtUserModel').modal('toggle')

                $('#edtUserModel').modal('show')
                $('#user_add_submit').click(function (e) {
                    e.preventDefault();
                    $('#edtUserModel').modal('hide')
                    menuUserSendAjaxFormUserAdd();
                })
            });
    });

    collapseOnWidthScreen('#menu-user-collapse-form', 768);

    function menuUserSendAjaxFormUserAdd(){
        let $form = $("form[name='user_add']");
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize()
        })
            .done(function (data, textStatus, jqXDR) {
                if (data[0] === true){
                    displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');
                    $('#edtUserModel').modal('hide');
                    menuUserSendAjaxFormFilter()

                }
                else{

                    $('#modal_edt_user').empty();
                    $('#modal_edt_user').append(data);
                    $('#user_add_roles').selectpicker();
                    $('#edtUserModel').modal('toggle');
                    $('#user_add_submit').click(function (e) {
                        e.preventDefault();
                        menuUserSendAjaxFormUserAdd();
                    })
                }
            });
    }

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

                $.ajax(href)
                        .done(function (data, textStatus, jqXDR) {
                            if (data[0] === false){
                                displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');
                                menuUserSendAjaxFormFilter()

                            }else {
                                $('#modal_edt_user').empty();
                                $('#modal_edt_user').append(data);
                                $('#user_add_roles').selectpicker();
                                $('#edtUserModel').modal('toggle')
                                $('#user_add_submit').click(function (e) {
                                    e.preventDefault();
                                    menuUserSendAjaxFormUserAdd();
                                })
                            }
                        });

            })
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
            '<td class="d-none d-md-table-cell" style="width:' + COL_WIDTH + '%" id="userName' + item.id + '">' + item.roles[0].libele + '</td>' +
            '<td class="d-none d-md-table-cell" style="width:' + COL_WIDTH + '%" id="userName' + item.id + '">' + item.first_name + '</td>' +
            '<td class="d-none d-md-table-cell" style="width:' + COL_WIDTH + '%" id="userName' + item.id + '">' + item.last_name + '</td>' +
            '<td class="d-none d-md-table-cell" style="width:' + COL_WIDTH + '%" ><div class="btn-group"><a href="/edtUserA/' + item.id + '" num="' + item.id + '" class="btn btn-secondary js-btn-edit-user">Modifier</a>' +
            '<a href="/resetUserA/'+ item.id +'"class="btn btn-warning js-btn-reset-user" data-toggle="modal" >Réinitialiser Mot de passe</a>' +
            '<a href="/delUserA/'+ item.id +'"class="btn btn-danger js-btn-suppr-user" data-toggle="modal" data-target="#modalValiddelete">Supprimer</a></div></td>' +
            '<td class="d-md-none">' +
            '                    <button class="btn" type="button" data-toggle="collapse" data-target="#lineTargetCollapse-'+ item.id +'" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">' +
            '                        <span class="custom_background-btn navbar-toggler-icon"></span>' +
            '                    </button>' +
            '</td>'+
            '</tr>'+
            '<tr class="d-md-none">'+
            '<td id="lineTargetCollapse-'+ item.id +'" colspan="4" class="collapse hide"><div class="btn-group"><a href="/edtUserA/' + item.id + '" num="' + item.id + '" class="btn btn-secondary js-btn-edit-user">Modifier</a>' +
            '            <a href="/resetUserA/'+ item.id +'" class="btn btn-warning js-btn-reset-user" data-toggle="modal" >Réinitialiser Mot de passe</a>' +
            '            <a href="/delUserA/'+ item.id +' " class="btn btn-danger js-btn-suppr-user" data-toggle="modal" data-target="#modalValiddelete">Supprimer</a></div></td>' +
            '</tr>'

        ).appendTo($t).hide().fadeIn(500);

    }

});



