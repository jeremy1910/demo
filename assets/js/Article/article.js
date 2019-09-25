
$('#create_edt_article').click(function (e) {
    $("form[name='form_article']").submit();
});

CKEDITOR.on('instanceReady', function() {
    $('#animation_loading_editor').hide();
    $('#article_editor').fadeIn(1000);
});

/*****************************************
 Gestion de la liste déroulante des catégorie
 ******************************************/

$('#form_article_num_category').selectpicker();

/*
$('#form_article_num_category').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
    if ($( "#form_article_num_category option:selected" ).val() === 'add'){
        let selectHiden = document.getElementById('selectHiden')
        selectHiden.style.display = 'inline-block'
        let submitHiden = document.getElementById('submitHiden')
        submitHiden.style.display = 'inline-block'

    }
});


$('#submitHiden').click(function (e) {
    let val = $('#selectHiden').val();
    $("#form_article_num_category").append('<option value="' + val + '">' + val + '</option>').selectpicker('refresh');
    $('#form_article_num_category').selectpicker('val', val);
    $('#form_article_num_category').selectpicker('refresh');
    let selectHiden = document.getElementById('selectHiden');
    selectHiden.style.display = 'none';
    let submitHiden = document.getElementById('submitHiden');
    submitHiden.style.display = 'none';
});
*/

$('.js-btn-suppr').each(function () {
    $(this).click(function () {
        $('#buttonValidDelete').attr('href', $(this).attr('href'));
        $('#buttonValidDelete').click(function (e) {
            e.preventDefault();
            document.location.href= $('#buttonValidDelete').attr('href');

        });
    });
});

/*****************************************
Gestion des Tags
******************************************/


$("<div class=\"form-group\"><input id='input_enter_new_tag' class=\"form-control invisible_input\" placeholder=\"Ajoutez des tags\"></div>").insertBefore('#tag-container');
$('#input_enter_new_tag').keypress(enterTag);
let index = 0;
let teamplate = $('#form_article_tags').attr('data-prototype');
let inputTeamplate = $(teamplate).find('input').css('display', 'none').prop('outerHTML');




function enterTag(e) {
    if (e.code === 'Enter'){
        e.preventDefault();
        let newTag = $(this).val().toUpperCase();

        if (newTag !== '' && find_duplicate_tag(newTag) === false){
            $('<span class="badge badge-success-custom scale-in-center p-2 m-1">'+ newTag +'</span>').appendTo('#tag-container');

            let inputTagHiden = inputTeamplate.replace(/__name__/g, index);

            inputTagHiden = $(inputTagHiden);
            inputTagHiden.val(newTag);
            $(inputTagHiden).insertAfter('#tag-container');
            this.value = '';
            index++;

        }
    }
}

function find_duplicate_tag(tag){
    let find = false;
    $('#tag-container').children().each(function (i, item) {


        if (item.innerText === tag){

            find = true;
        }
    });
    return find === true;
}

if (document.getElementById('form_article_tags').children)
{


    while(document.getElementById('form_article_tags_'+index+'_tagName') != null)
    {
        let tag = document.createElement('span');
        tag.classList.add('badge');
        tag.classList.add('scale-in-center');
        tag.classList.add('badge-success-custom');
        tag.classList.add('p-2');
        tag.classList.add('m-1');

        tag.innerText = document.getElementById('form_article_tags_'+index+'_tagName').value;


        document.getElementById('tag-container').appendChild(tag);

        let inputTagHiden = createElementFromHTML(inputTeamplate.replace(/__name__/g, index));
        inputTagHiden.value = document.getElementById('form_article_tags_'+index+'_tagName').value;
        document.getElementById('form_article_tags').parentElement.appendChild(inputTagHiden);



        index++;
    }

    let form_article_tags = document.getElementById('form_article_tags');

    while (form_article_tags.firstChild) {
        form_article_tags.removeChild(form_article_tags.firstChild);
    }



}

function createElementFromHTML(htmlString) {
    var div = document.createElement('div');
    div.innerHTML = htmlString.trim();

    return div.firstChild;
}

/**************
 * Mise en form du selecteur d'image
 *
 * ***************/

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#blah').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#form_article_image_imageFile").change(function() {
    readURL(this);
});



