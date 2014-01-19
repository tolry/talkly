// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();

$(document).ready(function() {
    $('[data-toggle]').click(function(event) {

        var id = $(this).data('toggle-target');
        var $toggleTarget = $("#" + id);

        if ($toggleTarget.is(':visible')) {
            $toggleTarget.hide('slide');
            $(this).removeClass('toggle-open');
        } else {
            $toggleTarget.show('slide');
            $(this).addClass('toggle-open');
            $toggleTarget.find('input,textarea').first().focus();
        }

        return false;
    });

    $('a.ajax-action').click(function(event) {

        event.stopPropagation();

        var url = $(this).attr('href');
        $.ajax(url, {
            dataType: 'json',
            method:   'post',
            complete: function(jqXHR) {
                if (jqXHR.responseJSON == undefined) {
                    console.log(jqXHR.responseText);
                    alert('error');

                    return false;
                }

                var json = jqXHR.responseJSON;

                console.log(json);

                // todo - currently not used
            }
        });

        return false;
    });
});

var app = {
    addFlash: function (level, message) {
        var $message = $("<div data-alert />");
        var $flash   = $("#flash");

        $message
            .addClass('alert-box')
            .addClass(level)
            .html(message)
            .append('<a href="#" class="close">&times;</a>')
            .appendTo($flash)
        ;

        // todo - events are not applied

    }
}

