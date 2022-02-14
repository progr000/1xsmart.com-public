/**
 *
 */
function modal_closed_view_my_video_modal()
{
    $('#user-local-video-profile')[0].pause();
}

//http://ssbeefeater.github.io/#ssi-uploader/documentation
$(document).ready(function() {

    /* картинки */
    $('#ssi-upload').ssi_uploader({
        url: '/user/upload-profile-photo',
        //dropZone: false,
        multiple: false,
        locale: 'ru',
        maxFileSize: 3,
        allowed: ['jpeg', 'jpg', 'png'],
        //onUpload:function() {
        //    console.log('The upload process finished.');
        //
        //},
        //responseValidation:{
        //    validationKey: 'type',
        //    resultKey: 'msg',
        //    success: 'success',
        //    error: 'error'
        //}
        responseValidationFunction: function(data) {
            console.log(data);
            if ("imgSrc" in data && data.type == 'success') {
                var $img = $('#user-profile-photo-container');
                $img[0].src = data.imgSrc;
                $('.photo-disabled').hide();
                $('.photo-enabled').show();
                //$('#ssi-clearBtn').trigger('click');
                $('#upload-profile-photo-modal').find('.js-close-modal').first().trigger('click');
            } else {

            }
        }
        //errorHandler: {
        //    method: function (msg, type) {
        //        console.log(msg);
        //    }
        //}

    });

    /**/
    $(document).on('click', '.profile-photo__add-btn', function(){
        $('#ssi-clearBtn').trigger('click');
    });

    /**/
    $(document).on('click', '.profile-photo__remove-btn', function(){
        $.ajax({
            type: 'get',
            url: '/user/delete-profile-photo',
            dataType: 'json'
        }).done(function (response) {
            if ("imgSrc" in response) {

                var $img = $('#user-profile-photo-container');
                $img[0].src = response.imgSrc;
                $('.photo-disabled').show();
                $('.photo-enabled').hide();

            } else {
                console.log(response);
                prettyAlert('An internal server error occurred.');
            }

        });

    });




    /* видео */
    $('#ssi-upload-video').ssi_uploader({
        url: '/user/upload-profile-video',
        //dropZone: false,
        multiple: false,
        locale: 'ru',
        maxFileSize: 1000,
        allowed: ['mov', 'mp4', 'mpeg', 'mpg'],
        responseValidationFunction: function(data) {
            console.log(data);
            if ("imgSrc" in data && data.type == 'success') {
                //var $img = $('#user-profile-photo-container');
                //$img[0].src = data.imgSrc;
                $('.video-disabled').hide();
                $('.video-enabled').show();
                $('#user-local-video-profile')[0].src = data.imgSrc;
                //$('#ssi-clearBtn').trigger('click');
                $('#upload-profile-video-modal').find('.js-close-modal').first().trigger('click');
            } else {
                $('#user-local-video-profile')[0].src = '';
            }
        }
    });

    /**/
    $(document).on('click', '.profile-video__add-btn', function(){
        $('#ssi-clearBtn').trigger('click');
    });

    /**/
    $(document).on('click', '.profile-video__remove-btn', function(){
        $.ajax({
            type: 'get',
            url: '/user/delete-profile-video',
            dataType: 'json'
        }).done(function (response) {
            if ("imgSrc" in response) {

                //var $img = $('#user-profile-photo-container');
                //$img[0].src = response.imgSrc;
                $('.video-disabled').show();
                $('.video-enabled').hide();

            } else {
                console.log(response);
                prettyAlert('An internal server error occurred.');
            }

        });

    });
});



