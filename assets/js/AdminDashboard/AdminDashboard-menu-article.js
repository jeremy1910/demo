import { eventSuppr } from './AdminDashboard.js';
import {collapseOnWidthScreen, displayPagination} from '../globalFunctions';

$(document).ready(function () {

    let menuArticleTagPrototype = $('#article_filter_tags').attr('data-prototype');
    menuArticleTagPrototype = $(menuArticleTagPrototype).find('input').prop('outerHTML');

    let menuArticleInputToAddTag = $('<div class="form-group"><label for="genericTagInput">Tags</label><input type="text" id="genericTagInput" placeholder="Ajouter un tag à rechercher" class="form-control"></div><div id="inputsTagHiden"></div>')
    $('#article_filter_tags').parent().replaceWith(menuArticleInputToAddTag);

    $('#selecter-article').selectpicker().change(function () {
        $('#article_filter_nbResult').val('');

        $('#article_filter_nbResult').val($("#selecter-article option:selected").text());
        $('#article_filter_pageSelected').val(1);

        menuArticleSendAjaxFormFilter();
    });

    $('#genericTagInput').keypress(function (e) {
        if (e.key === 'Enter') {
            menuArticleCreateTagHTML($(this).val());
            $(this).val('');

        }
    });
    $("form[name='article_filter']").submit(function (e) {
        e.preventDefault();
        menuArticleSendAjaxFormFilter();

    });

    collapseOnWidthScreen('#menu-article-collapse-form', 768);

    function menuArticleCreateTagHTML(value) {
        let tagIndex = $('#inputsTagHiden').children().length;
        $(menuArticleTagPrototype.replace(/__name__/g, tagIndex)).val(value).hide().appendTo('#inputsTagHiden');
        let $tag = $('<a href="'+ tagIndex +'" class="badge badge-warning"></a>').click(function (e) {
            e.preventDefault();
            $(this).remove();
            $("[id=article_filter_tags_"+ $(this).attr('href') +"]").remove();
        });
        $tag.text(value).appendTo('#tagsList');
    }

    function menuArticleSendAjaxFormFilter(){
        let $form = $("form[name='article_filter']");
        let tableBodyCategoryHeight = $('#table-admindashBoardArticle-body').css('height') === "0px" ? 150 : $('#table-admindashBoardArticle-body').css('height');
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize(),
            beforeSend: function () {
                $('#table-admindashBoardArticle-body').fadeOut(400, function () {
                    $('#spinnerLoadingGeneralSearch-adminDashboardArticle').addClass('d-block');
                    $('#spinnerLoadingGeneralSearch-adminDashboardArticle .loading-medium').css('height', tableBodyCategoryHeight)
                    $('#spinnerLoadingGeneralSearch-adminDashboardArticle').show();
                });
            }
        })
            .done(function (data) {
                $('#spinnerLoadingGeneralSearch-adminDashboardArticle').removeClass('d-block');
                $('#spinnerLoadingGeneralSearch-adminDashboardArticle').hide();
                $('#table-admindashBoardArticle-body').show()
                menuArticleDisplayResult(data)
            });
    }

    function menuArticleDisplayResult(data) {
        let pageActive = $('#article_filter_pageSelected').val();

        let result = JSON.parse(data);

        $('#table-admindashBoardArticle-body').empty();
        $.each(result.result, function (i, item) {
            menuArticleCreateTableLine(item);

        });
        $('.js-btn-suppr').each(function () {
            $(this).click(function () {
                $('#buttonValidDelete').attr('href', $(this).attr('href'));
                $('#buttonValidDelete').click(function (e) {
                    e.preventDefault();
                    eventSuppr('article');

                    menuArticleSendAjaxFormFilter();

                });

            });
        });
        $('.js-search-by-badge').each(function () {
            $(this).click(function () {
                $('#inputsTagHiden').children().remove();
                $('#tagsList').children().remove();
                menuArticleCreateTagHTML($(this).text());
                menuArticleSendAjaxFormFilter();


            })
        });
        $('.js-menuArticle-badge-user').each(function () {
            $(this).click(function () {
                $('#article_filter_user').val($(this).text());
                menuArticleSendAjaxFormFilter();
            })
        });

        $('.js-menuArticle-badge-category').each(function () {
            $(this).click(function (e) {
                e.preventDefault();
                $('#article_filter_numCategory').selectpicker('val', $(this).attr('href'));
                menuArticleSendAjaxFormFilter();
            })
        });
        displayPagination(result.nbPage, pageActive, function (e) {
            e.preventDefault();
            $('#article_filter_pageSelected').val($(this).children().text());
            menuArticleSendAjaxFormFilter();
        }, function (e) {
            e.preventDefault();
            $('#article_filter_pageSelected').val(Number(pageActive)-1);
            menuArticleSendAjaxFormFilter();
        }, function (e) {
            e.preventDefault();
            $('#article_filter_pageSelected').val(Number(pageActive)+1);
            menuArticleSendAjaxFormFilter();
        }, 'article',$('#article_filter_pageSelected'))
    }



    function menuArticleCreateTableLine(item) {
        let $t = $('#table-admindashBoardArticle-body');
        let tagHtml = '';
        let createdDate = new Date(item.created_at);
        createdDate = createdDate.getDate() + '/' + createdDate.getMonth() + '/' + createdDate.getFullYear();

        let editDate = '';
        if (item.last_edit !== undefined)
        {
            editDate = new Date(item.last_edit);
            editDate = editDate.getDate() + '/' + editDate.getMonth() + '/' + editDate.getFullYear();
        }

        if (item.tags !== undefined){
            item.tags.forEach(function (tag, index, array) {
                tagHtml += '<span class="badge badge-warning js-search-by-badge">'+ tag.tag_name +'</span>';
            });
        }

        $('<tr><th>'+ item.id +'</th>' +
            '<td>'+item.title+'</td>' +
            '<td class="d-none d-md-table-cell">'+ (item.num_category != undefined ? '<a class="badge badge-info js-menuArticle-badge-category" style="width: 100%;" href="'+ item.num_category.id +'">' + item.num_category.libele +'</a>': '')  +'</td>' +
            '<td class="d-none d-md-table-cell">'+ (item.user != undefined ? '<span class="badge badge-secondary js-menuArticle-badge-user" style="width: 100%;">' +item.user.username +'</span>': '') +'</td>' +
            '<td class="d-none d-md-table-cell">'+ createdDate +'</td>' +
            '<td class="d-none d-md-table-cell">'+ editDate +'</td>' +
            '<td class="d-none d-md-table-cell">'+ (item.description != undefined ? item.description : '') +'</td>'+
            '<td class="d-none d-md-table-cell">'+ tagHtml +'</td>' +
            '<td class="d-none d-md-table-cell"><div class="btn-group"> <a href="/edit/'+ item.id +'" class="btn btn-secondary" role="link" >Editer</a><a href="/show/'+ item.id +'" class="btn btn-success">Voir</a><a href="/rmArticleA?id='+ item.id +'" class="btn btn-danger js-btn-suppr" data-toggle="modal" data-target="#modalValiddelete">supprimer</a></div></td>'+
            '<td class="d-md-none">' +
            '                    <button class="btn" type="button" data-toggle="collapse" data-target="#lineTargetCollapse-'+ item.id +'" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">' +
            '                        <span class="custom_background-btn navbar-toggler-icon"></span>' +
            '                    </button>' +
            '</td>'+
            '</tr>'+
            '<tr class="d-md-none">'+
            '<td id="lineTargetCollapse-'+ item.id +'" colspan="4" class="collapse hide"><div class="btn-group"> <a href="/edit/'+ item.id +'" class="btn btn-secondary" role="link" >Editer</a><a href="/show/'+ item.id +'" class="btn btn-success">Voir</a><a href="/rmArticleA?id='+ item.id +'" class="btn btn-danger js-btn-suppr" data-toggle="modal" data-target="#modalValiddelete">supprimer</a></div></td>' +
            '</tr>'


        ).appendTo($t).hide().fadeIn(500);
    }

});

