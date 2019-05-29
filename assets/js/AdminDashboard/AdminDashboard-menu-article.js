$('#v-menu > a').each(function () {
    $(this).click(function (e) {
        e.preventDefault();

        $('#v-menu-tabContent > div').each(function () {
            $(this).fadeOut(100);
        });
        $('#'+$(this).attr('id')+'-content').delay(100).slideDown(50);

    })
});


$('#pills-home-tab').click(function (e)  {
    e.preventDefault();
    $.get('/getData?users')
        .done(function(data, textStatus, jqXDR) {

            console.log(data);
        });
});

$('#article_dashboard_filter_tags').keypress(function (e) {
    if (e.key == 'Enter'){
        let $tag = $('<a href="#" class="badge badge-warning"></a>').click(function (e) {
            e.preventDefault();
            $(this).remove();
        });
        $tag.text($(this).val()).appendTo('#tagsList');
        $(this).val('');
    }
});

let hrefArtcileToDelete = '';



$('#buttonValidDelete').click(eventSuppr);

function eventSuppr(e) {
    e.preventDefault();
    console.log("suppreion de : " + hrefArtcileToDelete);
    $.get(hrefArtcileToDelete)
        .done(function (data, textStatus, jqXDR) {
            displayListArticle(e);

        });

}


$('#validateArticleButton').click(displayListArticle);

function displayListArticle(e) {
    e.preventDefault();
    let request = "";

    if ($('#article_dashboard_filter_title').val() != "")
    {
        request += '&title='+$('#article_dashboard_filter_title').val();
    }
    if ($('#article_dashboard_filter_numCategory').val().length > 0){

        $('#article_dashboard_filter_numCategory').val().forEach(function (item, index, array) {

            request += '&category'+ (Number(index) +1) +'='+item;
        })

    }

    if ($('#article_dashboard_filter_user').val() != ""){
        request += '&author='+$('#article_dashboard_filter_user').val();
    }
    if ($('#tagsList').children().length > 0){
        $('#tagsList').children().each(function (index) {
            request += '&tag'+ (Number(index) +1) +'='+$(this).text();
        });

    }

    if ($('#article_dashboard_filter_created_before').val() != ''){
        request += '&created_before=' + $('#article_dashboard_filter_created_before').val();
    }
    if ($('#article_dashboard_filter_created_after').val() != ''){
        request += '&created_after=' + $('#article_dashboard_filter_created_after').val();
    }
    console.log(request);

    $.getJSON('/get_info?t=article'+request)
        .done(function (data, textStatus, jqXDR) {
            $('#table-body').empty();
            $.each(data, function (i, item) {

                creatTableLine(item);
            })
            $('.js-btn-suppr').each(function () {
                $(this).click(function () {
                    hrefArtcileToDelete = $(this).attr('href');
                });
            });
        })

}

function creatTableLine(item) {

    let $t = $('#table-body');
    let tagHtml = ''
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
            tagHtml += '<span class="badge badge-warning">'+ tag.tag_name +'</span>';
        });
    }

    $('<tr><th>'+ item.id +'</th>' +
        '<td>'+item.title+'</td>' +
        '<td>'+ (item.num_category != undefined ? item.num_category.libele : '')  +'</td>' +
        '<td>'+ (item.user != undefined ? item.user.username : '') +'</td>' +
        '<td>'+ createdDate +'</td>' +
        '<td>'+ editDate +'</td>' +
        '<td>'+ (item.description != undefined ? item.description : '') +'</td>'+
        '<td>'+ tagHtml +'</td>' +
        '<td><div class="btn-group"> <a href="/edit/'+ item.id +'" class="btn btn-secondary" role="link" >Editer</a><a href="/show/'+ item.id +'" class="btn btn-success">Voir</a><a href="/delete/'+ item.id +'" class="btn btn-danger js-btn-suppr" data-toggle="modal" data-target="#modalValiddelete">supprimer</a></div></td>'+
        '</tr>').appendTo($t).hide().fadeIn(500);
}
