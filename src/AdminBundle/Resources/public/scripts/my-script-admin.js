$(document).ready(function() {
//-----------------------------FUNKCJE DO PLIKU index.html.twig------------------------------------

    //Po kliknięciu w opcje z sidebar-menu zmienia sie zawartość div'a o id="page-wrapper"
    $('.admin-menu').on('click', function(e)
    {
        e.preventDefault();
        var clickedMenu = $(this).data('menu');

        //zmienia klase select kliknietej opcji menu
        $('.selected').removeClass('selected');
        $(this).addClass('selected');

        $.post(clickedMenu,

            function (response) {
                if (response.code == 100 && response.success) {

                    var parsedData =JSON.parse(response.content);
                    var wrapper = $("#page-wrapper");
                    wrapper.empty();
                    wrapper.html(parsedData);

                } else {
                    alert('Odśwież stronę');
                }
            }, "json");
    });

//--------------------------FUNKCJE DO PLIKU tabello.html.twig---------------------------------------------------

    // Funkcja umożliwia edycje klikniętego usera
    $('.users-in-table').on('click', function(e)
    {
        e.preventDefault();
        var id = $(this).data('edituser');

        $.post('http://www.dino.dev/app_dev.php/admin/ajax_edit_users',
        {
            id: id
        },
            function (response) {
                if (response.code == 200 && response.success) {

                    var parsedData =JSON.parse(response.content);
                    var wrapper = $("#edit-dino-form");
                    wrapper.empty();
                    wrapper.html(parsedData);
                    window.scrollTo(0,document.body.scrollHeight);
                    saveChange(id);
                } else {
                }
            }, "json");
    });

    //Funkcja zapisuje dane wprowadzone w formularzu do edycji usera, jego parametrów i ilości surowców
    function saveChange(id)
    {
        $('.save_user_ajax').on('submit', function(e)
        {
            e.preventDefault();
            console.log();
            $.post('/app_dev.php/admin/ajax_save_user_change/'+id, $(this).serialize(),
                function (response) {
                    if (response.code == 200 && response.success) {

                        var parsedData =JSON.parse(response.content);
                        var wrapper = $("#tabello");
                        var editDinoForm = $("#edit-dino-form");
                        wrapper.empty();
                        editDinoForm.empty();
                        wrapper.html(parsedData);
                        window.scrollTo(0,0);

                    } else if(response.code == 400 && !response.success) {
                        var parsedData =JSON.parse(response.content);
                        var wrapper = $("#edit-dino-form");
                        wrapper.empty();
                        wrapper.html(parsedData);
                        saveChange(id);
                    }
                }, "json");
        });
    }

    //Funkcja sortuje tabele z userami.
    $(".user-sort").click(function()
    {
        //jeżli przed sortowaniem user wyszukiwał coś to pobieram treść tego wyszukiwania
        //aby sortować tylko wyszukiwane pozycje
        var searchVal = $("input[type=text][name=show-result]").val();
        var sort = $(this).data('sort');

        $.post('http://www.dino.dev/app_dev.php/admin/ajax_sort_users',
    {
        searchVal: searchVal,
        sort: sort
    },
        function (response) {
            if (response.code == 200 && response.success)
            {
                var parsedData =JSON.parse(response.content);
                var wrapper = $("#tabello");
                wrapper.empty();
                wrapper.html(parsedData);
            } else {
            }
        }, "json");
    });

//--------------------------FUNKCJE DO PLIKU users.html.twig---------------------------------------------------

    //Funkcja wyszukuje tekstu w tabeli users.
    $("input[type=text][name=show-result]").keyup(function(){

        //wartość wpisanego tekstu w polu szukaj
        var search = $(this).val();
        //sprawdza które filtry są zaznaczone
        var email =  $('#email').is(':checked');
        var name =  $('#name').is(':checked');
        var age =  $('#age').is(':checked');
        var species =  $('#species').is(':checked');

        $.post('http://www.dino.dev/app_dev.php/admin/ajax_show_search ',
        {
            search: search,
            email: email ? 1 : 0, //tak dziwacznie gdyż miałem problem z przekazaniem wartości bollowskiej za pomocą XMLHttpRequest
            name: name ? 1 : 0,
            age: age ? 1 : 0,
            species: species ? 1 : 0
        },
            function (response) {
                if (response.code == 200 && response.success) {

                    var parsedData =JSON.parse(response.content);
                    var wrapper = $("#tabello");
                    wrapper.empty();
                    wrapper.html(parsedData);
                } else {
                }
            }, "json");
    });





});