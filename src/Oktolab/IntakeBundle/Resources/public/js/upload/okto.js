$(document).ready( function() {
    $('#oktolab_intake_bundle_filetype_save').click(function (e) {
        if ($('#oktolab_intake_bundle_filetype_save').data('value') != "resend") { 
            e.preventDefault();
        }
    });

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
            // add visual info for upload start
            $('#progress .progress-bar').css('min-width', "2em");
            $('#oktolab_intake_bundle_filetype_save').text($('#oktolab_intake_bundle_filetype_save').data('uploading'));
            $('#oktolab_intake_bundle_filetype_save').prop('disabled', true);
            $('#oktolab_intake_bundle_filetype_save').parent().parent().prop('disabled', true);
        });

    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css(
            'width',
            progress + '%'
        );
        $('#progress .progress-bar').text(progress + '%');

    }).on('fileuploadstop', function (e, data) {
        $('#oktolab_intake_bundle_filetype_save').parent().parent().prop('disabled', false);
        document.oktolab_intake_bundle_filetype.submit();
    });
});