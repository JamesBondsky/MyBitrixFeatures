$(function(){
    function showModal(id){
        $(document.body).addClass('is-open-modal');
        $(id).addClass('is-open');
        console.log(id);
    }
    function hideModals(){
        $(document.body).removeClass('is-open-modal');
        $('.modal').removeClass('is-open');
    }

    $(".modal-open").on('click', function(e){
        console.log(e);
        showModal('#modal1');
    });

    $(document).on('click', function(e){
        if (!(
            ($(e.target).parents('.box-modal').length)
            ||	($(e.target).hasClass('box-modal'))
            ||	($(e.target).hasClass('box-modal-button-open')))
        ) {
            hideModals();
        }
    });

});