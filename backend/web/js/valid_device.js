$("form").on("ajaxComplete", function (e) {
    //alert(e);
    var query = window.location.search;
    query = query.substr(1);

    var notunique = false;

    //получаем значения всех возможных ошибок уникальности
    var mesSn = $(".field-devices-sn>.help-block").html();
    var mesImei1 = $(".field-devices-imei1>.help-block").html();
    var mesImei2 = $(".field-devices-imei2>.help-block").html();
    var mesMac = $(".field-devices-device_mac>.help-block").html();

    if (mesSn !== '') {
        var value = $("#devices-sn").val();
        var label = "sn";
        notunique = true;
    }
    if (mesImei1 !== '') {
        var value = $("#devices-imei1").val();
        var label = "imei1";
        notunique = true;
    }
    if (mesImei2 !== '') {
        var value = $("#devices-imei2").val();
        var label = "imei2";
        notunique = true;
    }
    // У предыдущих параметров идет проверка только на уникальность
    // а вот мак-адрес может быть и неправильно введн, поэтому
    // выбираем только ошибку иникальности.
    if (mesMac == 'Устройство с таким mac-адресом уже существует') {
        var value = $("#devices-device_mac").val();
        var label = "device_mac";
        notunique = true;
    }

    //далее либо выводим кнопку с приглашением выбрать имеющееся утройство
    //либо удаляем все подобные кнопки
    if (notunique && value !== '') {

        $("#change-by-attr").remove();
        $(".field-devices-" + label + ">.help-block").after(
            '<a id="change-by-attr" class="btn btn-success" href="change-by-attr?label=' + label + '&value=' + value + '&' + query + '" > Выбрать это устройство </a>'
        );
    } else {
        $("#change-by-attr").remove();
    }
});

