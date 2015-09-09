jQuery(document).ready(function($){


    var custom_uploader;


    $('.upload_image_button').live('click', function(e) {
        el = $(this);
        e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: true
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            el.prev().val(attachment.url);
        });

        //Open the uploader dialog
        custom_uploader.open();
    });
});