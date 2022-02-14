let $required_photo = $('.required-photo');
let $checkboxes_required = $('.checkboxes-required');
let $location_required = $('.location-required');
let $required_notif = $('.required-notif');
let $form_profile_teacher = $('#form-profile-teacher');
let $form_profile_student = $('#form-profile-student');

/**
 * @returns {boolean}
 */
function checkTeacherForm()
{
    $required_photo.removeClass('has-error');
    $checkboxes_required.removeClass('has-error');
    $location_required.removeClass('has-error');
    $required_notif.removeClass('form-has-error');

    //let $self = $(this);

    /**/
    let photo_uploaded = true;
    $form_profile_teacher.find('.photo-disabled').each(function() {
        if ($(this).is(':visible')) {
            photo_uploaded = false;
        }
    });
    if (!photo_uploaded) {
        $required_photo.addClass('has-error');
    }

    /**/
    let native_checked = false;
    $form_profile_teacher.find('.user-are-native').each(function() {
        let $ch = $(this);
        //console.log($ch[0].checked);
        if ($ch[0].checked) {
            native_checked = true;
        }
    });
    if (!native_checked) {
        $checkboxes_required.addClass('has-error');
    }

    /**/
    let geo_ok = true;
    let geo_country = $('#geo-country-field').val();
    if (geo_country <= 0) {
        $location_required.addClass('has-error');
        geo_ok = false;
    }

    /**/
    if (!(native_checked && photo_uploaded && geo_ok)) {
        $required_notif.addClass('form-has-error');
    }

    /**/
    return (native_checked && photo_uploaded && geo_ok);
}

/**
 *
 */
$(document).ready(function() {

    /**/
    $(document).on('change', '.lng-level-select', function() {
        $('#' + $(this).data('for')).val($(this).val());
    });

    /**/
    $(document).on('change', '.user-are-native', function () {
        let $self = $(this);
        let lng = $self.data('lng');
        let $also = $(`#radio-user-speak-also-${lng}`);
        let $div_sel_also = $(`#div-select-user-speak-also-${lng}`);
        if ($self.is(":checked")) {
            $also.val('NATIVE')
                .prop('checked', true)
                .prop('disabled', true)
                .attr('checked', 'checked')
                .attr('disabled', 'disabled');
            $div_sel_also.removeClass('_visible');
        } else {
            $also.val('off')
                .prop('checked', false)
                .prop('disabled', false)
                .removeAttr('checked')
                .removeAttr('disabled');
            $div_sel_also.removeClass('_visible');
        }
    });

    /**/
    $(document).on('change', '.user-speak-also', function () {
        let $self = $(this);
        let lng = $self.data('lng');
        if ($self.is(":checked")) {
            $self.val($(`#select-user-speak-also-${lng}`).val());
        } else {
            $self.val('off')
        }
        //alert($(this).val());
    });

    /**/
    $(document).on('change', '#id1-user_youtube_video', function() {
        $('#id2-user_youtube_video').val($(this).val());
    });
    $(document).on('change', '#id2-user_youtube_video', function() {
        $('#id1-user_youtube_video').val($(this).val());
    });

    /**/
    $(document).on('change', '#form-profile-teacher .user-are-native', function() {
        if ($(this)[0].checked) {
            $checkboxes_required.removeClass('has-error');
            //console.log($form_profile_teacher.find('.has-error').length);
            if ($form_profile_teacher.find('.has-error').length == 0) {
                $required_notif.removeClass('form-has-error');
            }
        }
    });

    /**/
    $(document).on('change', '.check-this-field', function () {
        //console.log($form_profile_teacher.find('.has-error').length);
        setTimeout(function() {
            if ($form_profile_teacher.length) {
                if ($form_profile_teacher.find('.has-error').length == 0) {
                    $required_notif.removeClass('form-has-error');
                } else {
                    $required_notif.addClass('form-has-error');
                }
            }
            if ($form_profile_student.length) {
                if ($form_profile_student.find('.has-error').length == 0) {
                    $required_notif.removeClass('form-has-error');
                } else {
                    $required_notif.addClass('form-has-error');
                }
            }
        }, 500);
    });

    /**/
    $(document).on('beforeSubmit', '#form-profile-teacher', function() {
        return checkTeacherForm();
    });
    $(document).on('submit', '#form-profile-teacher', function() {
        return checkTeacherForm();
    });
    $(document).on('click', "#form-profile-teacher button[name='settings_and_profile']", function() {
        checkTeacherForm();
    });

});
