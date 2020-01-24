$(function () {
    $.datepicker.setDefaults(
        $.extend(
            {'dateFormat':'yy-mm-dd'},
            $.datepicker.regional["en-AU"]
        )
    );
})