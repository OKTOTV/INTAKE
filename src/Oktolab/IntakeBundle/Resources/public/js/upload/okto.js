$(document).ready( function() {
    $('#oktolab_intake_bundle_filetype_save').click(function (e) {
        if ($('#oktolab_intake_bundle_filetype_save').data('value') != "resend") { 
            e.preventDefault();
        }
    });

    console.log($('#fileupload').data('junksize'));

    $('#fileupload').fileupload({
        maxChunkSize: $('#fileupload').data('junksize'),
        autoUpload:false

    }).on('fileuploadadd', function (e, data) {
        data.context = $('<div/>').appendTo('#div-fileupload');
        $.each(data.files, function (index, file) {
            var node = $('<p/>').append($('<span/>').text(file.name));
            node.appendTo(data.context);
        });
        $('#oktolab_intake_bundle_filetype_save').prop('disabled', false);
        $('#oktolab_intake_bundle_filetype_save').data('value', '');
        $('#oktolab_intake_bundle_filetype_save').click(function (e) {
            data.submit();
        });

    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css(
            'width',
            progress + '%'
        );

    }).on('fileuploadstop', function (e, data) {
        document.oktolab_intake_bundle_filetype.submit();
    });
});