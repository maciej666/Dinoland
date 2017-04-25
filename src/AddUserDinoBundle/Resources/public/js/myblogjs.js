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
                    $(parsedData).hide().prependTo("#comments-block").fadeIn(4000);
                    last_comment = $('.comments-date:first').data('last');
                } else {
                    console.log('204: Nie ma nowych komentarzy');
                }
            }, "json")
    }, 5000);

});