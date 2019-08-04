



var selectCategory =  document.getElementById('form_article_num_category');



var addCategory_option = document.createElement("option")
addCategory_option.text = " Ajouter une category...";
addCategory_option.id = "option_ajouter";
selectCategory.add(addCategory_option,  null);

$('#form_article_num_category').selectpicker();


$('#form_article_num_category').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
    console.log(isSelected);
});



var optionAjouter = document.getElementById('option_ajouter');
optionAjouter.addEventListener('click', function(){

        let selectHiden = document.getElementById('selectHiden')
        selectHiden.style.display = 'inline-block'
        let submitHiden = document.getElementById('submitHiden')
        submitHiden.style.display = 'inline-block'



})


var submitHidenLisenner = document.getElementById('submitHiden');


submitHidenLisenner.addEventListener('click', function(){
    addCategory_option = document.createElement("option")
    addCategory_option.text = document.getElementById('selectHiden').value

    selectCategory.insertBefore(addCategory_option, optionAjouter);
    addCategory_option.value = document.getElementById('selectHiden').value + '|' + (Number(addCategory_option.previousElementSibling != null ? addCategory_option.previousElementSibling.value : 0) + 1)
    addCategory_option.selected = 'selected'

    let selectHiden = document.getElementById('selectHiden')
    selectHiden.style.display = 'none'
    let submitHiden = document.getElementById('submitHiden')
    submitHiden.style.display = 'none'
})




/*****************************************
Gestion des Tags
******************************************/

let div = document.createElement('div');
div.classList.add('form-group');

let input = document.createElement('input');
input.classList.add('form-control');
input.placeholder = 'Ajoutez des tags';

let index = 0;

div.appendChild(input);

document.getElementById('form_article_tags').parentElement.insertBefore(div, document.getElementById('tag-container'));

input.addEventListener('keydown', enterTag);

function enterTag(e) {

    if (e.code == 'Enter'){
        e.preventDefault();
        if (this.value != ''){
            let tag = document.createElement('span');
            tag.classList.add('badge');
            tag.classList.add('badge-pill');
            tag.classList.add('badge-primary');
            tag.innerText = this.value;
            document.getElementById('tag-container').appendChild(tag);

            let inputTagHiden = createElementFromHTML(inputTeamplate.replace(/__name__/g, index));
            inputTagHiden.value = this.value;
            document.getElementById('form_article_tags').parentElement.appendChild(inputTagHiden);
            this.value = '';
            index++;

        }
    }
}

let inputTeamplate = '<input type="text" style="display: none" id="form_article_tags___name___tagName" name="form_article[tags][__name__][tagName]" required="required" maxlength="255" class="form-control" />';



function createElementFromHTML(htmlString) {
    var div = document.createElement('div');
    div.innerHTML = htmlString.trim();

    return div.firstChild;
}

/*  */


if (document.getElementById('form_article_tags').children)
{


    while(document.getElementById('form_article_tags_'+index+'_tagName') != null)
    {
        let tag = document.createElement('span');
        tag.classList.add('badge');
        tag.classList.add('badge-pill');
        tag.classList.add('badge-primary');
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