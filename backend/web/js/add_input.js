/**
 * Скрипт работает на странице представления документа "Счет" и генерирует поле ввода цены
 */
$(".container").on("click", ".set-price", function () {
    $("div#div-set-price").remove();
    id = $(this).data("id");
    value = $(this).data("price");
    if (!value) value = 0;
    $("#d" + id).append("<div class=\'input-group\' id=\'div-set-price\'>" +
        "<input class=\'form-control\' type=\'text\' name=\'dd\' id=\'input-set-price\' value=\'" + value + "\' data-id=\'" + id + "\'/>" +
        "<span class=\'input-group-btn\'>" +
        "<button class=\'btn btn-default\' id=\'btn-set-price\' type=\'button\' data-id=\'" + id + "\'>Ok</button>" +
        "</span></div>");

    $(".grid-view").on("click", "#btn-set-price", function () {
        id = $(this).data("id");
        value = $("#input-set-price").val();
        $.post("/admin/dt-invoice-devices/set-price?id=" + id + "&price=" + value, function (data) {
            if (data == 1)
                $.pjax.reload({container: "#dt-invoices-view"});
        })
    });

    $(".grid-view").on("keyup", "#input-set-price", function (event) {
        if (event.keyCode == 13) {
            id = $(this).data("id");
            value = $(this).val();
            $.post("/admin/dt-invoice-devices/set-price?id=" + id + "&price=" + value, function (data) {
                if (data == 1)
                    $.pjax.reload({container: "#dt-invoices-view"});
            });
        }
    });

    $("#input-set-price").focus().select();

});
