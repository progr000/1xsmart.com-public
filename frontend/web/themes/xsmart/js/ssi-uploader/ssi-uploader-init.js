const $download_video_link = $('#download-video-link');

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
        locale: _LANGUAGE,
        maxFileSize: 3,
        allowed: ['jpeg', 'jpg', 'png'],
        data: { any_data_field_here: 'value' },
        beforeUpload: function () {
            let $user_id_field = $('#upload-img-for-user-id');
            if ($user_id_field.length) {
                this.data.user_id = parseInt($user_id_field.val());
                //alert(this.data.user_id);
            }
        },
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
            //console.log(data);
            let search_user_id = parseInt(this.data.user_id);
            if ("imgSrc" in data && data.type == 'success') {
                if (search_user_id == 0) {
                    $(document).find('.any-place-user-ava').each(function() {
                        let $img_ava = $(this);
                        $img_ava[0].src = data.imgSrc;
                        $('#form-profile-teacher .required-photo').removeClass('has-error');
                        if ($('#form-profile-teacher').find('.has-error').length == 0) {
                            $('#form-profile-teacher .required-notif').removeClass('form-has-error');
                        } else {
                            $('#form-profile-teacher .required-notif').addClass('form-has-error');
                        }
                    });
                } else {
                    $(document).find('.managed-ava-user_photo').each(function() {
                        if ($(this).attr('data-user-id') == search_user_id) {
                            $(this)[0].src = data.imgSrc;
                        }
                    });
                    try { $.pjax.reload({container: "#students-list-content", async: false}); }
                    catch (e) { console.log('info:: Skipped. Not found pjax container #students-list-content for reload.'); }
                }

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
    $(document).on('click', '.profile-photo__remove-btn', function() {
        let data = {};
        let search_user_id = 0;
        let $user_id_field = $('#upload-img-for-user-id');
        if ($user_id_field.length) {
            data.user_id = parseInt($user_id_field.val());
            search_user_id = data.user_id;
            //alert(this.data.user_id);
        }
        $.ajax({
            type: 'get',
            url: '/user/delete-profile-photo',
            data: data,
            dataType: 'json'
        }).done(function (response) {
            if ("imgSrc" in response) {

                if (search_user_id == 0) {
                    $(document).find('.any-place-user-ava').each(function() {
                        let $img_ava = $(this);
                        $img_ava[0].src = response.imgSrc;
                        $('#form-profile-teacher .required-photo').addClass('has-error');
                        $('#form-profile-teacher .required-notif').addClass('form-has-error');
                    });
                } else {
                    $(document).find('.managed-ava-user_photo').each(function() {
                        if ($(this).attr('data-user-id') == search_user_id) {
                            $(this)[0].src = response.imgSrc;
                        }
                    });
                }

                $('.photo-disabled').show();
                $('.photo-enabled').hide();

            } else {
                console.log(response);
                //prettyAlert($translate_text_messages.attr('data-msg-15'));
            }

        });

    });




    /* видео */
    $('#ssi-upload-video').ssi_uploader({
        url: '/user/upload-profile-video',
        //dropZone: false,
        multiple: false,
        locale: _LANGUAGE,
        maxFileSize: 1000,
        allowed: ['mp4', 'mpeg', 'mpg', 'webm', 'mov'],
        responseValidationFunction: function(data) {
            console.log(data);
            if ("imgSrc" in data && data.type == 'success') {
                //var $img = $('#user-profile-photo-container');
                //$img[0].src = data.imgSrc;
                $('.video-disabled').hide();
                $('.video-enabled').show();
                $('#user-local-video-profile')[0].src = data.imgSrc;
                $download_video_link.attr('href', data.imgSrc);
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
                $download_video_link.attr('href', response.imgSrc);
                $('.video-disabled').show();
                $('.video-enabled').hide();

            } else {
                console.log(response);
                //prettyAlert($translate_text_messages.attr('data-msg-15'));
            }

        });

    });
});



