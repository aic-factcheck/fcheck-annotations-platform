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
            $('#evidence tr:not([data-show]) td:last-child,#evidence tr:not([data-show])  th:last-child').each(function () {
                var clone = $(this).clone().html('');
                if (clone.is('th')) {
                    if ($(this).parent().hasClass("table-primary")) {
                        clone.text('#' + (parseInt($(this).text().split("#")[1]) + 1));
                    } else {
                        clone.text('');
                    }
                } else {
                    var $checkbox = $(this).find('input[type=checkbox]').clone().prop('checked', false);
                    var name = $checkbox.attr('name').split('[');
                    $checkbox.attr('name', name[0] + '[' + (parseInt(name[1]) + 1) + '][]');
                    clone.append($checkbox);
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

$(".autoflag").click(function (e) {
    $("#flag").get(0).prop('checked', true);
});
bindAll();
$(document).on("keypress", '#label-form', function (e) {
    var code = e.keyCode || e.which;
    if (code == 13) {
        e.preventDefault();
        return false;
    }
});
$("[data-show]").each(function (){
    $($(this).data("show")).css("display","none");
});
$("[data-show]").click(function (e){
    $($(this).data("show")).slideToggle();
    if($(this).data("alt") !== undefined && $(this).data("alt") !== null){
        var tmp = $(this).data("alt");
        $(this).data("alt",$(this).html());
        $(this).html(tmp);
    }
    e.preventDefault();
});