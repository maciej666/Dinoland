$(document).ready(function() {

    //chowa info o dodaniu komentarza
    $('.alert').fadeOut(4000);

    //wysyła zapytanie o nowe komentarze
    var last_comment = $('.comments-date:first').data('last');
    var post_id = $('#post-id').data('postid');
    setInterval(function() {
        $.post('http://www.dino.dev/app_dev.php/blog/load_fresh_comments',
            {
                last_comment: last_comment,
                post_id: post_id
            },
            function (response) {
                if (response.code == 200 && response.success) {
                    var parsedData =JSON.parse(response.content);
                    $(parsedData).hide().prependTo(".main-menu").fadeIn(4000);
                    last_comment = $('.comments-date:first').data('last');
                } else {
                    console.log('204: Nie ma nowych komentarzy');
                }
            }, "json")
    }, 5000);

    //boczny panel menu
    $('.col-sm-3:first').hide();
    $('.col-sm-9:first').before('<div id="show-panel" class="col-sm-1 fadeout"></div>');

    $('#show-panel').click(function() {
        $('.col-sm-3:first').show(1000);
        $('.col-sm-3:first').css('cursor', 'w-resize');
        $(this).hide(1000);
    });

    $('.col-sm-3:first').click(function() {
        $('#show-panel').show(1000);
        $(this).hide(1000);
    });


    //show form
    var form = $('#comment-form');

    $('.show-commment-form').click(function() {
        $(this).closest("div").append(form);
        $('#comment-form').css('display', 'block');
    });


    //show comments
    $('.show-commment-response').click(function() {
        var ul = $(this).parent().parent().find('ul');
        console.log(ul);
        ul.toggle();
    });


    //hide comment form
    $('.cover-comment-form').click(function() {
        $('#comment-form').hide();
    });


    var offset = $('#border-div').offset();
    //change style for comment block if has parent
    $('#child div').css({'border-width': '3px 0 0 0', 'border-style': 'outset', 'padding-top': '20px'});


    //save comment form
    $('.save_comment_form').on('submit', function(e)
    {
        e.preventDefault();
        var postSlug = $('#post-slug').data('postslug');
        var parentId = $(this).closest('li').find('.comments-id').data('id');
        console.log(parentId);
        //return;
        $.post('/app_dev.php/blog/'+postSlug+'/comment/create/'+parentId, $(this).serialize(),
            function (response) {
                if (response.code == 200 && response.success) {
                    console.log('ok');

                } else if(response.code == 400 && !response.success) {
                    console.log('bad');
                }
            }, "json");
    });

    //send pdf on email
    $('#send-pdf').on('click', function(e)
    {
        var email = $('#email').val();
        window.scrollTo(0, 0);
        $('.alert-info').show().fadeOut(7000);
        e.preventDefault();
        $.post('/app_dev.php/blog/send_pdf_email',
            {
            email: email
            },
            function (response) {
                if (response.code == 200 && response.success) {
                    console.log('wysłano');
                }
            }, "json");
    });


    //download pdf
    $("#download-pdf").on('click', function() {
        location.href = "/app_dev.php/blog/download/pdf";
            $('.alert-success').show().fadeOut(5000);
    });

});