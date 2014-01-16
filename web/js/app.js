// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();

$(document).ready(function() {
    $('.toggle').click(function() {
        var id = $(this).data('toggle-target');
        $("#" + id).toggle('slide');
    });
});
