/**
 * Разворачиваем и сворачиваем строку Таблицы устройств,
 * отображая и скрывая комплектующие
 */
function CollapseTable(){
    $('[id ^= row]').click(function() {
        if ($("tr").is("#newtr")) {
            if ($(this).next("tr").is("#newtr")) {
                $("#newtr").remove();
            } else {
                $(this).after($("#newtr"));
                $("#newtd").text("");
                $("#newtd").load($(this).attr('data-target'));
            }
        } else {
            $(this).after('<tr id ="newtr"><td id ="newtd" colspan="12"></td></tr>');
            $("#newtd").load($(this).attr('data-target'));
        }
    });
}


