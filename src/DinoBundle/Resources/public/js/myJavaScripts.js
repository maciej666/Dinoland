//Moje pierwsze JavaScripty, toteż są takie a nie inne.
$(document).ready(function() {
//FUNKCJE DO ŚCIEŻKI /dino_account; widok: showDino.html.twig
//Funkcje obsługujące dzienny przydział mocy
    function addPointsHealth()
    {
        //ilość ptk. do rozdania
        var sum = document.getElementById("sum");
        //ilość ptk. pola do edycji
        var health = document.getElementById("health");
        //początkowa wartość health
        var begin = document.getElementById("begin_value_health");

        if(sum.innerHTML>0 && health.innerHTML >= begin.value){
            health.innerHTML++;
            if(health.innerHTML != begin.value){
                health.style.color = 'black';
                health.style.fontWeight = 'bold';
            }
            sum.innerHTML--;
        }
    }

    function minusPointsHealth()
    {
        var sum = document.getElementById("sum");
        var health = document.getElementById("health");
        var begin = document.getElementById("begin_value_health");

        if((sum.innerHTML==0 || sum.innerHTML==1) && health.innerHTML > begin.value ){
            health.innerHTML--;
            if(health.innerHTML != begin.value){
                health.style.color = 'black';
            }
            if(health.innerHTML == begin.value){
                health.style.color = '#777';
                health.style.fontWeight = 'normal';
            }
            sum.innerHTML++;
        }
    }

    function addPointsStrength()
    {
        var sum = document.getElementById("sum");
        var strength = document.getElementById("strength");
        var begin = document.getElementById("begin_value_strength");

        if(sum.innerHTML>0 && strength.innerHTML >= begin.value){
            strength.innerHTML++;
            if(strength.innerHTML != begin.value){
                strength.style.color = 'black';
                strength.style.fontWeight = 'bold';
            }
            sum.innerHTML--;
        }
    }

    function minusPointsStrength()
    {
        var sum = document.getElementById("sum");
        var strength = document.getElementById("strength");
        var begin = document.getElementById("begin_value_strength");

        if((sum.innerHTML==0 || sum.innerHTML==1) && strength.innerHTML > begin.value){
            strength.innerHTML--;
            if(strength.innerHTML != begin.value){
                strength.style.color = 'black';
            }
            if(strength.innerHTML == begin.value){
                strength.style.color = '#777';
                strength.style.fontWeight = 'normal';
            }
            sum.innerHTML++;
        }
    }

    function addPointsBackup()
    {
        var sum = document.getElementById("sum");
        var backup = document.getElementById("backup");
        var begin = document.getElementById("begin_value_backup");

        if(sum.innerHTML>0 && backup.innerHTML >= begin.value){
            backup.innerHTML++;
            if(backup.innerHTML != begin.value){
                backup.style.color = 'black';
                backup.style.fontWeight = 'bold';
            }
            sum.innerHTML--;
        }
    }

    function minusPointsBackup()
    {
        var sum = document.getElementById("sum");
        var backup = document.getElementById("backup");
        var begin = document.getElementById("begin_value_backup");

        if((sum.innerHTML==0 || sum.innerHTML==1) && backup.innerHTML > begin.value){
            backup.innerHTML--;
            if(backup.innerHTML != begin.value){
                backup.style.color = 'black';
            }
            if(backup.innerHTML == begin.value){
                backup.style.color = '#777';
                backup.style.fontWeight = 'normal';
            }
            sum.innerHTML++;
        }
    }

    function addPointsSpeed()
    {
        var sum = document.getElementById("sum");
        var speed = document.getElementById("speed");
        var begin = document.getElementById("begin_value_speed");

        if(sum.innerHTML>0 && speed.innerHTML >= begin.value){
            speed.innerHTML++;
            if(speed.innerHTML != begin.value){
                speed.style.color = 'black';
                speed.style.fontWeight = 'bold';
            }
            sum.innerHTML--;
        }
    }

    function minusPointsSpeed()
    {
        var sum = document.getElementById("sum");
        var speed = document.getElementById("speed");
        var begin = document.getElementById("begin_value_speed");

        if((sum.innerHTML==0 || sum.innerHTML==1) && speed.innerHTML > begin.value){
            speed.innerHTML--;
            if(speed.innerHTML != begin.value){
                speed.style.color = 'black';
            }
            if(speed.innerHTML == begin.value){
                speed.style.color = '#777';
                speed.style.fontWeight = 'normal';
            }
            sum.innerHTML++;
        }
    }

    //Timer ściągnięty z neta, modyfikowany przeze mnie
    function startTimer(duration, display)
    {
        var timer = duration, hours, minutes, seconds;
        setInterval(function () {

            hours = parseInt(timer / 3600, 10);
            minutes = parseInt((timer / 60)%60, 10);
            seconds = parseInt(timer % 60, 10);

            hours = hours < 10 ? "0" + hours : hours;
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.text("Kolejny przydział mocy za " + hours + ":" + minutes + ":" + seconds );

            if (--timer < 0) {
                timer = duration;
            }
            if (seconds == 0 && minutes==0){
                return null
            }
        }, 1000);
    }

    //Cztery ponizsze funkcję zwiększają odpowiednio ilość surowców po stronie klienta aby nie obciążać serwera.
    //Stan surowców do bazy danych zapisywany jest przy odswieżaniu i po zakupie udoskonaleń.
    //Zmienna timero uwzględnia zmiane przydzielania kolejnych ptk. w zależności od posiadanego lvl'u, na podstawie pola access z informacją o poziomie wykupionego schronu.
    //Aktualny poziom surowców jest też sprawdzany w kontrolerze
    var timero_wood = 300000; // 5min
    var timero_stone = 600000; // 10min
    var timero_bone = 900000; // 15min
    var timero_flint = 1200000; // 20min

    var access = document.getElementById("access").value ;

    if(access == '1' ){
        var timero_wood = 285000;
    }else if(access == '2'){
        var timero_wood = 285000;
        var timero_stone = 480000;
        var timero_bone = 980000;
    }else if(access == '3'){
        var timero_wood = 242250;
        var timero_stone = 480000;
        var timero_bone = 980000;
    }else if(access == '4'){
        var timero_wood = 242250;
        var timero_stone = 432000;
        var timero_bone = 882000;
    }else if(access == '5'){
        var timero_wood = 242250;
        var timero_stone = 432000;
        var timero_bone = 882000;
        var timero_flint = 1116000;
    }else if(access == '6'){
        var timero_wood = 218025;
        var timero_stone = 388800;
        var timero_bone = 793800;
        var timero_flint = 1116000;
    }else if(access == '7'){
        var timero_wood = 130815;
        var timero_stone = 388800;
        var timero_bone = 793800;
        var timero_flint = 1116000;
    }else if(access == '8'){
        var timero_wood = 104652;
        var timero_stone = 311040;
        var timero_bone = 635040;
        var timero_flint = 892800;
    }

    setInterval(function () {
        var drewno = $("#drewno").text();
        var sum = +drewno + +1 ;
        $("#drewno").text(sum);
    },timero_wood);

    setInterval(function () {
        var kamien = $("#kamien").text();
        var sum = +kamien + +1 ;
        $("#kamien").text(sum);
    },timero_stone);

    setInterval(function () {
        var kosc = $("#kosc").text();
        var sum = +kosc + +1 ;
        $("#kosc").text(sum);
    },timero_bone);

    setInterval(function () {
        var krzemien = $("#krzemien").text();
        var sum = +krzemien + +1 ;
        $("#krzemien").text(sum);
    },timero_flint);

    //Zapisywanie parametrów dina do bazy danych
    function aButtonPressed()
    {
        var sum = document.getElementById("sum");
        var url = $('#dino_button').data('url');

        if(sum.innerHTML == 0){
            $.post(url,
                {health: document.getElementById("health").innerHTML,
                    speed: document.getElementById("speed").innerHTML,
                    backup: document.getElementById("backup").innerHTML,
                    strength: document.getElementById("strength").innerHTML
                },
                function(response){
                    if(response.code == 100 && response.success){
                        document.getElementById("odp").innerHTML = 'Udoskonalono';
                        document.getElementById("dino_button").style.display = "none";
                        document.getElementById("info").style.display = "block";

                        var display = $('#ile');
                        //timer odliczający 1 dzień do następnego rozdania ptk. mocy
                        startTimer(86400, display);

                        //co 15 minut wysyła zapytanie czy można wyświetlić przycisk do edycji parametrów
                        setInterval(checkTime, 900000);

                        document.getElementById("sum").innerHTML = '0';
                        document.getElementById("dino_button").style.display = "none";
                        document.getElementById("backup").style.color = '#777';
                        document.getElementById("health").style.color = '#777';
                        document.getElementById("speed").style.color = '#777';
                        document.getElementById("strength").style.color = '#777';
                        document.getElementById("backup").style.fontWeight = 'normal';
                        document.getElementById("health").style.fontWeight = 'normal';
                        document.getElementById("speed").style.fontWeight = 'normal';
                        document.getElementById("strength").style.fontWeight = 'normal';
                    }else{
                        document.getElementById("odp").innerHTML = 'Coś poszło nie tak, odśwież stronę:)';
                        document.getElementById("dino_button").style.display = "none";
                    }
                }, "json");
        }else{
            document.getElementById("odp").innerHTML = 'Rozdaj wszystkie punkty';
        }
    }

    //Sprawdza czy minał czas po którym parametry(siła ,zdrowie...) mogą być zmienione,
    //jeżeli tak to pokazuje przycisk.
    //Funkcja uruchamiana przy ładowaniu strony
    function checkTime()
    {
        var url = $('#odp').data('url');

        $.post(url,
            function (response) {
                if (response.code == 100 && response.success) {
                    document.getElementById("odp").innerHTML = '';
                    if(document.getElementById("info").style.display == "block"){
                        document.getElementById("sum").innerHTML = '2';
                    }
                    document.getElementById("dino_button").style.display = "block";
                    document.getElementById("info").style.display = "none";
                } else {
                    //jeżeli odpowiednio dużo czasu nie upłyneło ustawiany jest znowu timer odliczający pozostały czas
                    var time_to_update = $('#time_to_update').data('time');
                    var display = $('#ile');
                    startTimer(time_to_update, display);
                    document.getElementById("info").style.display = "block";
                    document.getElementById("sum").innerHTML = '0';
                    document.getElementById("dino_button").style.display = "none";
                }
            }, "json");
    }

    //Funkcja obsługuje wykup schronu
    function addHome()
    {
        document.getElementById("btnHome").className = "btn btn-primary disabled";
        var access = document.getElementById("home_btn").innerHTML;
        var url = 'http://www.dino.dev/app_dev.php/dino_account/ajax_add_home';

        $.post(url,
            {
                access: access
            },
            function (response) {
                if (response.code == 100 && response.success) {

                    //odświeża ilość surowca
                    refreshMaterials();
                    // odświeża diva z dino-schronami
                    $('.whole').load(" .whole");
                } else {
                }
            }, "json");
    }


    //Odświeża ilość surowców po wykupieniu schronu
    function refreshMaterials()
    {
        var url = 'http://www.dino.dev/app_dev.php/dino_account/ajax_materials';

        $.post(url,
            function (response) {
                if (response.code == 100 && response.success) {
                    document.getElementById("drewno").innerHTML = response.wood;
                    document.getElementById("kamien").innerHTML = response.stone;
                    document.getElementById("kosc").innerHTML = response.bone;
                    document.getElementById("krzemien").innerHTML = response.flint;
                    document.getElementById("access").value = response.access;
                } else {
                    document.getElementById("qwe").innerHTML = 'Odśwież stronę!!!';
                }
            }, "json");
    }



});

