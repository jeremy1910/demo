require('../css/index.css')

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

$('#toto').click(function (e) {
    $('#col-4').removeClass('collapsed');
    $('#col-1').addClass('collapsed');

});

$('.js-index-card').hover(function (e) {

    console.log();

    $(this).prev().children().first().removeClass('offset-img');
    $(this).prev().children().first().addClass('offset-img-none');
}, function (e) {
    $(this).prev().children().first().removeClass('offset-img-none');
    $(this).prev().children().first().addClass('offset-img');

});



