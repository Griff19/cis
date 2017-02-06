function check_keys (e, form, mod, mm_id) {

    if (e.ctrlKey && e.keyCode == 13) {
        $.post(
            '/admin/meeting-minutes/view?id=' + mm_id + '&mod=' + mod + '&content=' + $('#'+mod+'-content').val(),
            function () {
                $.pjax.reload({container: '#'+mod+ '_idx'});
                form.reset();
            }
        );
    }
}