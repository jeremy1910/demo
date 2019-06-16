import { eventSuppr } from './AdminDashboard.js';
import { displayFlashMessageSuccess } from '../globalFunctions';
import { displayPagination } from '../globalFunctions';

$(document).ready(function () {

    let menuArticleTagPrototype = $('#article_filter_tags').attr('data-prototype');
    menuArticleTagPrototype = $(menuArticleTagPrototype).find('input').prop('outerHTML');

    let menuArticleInputToAddTag = $('<div class="form-group"><label for="genericTagInput">Tags</label><input type="text" id="genericTagInput" placeholder="Ajouter un tag à rechercher" class="form-control"></div><div id="inputsTagHiden"></div>')
    $('#article_filter_tags').parent().replaceWith(menuArticleInputToAddTag);

    $('#selecter').selectpicker().change(function () {
        console.log($("select option:selected").text());
        $('#article_filter_nbResult').val($("select option:selected").text());
        menuArticleSendAjaxFormFilter();
    });

    $('#genericTagInput').keypress(function (e) {
        if (e.key == 'Enter') {
            menuArticleCreateTagHTML($(this).val());
            $(this).val('');

        }
    });
    $("form[name='article_filter']").submit(function (e) {
        e.preventDefault();
        menuArticleSendAjaxFormFilter();

    });


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

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: $form.serialize()})
            .done(function (data, textStatus, jqXDR) {
                menuArticleDisplayResult(data)
            });
    }

    function menuArticleDisplayResult(data) {
        let pageActive = $('#article_filter_pageSelected').val();

        let result = JSON.parse(data);

        $('#table-body').empty();
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
        }, $('#article_filter_pageSelected'))
    }



    function menuArticleCreateTableLine(item) {
        let $t = $('#table-body');
        let tagHtml = '';
        let createdDate = new Date(item.created_at);
        createdDate = createdDate.getDate() + '/' + createdDate.getMonth() + '/' + createdDate.getFullYear();

        let editDate = '';
        if (item.last_edit != undefined)
        {
            editDate = new Date(item.last_edit);
            editDate = editDate.getDate() + '/' + editDate.getMonth() + '/' + editDate.getFullYear();
        }

        if (item.tags != undefined){
            item.tags.forEach(function (tag, index, array) {
                tagHtml += '<span class="badge badge-warning js-search-by-badge">'+ tag.tag_name +'</span>';
            });
        }

        $('<tr><th>'+ item.id +'</th>' +
            '<td>'+item.title+'</td>' +
            '<td>'+ (item.num_category != undefined ? '<a class="badge badge-info js-menuArticle-badge-category" style="width: 100%;" href="'+ item.num_category.id +'">' + item.num_category.libele +'</a>': '')  +'</td>' +
            '<td>'+ (item.user != undefined ? '<span class="badge badge-secondary js-menuArticle-badge-user" style="width: 100%;">' +item.user.username +'</span>': '') +'</td>' +
            '<td>'+ createdDate +'</td>' +
            '<td>'+ editDate +'</td>' +
            '<td>'+ (item.description != undefined ? item.description : '') +'</td>'+
            '<td>'+ tagHtml +'</td>' +
            '<td><div class="btn-group"> <a href="/edit/'+ item.id +'" class="btn btn-secondary" role="link" >Editer</a><a href="/show/'+ item.id +'" class="btn btn-success">Voir</a><a href="/rmArticleA?id='+ item.id +'" class="btn btn-danger js-btn-suppr" data-toggle="modal" data-target="#modalValiddelete">supprimer</a></div></td>'+
            '</tr>').appendTo($t).hide().fadeIn(500);
    }

});

