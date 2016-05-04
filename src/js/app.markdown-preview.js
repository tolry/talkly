$(document).ready(function() {
    $('textarea')
        .each(function() {
            var $textarea = $(this);
            var $previewDiv = $('<div />')
                .addClass('panel')
                .html('preview loading')
            ;
            var $panel = $('<div />')
                .append('<label>markdown preview</label>')
                .append($previewDiv)
            ;

            $textarea
                .after($panel)
                .data('previewDiv', $previewDiv)
            ;
        })
        .on('change', function() {
            var $textarea   = $(this);
            var $previewDiv = $(this).data('previewDiv');

            $previewDiv.html('loading preview');

            $.ajax(app.baseUrl() + "ajax/markdown/", {
                data: {
                    text: $textarea.val()
                },
                success: function(data) {
                    $previewDiv.html(data.html);
                },
                type: 'POST',
                format: 'json'
            });
        })
        .on('keyup', function() {
            var $textarea = $(this);

            delay(function() { $textarea.trigger('change'); }, 1000);
        })
        .trigger('change')
    ;
});

// http://stackoverflow.com/questions/1909441/jquery-keyup-delay
var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();

