/**
 * Основной скрипт, который работает на каждой странице
 * сейчас обрабатывает не загруженные изображения заменяя стандартную картинку
 */
$("img").error(function () {
    $(this).attr("src", "/admin/img/noimage.jpg");
});





