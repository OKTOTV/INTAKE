$(document).ready( function() {

    var contactSortUrl = window.location + "/sort"
    var ajaxRequest = function (url, json) {
        $.ajax({
            url: url,
                 type: 'post',
                 data: JSON.stringify(json),
                 contentType: 'application/json',
                 dataType: 'json'
        });
    };


    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };

    $('.sortable').sortable({
        axis: "y",
        cursor: "move",
        handle: ".glyphicon-align-justify",
        scroll: false,
        helper: fixHelper,
        update: function( event, ui ) {
            var json = new Object();
             ui.item.parent().children().each(function (key, value) {
                 json[$(value).data('value')] = $(value).index();
             });
             ajaxRequest(contactSortUrl, json);
        }
    });
});