$(document).ready(function() {
    $('#profile_picture_file').change(function() {
        $('#profile_picture_form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: './?_task=settings&_action=plugin.profile_picture_upload',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert('Profile picture uploaded successfully!');
                },
                error: function() {
                    alert('Failed to upload profile picture.');
                }
            });
        });
    });
});
