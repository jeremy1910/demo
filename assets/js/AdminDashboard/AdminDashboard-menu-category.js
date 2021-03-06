import {displayFlashMessageSuccess, displayPagination, collapseOnWidthScreen} from '../globalFunctions';
import {eventSuppr} from "./AdminDashboard";


const NB_COL = 3;
const COL_WIDTH = 100/NB_COL;

$(document).ready(function () {
    $('#divAddNewCategory').hide();

    $('#addCategoryButton').click(function (e) {
        e.preventDefault();
        $('#divAddNewCategory').slideToggle();
    });

    $('#category_filter_search').click(function (e) {
        e.preventDefault();

        $('#category_filter_clickedButton').val($(this).attr('name'));
        menuCategorySendAjaxFormFilter();

    });

    $('#category_filter_createCategory_submit').click(function (e) {
        e.preventDefault();
        $('#category_filter_clickedButton').val($(this).attr('name'));
        menuCategorySendAjaxFormFilter();

    });

    $('#selecter-category').selectpicker().change(function () {
        $('#category_filter_nbResult').val('');

        $('#category_filter_nbResult').val($("#selecter-category option:selected").text());
        $('#category_filter_pageSelected').val(1);
        $('#category_filter_search').click();
    });

    $('#categoryChangeImageSelecter').change(function (e) {

        let id = $('#categoryChangeImageID').val();

        let $form = $("form[name='categoryChangeImageForm']");
        console.log($form);

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: new FormData($form[0]),
            cache: false,
            contentType: false,
            processData: false,

            beforeSend: function () {

            }
        })
            .done(function (data, textStatus, jqXDR) {

                displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');
                $('#category_filter_search').click();


        })

    });

    collapseOnWidthScreen('#menu-category-collapse-form', 768);

    function menuCategorySendAjaxFormFilter() {
        let $form = $("form[name='category_filter']");
        let tableBodyCategoryHeight = $('#table-body-category').css('height') === "0px" ? 150 : $('#table-body-category').css('height');

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: new FormData($form[0]),
            cache: false,
            contentType: false,
            processData: false,

            beforeSend: function () {
                $('#table-body-category').fadeOut(400, function () {
                    $('#spinnerLoadingGeneralSearch-adminDashboardCategory').addClass('d-block');
                    $('#spinnerLoadingGeneralSearch-adminDashboardCategory .loading-medium').css('height', tableBodyCategoryHeight)
                    $('#spinnerLoadingGeneralSearch-adminDashboardCategory').show();
                });

            }        }).done(function (data, textStatus, jqXDR) {

            if ($('#category_filter_clickedButton').val() == 'category_filter[createCategory][submit]'){
                displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');
                $('#category_filter_search').click();
            }
            else {
                setTimeout(function () {
                    $('#spinnerLoadingGeneralSearch-adminDashboardCategory').removeClass('d-block');
                    $('#spinnerLoadingGeneralSearch-adminDashboardCategory').hide();
                    $('#table-body-category').show()
                    menuCategoryDisplayResult(data);
                },420);

            }
            $('#category_filter_clickedButton').val('');
            $('#divAddNewCategory').hide();
        })
    }


    function menuCategoryDisplayResult(data) {
        let pageActive = $('#category_filter_pageSelected').val();
        let result = JSON.parse(data);

        $('#table-body-category').empty();

        $.each(result.result, function (i, item) {
            menuCategoryCreateTableLine(item);

        });
        $('.js-btn-suppr-category').each(function () {
            $(this).click(function () {
                $('#buttonValidDelete').attr('href', $(this).attr('href'));
                $('#buttonValidDelete').click(function (e) {
                    e.preventDefault();
                    eventSuppr('category');
                    $('#category_filter_search').click();
                });

            });
        });
        $('.js-btn-edit').each(function () {
            $(this).click(function (e) {
                e.preventDefault();
                let href = $(this).attr('href');
                let num = $(this).attr('num');
                let text = $('#categoryLibele'+num).text();


                $('#categoryLibele'+num).text('');
                let $btn = $('<a href="'+ href +'" class="btn btn-primary js-btn-valide-edit">Modifier</a>');
                $btn.click(function (e) {
                    e.preventDefault();
                    let newLibele = $('#inputNewCategory'+ num).val();
                    $.getJSON(href+'&libele='+newLibele)
                        .done(function (data, textStatus, jqXDR) {
                            if (data[0] === true){
                                displayFlashMessageSuccess(Object.keys(data[1])[0], Object.values(data[1])[0][0], 'flash-message');
                                $('#category_filter_search').click();
                            }
                        });
                });
                $btn.appendTo($('<div class="col-2"></div></div>').appendTo($('<div class="row"><div class="col-10"><input id="inputNewCategory'+ num +'" type="text" class="form-control" placeholder="'+ text +'"></div>').appendTo('#categoryLibele'+num)));
                //$('<a href="'+ href +'" class="btn btn-primary js-btn-valide-edit">Modifier</a>').appendTo('#categoryLibele'+num);
            })
        });

        displayPagination(result.nbPage, pageActive, function (e) {
            e.preventDefault();
            $('#category_filter_pageSelected').val($(this).children().text());
            $('#category_filter_search').click();
        }, function (e) {
            e.preventDefault();
            $('#category_filter_pageSelected').val(Number(pageActive)-1);
            $('#category_filter_search').click();
        }, function (e) {
            e.preventDefault();
            $('#category_filter_pageSelected').val(Number(pageActive)+1);
            $('#category_filter_search').click();
        }, 'category',$('#category_filter_pageSelected'))


    }

    function menuCategoryCreateTableLine(item) {

        let $t = $('#table-body-category');



        $('<tr>' +
            '<th style="width:' + COL_WIDTH + '%" scope="row">' + item.id + '</th>' +
            '<td style="width:' + COL_WIDTH + '%" id="categoryLibele' + item.id + '">' + item.libele + '</td>' +
            '<td style="width:' + COL_WIDTH + '%" id="categoryImage' + item.id + '"><img class="rounded-circle" style="width: 50px; height: 50px;" src="/images/category_img/'+ item.image_path +'"></td>' +
            '<td  class="d-none d-md-table-cell" style="width:' + COL_WIDTH + '%" ><div class="btn-group"><a href="/edtCategoryA?id=' + item.id + '" num="' + item.id + '" class="btn btn-secondary js-btn-edit">Modifier le libélé</a>' +
            '<a href="#" onclick="$(\'#categoryChangeImageID\').val('+item.id+');document.getElementById(\'categoryChangeImageSelecter\').click();" class="btn btn-secondary js-btn-edit">Modifier l\'image</a>'+
            '<a href="/rmCategoryA?id=' + item.id + '"class="btn btn-danger js-btn-suppr-category" data-toggle="modal" data-target="#modalValiddelete">supprimer</a></div></td>' +
            '<td class="d-md-none">' +
            '                    <button class="btn" type="button" data-toggle="collapse" data-target="#lineTargetCollapse-'+ item.id +'" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">' +
            '                        <span class="custom_background-btn navbar-toggler-icon"></span>' +
            '                    </button>' +
            '</td>'+
            '</tr>'+
            '<tr class="d-md-none">'+
            ' <td id="lineTargetCollapse-'+ item.id +'" colspan="4" class="collapse hide"><div class="btn-group"><a href="/edtCategoryA?id=' + item.id + '" num="' + item.id + '" class="btn btn-secondary js-btn-edit">Modifier le libélé</a>' +
            '<a href="/rmCategoryA?id=' + item.id + '"class="btn btn-danger js-btn-suppr-category" data-toggle="modal" data-target="#modalValiddelete">supprimer</a></div></td>' +
            '</tr>'

        ).appendTo($t).hide().fadeIn(500);

    }

});
