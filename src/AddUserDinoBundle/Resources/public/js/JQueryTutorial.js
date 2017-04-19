//ŹRÓDŁO: https://www.youtube.com/watch?v=062SOUOJVds&list=PLr6-GrHUlVf_RNxQQkQnEwUiHELmB0fW1&index=22
//Gdy zmiana styli nie działa w chrome można pokopiować style z jquery ui, umieścić w nowym pliku i dołączyć za wszystkimi stylami
$(document).ready(function(){

    $("button").click(function() {

        //-------------SELEKTORY-------------------
        //$('div:even').hide(); //parzyste
        //$("p:contains('center')").hide(); //chowa element mający tekst center
        //$("#left p:first").hide(); // chowa pierwszego <p> z diva o id=left
        //$("div:has(b)").hide(); //każdy div który ma <b>
        //$("ul li:nth-child(2)").hide(); //chowa drugiego <li> z <ul>
        //$("body div:nth-child(4)").hide();
        //$("li:empty").hide(); //chowa pustego li

        //-------------METODY CSS-------------
        //$("#left").css('color', 'blue');
        //$('#left').css('border', 'solid 2px red');
        //$('#left').css({'border' : 'solid 2px red', 'color' : 'blue'});

        //--------------METODY HTML I TEXT-------------------
        //$("p").html('<h3>wstawia html<h3>');
        //$("p").text('To jest normalny tekst');
        //$("h2:first").text('Wrzuca tekt do pustego elementu');

        //---------------CHAINING METHODS--------------
        //$('#left').css('color', 'blue').css('border', 'solid 4px blue').fadeOut(4000);

        //---------------ADD AND REMOVE CLASS----------------
        //$("div:first p:last").addClass('danger');
        //$("div:first p:first").removeClass('bob');

        //----------------FADE IN FADE OUT------------------------
        //$("#secret").fadeIn(2500);
        //$("#left").fadeOut(2500);
        //$("#center").fadeOut(2500).fadeIn(2500);
        //$("#center").fadeTo(2500, 0.5); //drugi argument wskazuje na przezroczystosc
        //$("#center").fadeToggle(2500);

        //-----------------APPEND PREPEND----------------------
        //$("#center").append("<h3>HO HO</h3>"); //wstawia na końcu wybranego elementu
        //$("#right").prepend("<h3>Ha Ha</h3>"); //wstawia na początku
        //$("#center").after("<h3>ZA DIV</h3>"); //wstawia przed wybranym elementem
        //$("#center").before("<h3>PRZED DIV</h3>").css('border', 'solid 4px orange'); //wstawia za

        //-----------------REPLACE REMOVE--------------------------------------
        //$("div:first p:first").replaceWith("<p>nowy paragraf</p>"); //zastępuje stary nowym

        //-----------------ATTRIBUTE METHOD-----------------------------------
        //$("img").attr("src", "/bundles/framework/images/logo_symfony.png"); //wstawia w miejsce src ścieżke
        //$("img").removeAttr("src"); //usuwa src, foto znika

        //-----------------EACH METHOD AND THIS KEY (LOOP)------------------------------
        //$("li").each(function() {
        //    $(this).html("<li>tekst z pętli</li>");
        //});


    });

        //-------------------EVENTS-------------------------------------
        //$("button").mouseover(function() {
        //    $("button").css('color', 'red');
        //});

        //$("img").mouseover(function() {
        //    $("#img-box").append('<p>Chwała Symfony</p>');
        //});

        //hover jako argumenty przyjmuje dwie funkcje
        //$("img").hover(function() {
        //    $("div").css('background-color', 'orange').css('color', 'red'); },
        //    //gdy odjedziemy myszką
        //    function() {
        //        $("div").css('background-color', 'grey').css('color', 'blue');
        //});

        //$("#img-box").hover(
        //    function() {
        //    $("#img-box img").show();
        //    },
        //    function() {
        //        $("#img-box img").hide();
        //    }
        //);

        //---------------------SLIDE EFFECTS--------------------------
        //$("button").click(function() {
        //    $("#img-box").slideUp();
        //});

        //$("#test_extra").click(function() {
        //    $("#img-box").slideDown();
        //});

        //$("#test_extra").click(function() {
        //    $("#img-box").slideToggle();
        //});

        //--------------------JQUERY UI http://jqueryui.com/------------------------------
        //Spolszczenie
        //( function( factory ) {
        //    if ( typeof define === "function" && define.amd ) {
        //
        //        // AMD. Register as an anonymous module.
        //        define( [ "../widgets/datepicker" ], factory );
        //    } else {
        //
        //        // Browser globals
        //        factory( jQuery.datepicker );
        //    }
        //}( function( datepicker ) {
        //
        //    datepicker.regional.pl = {
        //        closeText: "Zamknij",
        //        prevText: "&#x3C;Poprzedni",
        //        nextText: "Następny&#x3E;",
        //        currentText: "Dziś",
        //        monthNames: [ "Styczeń","Luty","Marzec","Kwiecień","Maj","Czerwiec",
        //            "Lipiec","Sierpień","Wrzesień","Październik","Listopad","Grudzień" ],
        //        monthNamesShort: [ "Sty","Lu","Mar","Kw","Maj","Cze",
        //            "Lip","Sie","Wrz","Pa","Lis","Gru" ],
        //        dayNames: [ "Niedziela","Poniedziałek","Wtorek","Środa","Czwartek","Piątek","Sobota" ],
        //        dayNamesShort: [ "Nie","Pn","Wt","Śr","Czw","Pt","So" ],
        //        dayNamesMin: [ "N","Pn","Wt","Śr","Cz","Pt","So" ],
        //        weekHeader: "Tydz",
        //        dateFormat: "yy-mm-dd",
        //        firstDay: 1,
        //        isRTL: false,
        //        showMonthAfterYear: false,
        //        yearSuffix: "" };
        //    datepicker.setDefaults( datepicker.regional.pl );
        //
        //    return datepicker.regional.pl;
        //
        //} ) );

        $("#pickdate").datepicker();
        //$("#pickdate").datepicker({numberOfMonths: 3}); // ile miesiecy ma pokazac
        //$("#pickdate").datepicker({changeMonth: true, showWeek: true }); //droplist dla miesięcy...
        //$("#pickdate").datepicker({showWeek: true, weekHeader: "Week" });
        //$("#pickdate").datepicker({showOtherMonths: true });
        //$("#pickdate").datepicker({minDate: new Date(2016, 11, 25) }); // 11 to grudzień
        //$("#pickdate").datepicker({showButtonPanel: true, closeText: "Zamknij", currentText: "Dzisiaj" }); //droplist dla miesięcy...
        //$("#pickdate").datepicker({yearSuffix: " BC" }); //droplist dla miesięcy...

        //----------------------------THEMEROLL http://jqueryui.com/themeroller/--------------------------------------
        //Zmiana styli, patrz na stronie.

        //-----------------------------TOOLTIP - działa z jquery 2.* - patrz też: http://www.ryadel.com/en/using-jquery-ui-bootstrap-togheter-web-page/--------------------------------------------
        //$("#img-box img").uitooltip();
        $("img").uitooltip({content: "Dino alive!"});
        $("img").uitooltip({
            track: true
        });

        //$("img").uitooltip( { show: {
        //    effect: 'pulsate',
        //    duration: 800
        //}});

        //$("img").uitooltip({show: {effect: "fadeIn", duration:1200}},
        //    {hide: {effect: "fadeOut", duration:100}}
        //
        //);

        //Zmiana styli tooltipa w jquery-ui-structure.css
        //Patrz też https://jqueryui.com/tooltip/#custom-style style trzeba umieścić po scriptcie

        //$( document ).uitooltip({
        //    position: {
        //        my: "center bottom-20",
        //        at: "center top",
        //        using: function( position, feedback ) {
        //            $( this ).css( position );
        //            $( "<div>" )
        //                .addClass( "arrow" )
        //                .addClass( feedback.vertical )
        //                .addClass( feedback.horizontal )
        //                .appendTo( this );
        //        }
        //    }
        //});

        //--------------------------ACCORDION WIDGET----------------------------------
        $("#accordion_panels").accordion({
            collapsible: true, //żeby móc zamknąć wszystkie panele
            event: 'mouseover', //otiwera po najechaniu myszką
            animate: 900, //czas trwania animacji
            active: 1, //który panel ma być otwarty, tu drugi/ Rozmiar defaultowego diva będzie rozmiarem każdego diva
            heightStyle: "content" //wysokość panelu zależy od ilości tekstu
        });
        //zmiana ikon patrz: https://api.jqueryui.com/theming/icons/
        $("#accordion_panels").accordion({icons: {
            header: "ui-icon-folder-collapsed",
            activeHeader: "ui-icon-folder-open"}});

        //--------------------------VERTICAL MENU----------------------------------------
        $('#verticalMenu').menu({
            icons: {
                submenu: 'ui-icon-circle-triangle-e'
            }
        });
        //---------------------------MESSAGE BOX----------------------------------------
    $('#boxxx').dialog({
        title: 'Dinoland',
        width: 400,
        modal: true
    }).height("auto");

});

//JQuery ready executes when HTML-Document is loaded and DOM is ready (earliest possible)
//Load executes when complete page is fully loaded, including all frames, objects and images
//$(window).on("load",function()
//{
//
//    alert("on loaded iframe");
//
//});
//nie działa z konkretnymi selektorami !!??

