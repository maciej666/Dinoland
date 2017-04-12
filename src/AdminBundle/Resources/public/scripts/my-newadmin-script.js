$(document).ready(function() {
//-----------------------------FUNKCJE DO PLIKU index.html.twig------------------------------------

    //Po kliknięciu w opcje z sidebar-menu zmienia sie zawartość div'a o id="page-wrapper"
    $('.admin-menu').on('click', function (e) {
        e.preventDefault();
        var clickedMenu = $(this).data('menu');

        //zmienia klase select kliknietej opcji menu
        $('.selected').removeClass('selected');
        $(this).addClass('selected');

        $.post(clickedMenu,
            function (response) {
                if (response.code == 100 && response.success) {

                    var parsedData = JSON.parse(response.content);
                    var wrapper = $("#page-wrapper");
                    wrapper.empty();
                    wrapper.html(parsedData);

                } else {
                    alert('Odśwież stronę');
                }
            }, "json");
    });

});