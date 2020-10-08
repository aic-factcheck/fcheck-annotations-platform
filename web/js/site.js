function bindAll() {
    $(".checkcell,.dictionary-item").unbind('click');
    $(".evidence").unbind('change');

    $(".checkcell").click(function (e) {
        var chk = $(this).find("input:checkbox").get(0);
        if (e.target !== chk) {
            chk.click();
        }
    });

    $(".evidence").change(function () {
        $(".btn-success,.btn-danger").removeAttr('disabled');
        if ($(this).parent().is(":last-child")) {
            $('#evidence tr td:last-child,#evidence tr th:last-child').each(function () {
                var clone = $(this).clone().html('&nbsp;');
                if (clone.is('th')) {
                    if ($(this).parent().hasClass("table-primary")) {
                        clone.text('#' + (parseInt($(this).text().split("#")[1]) + 1));
                    } else {
                        clone.text('');
                    }
                } else {
                    clone.html('<input type="checkbox" class="evidence">')
                }
                $(this).parent().append(clone);
            });
            bindAll();
        }
    });

    $(".dictionary-item").click(function () {
        $(this).find(".fa-caret-down,.fa-caret-up").toggleClass("d-none");
        var $next = $(this).next('tr');
        while (!$next.hasClass('dictionary-item')) {
            $next.toggleClass('d-none');
            $next = $next.next('tr');
        }
    });
}

bindAll();
