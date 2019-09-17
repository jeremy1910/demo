require('../css/index.css')


let listCategory = $('#category-container').data('category-list').split(',');

let actualCategory = 2;

let chargedCategory = [0, 1, 2];
let posOffset = 0;



$('#arrow-next').click(function (e) {

    if (listCategory[actualCategory+1] !== undefined && chargedCategory[actualCategory+1] === undefined) {
        $.ajax({
            url: '/index/getCategoryCards/' + listCategory[actualCategory+1],
            method: 'POST',
            data: '',
            beforeSend: function () {

                $('#arrow-next').hide();
                $('#animation_loading_column').show()
            }
        }).done(function (data) {
            chargedCategory.push(actualCategory);
            posOffset += 33.33;
            $('#category-container-card').append(data);
            $('.js-index-card').animate({
                right: posOffset+'%'
            }, 300);

            actualCategory++;
            $('#animation_loading_column').hide();
            $('#arrow-next').show();
        })

    }else if (listCategory[actualCategory+1] !== undefined && chargedCategory[actualCategory+1] !== undefined){
        posOffset += 33.33;
        $('.js-index-card').animate({
            right: posOffset+'%'
        }, 300);
        actualCategory++;
    }
});

$('#arrow-previous').click(function (e) {
    if (listCategory[actualCategory-3] !== undefined) {
        posOffset -= 33.33;
        $('.js-index-card').animate({
            right: posOffset+'%'
        }, 300);
        actualCategory--;
    }
});

$('.js-index-card').hover(function (e) {
    $(this).prev().children().first().removeClass('offset-img');
    $(this).prev().children().first().addClass('offset-img-none');
}, function (e) {
    $(this).prev().children().first().removeClass('offset-img-none');
    $(this).prev().children().first().addClass('offset-img');
});






const ratio = .2;
const options = {
    root: null,
    rootMargin: '0px',
    threshold: ratio,
};

const handleIntersect = function (entries, observer){
    entries.forEach(function (entry) {
        if (entry.intersectionRatio > ratio){
            entry.target.classList.add('revealX-visible');
            observer.unobserve(entry.target)
        }
    });
};

let observer = new IntersectionObserver(handleIntersect, options);

document.querySelectorAll('.revealX').forEach(function (r) {
    observer.observe(r);
});

