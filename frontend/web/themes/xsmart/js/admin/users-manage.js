let $profile_student_form = $('#form-profile-add-edit-student');
let $upload_img_for_user_id = $('#upload-img-for-user-id');
let $edit_action = $('.action-edit');
let $add_action = $('.action-add');
let $ff_count = $('#ff_count');
let $ff_button = $('#ff-button');
let TTL_FF_UPDATE = 60;

/**
 * @param {int} user_id
 * @param {boolean} for_teacher
 * @param {int} need_schedule
 */
function getUserData(user_id, for_teacher, need_schedule)
{
    /**/
    $.ajax({
        type: 'get',
        url: '/admin/get-user-data', //?sort=price_lowest',
        data: {
            user_id: user_id,
            schedule: need_schedule
        },
        dataType: 'json'
    }).done(function (response) {

        if ("User" in response) {
            let User = response.User;

            /**/
            $.each(User, (key, val) => {
                //console.log(`${key} = ${val}`);
                $(`#${key}`).html(val);
            });

            /**/
            if ("images" in User) {
                $.each(User.images, (key, val) => {
                    //console.log(`${key} = ${val}`);
                    $(`#${key}`)[0].src = val;
                });
            }

            /**/
            if ("show_hide_elements" in User) {
                $.each(User.show_hide_elements, (key, val) => {
                    console.log(`${key} = ${val}`);
                    if (val == 'show') {
                        $(`#${key}`).show();
                    } else {
                        $(`#${key}`).hide();
                    }
                });
            }

            /**/
            $('#button-approve-user').attr('data-user_id', user_id);

            /**/
            let additional_server_info = '';
            if ("additional_service_info" in User) {
                $.each(User.additional_service_info, (key, val) => {
                    additional_server_info += `<div class="params-tbl__row"><span>${key}:</span><span>${val}</span></div>`;
                });
            }
            $('#full_additional_server_info').html(additional_server_info);

            /**/
            if ("Schedule" in response) {
                $('#user_schedule').html(response.Schedule);
                if (typeof scheduleCarouselInit == 'function') { scheduleCarouselInit(); }
            }

            /**/
            if (typeof wrapHidden == 'function') { wrapHidden(); }

            /**/
            $('#tabs__item_personal').trigger('click');
            $('#user-info--popup').addClass('_opened');
        } else {
            console.log(response);
            prettyAlert($translate_text_messages.attr('data-msg-15'));
        }

    });
}

/**
 * @param $obj
 * @param select_val
 */
function selectize_me($obj, select_val=null)
{
    if ($obj[0].selectize) {
        $obj[0].selectize.destroy();
    }
    if (select_val) {
        $obj.val(select_val);
    } else {
        $obj.find('option:last').prop('selected', true);
    }
    $obj.selectize();
}

/**
 * @param student_user_id
 */
function initStudentAddEditForm(student_user_id)
{

    $('.help-block').html('');
    $profile_student_form.find('.has-error').each(function() {
        $(this).removeClass('has-error');
    });


    $upload_img_for_user_id.val(student_user_id);

    /* очистим всю форму в начальное состояние */
    $('.div-select-user-speak-also').removeClass('_visible');
    $profile_student_form.find('input').each(function() {
        let $self = $(this);
        if ($self[0].type == 'text' || $self[0].type == 'password' || $self[0].type == 'hidden') {
            $self.val('');
        }
        if ($self[0].type == 'radio' || $self[0].type == 'checkbox') {
            $self[0].checked = false;
            $self[0].disabled = false;
        }
    });
    $profile_student_form.find('select').each(function() {
        selectize_me($(this));
    });
    $profile_student_form.find('.profile-photo__ava').each(function() {
        $(this)[0].src = $(this).data('upload_your_photo');
        $(this).attr('data-user-id', "");
    });
    $profile_student_form.find('textarea').each(function() {
        $(this).val('');
    });


    //return;
    /* заполним форму данными из аякс если это существующий юзер или просто покажем пустую, если это новый юзер */
    if (!student_user_id) {
        $('#trigger-open-add-edit-student-popup')[0].click();
        $edit_action.addClass('_hidden');
        $add_action.removeClass('_hidden');
        $('#profilestudent-user_id').val('0');
    } else {
        $add_action.addClass('_hidden');
        $edit_action.removeClass('_hidden');
        $.ajax({
            type: 'get',
            url: '/admin/get-user-data', //?sort=price_lowest',
            data: {
                user_id: student_user_id,
                schedule: 0
            },
            dataType: 'json'
        }).done(function (response) {

            if ("User" in response) {
                let User = response.User;

                /**/
                $.each(User, (key, val) => {
                    //console.log(`${key} = ${val}`);
                    let $inp = $(`#profilestudent-${key}`);
                    if ($inp.length) {
                        let inp_tag_name = $inp[0].tagName.toLowerCase();
                        let inp_type = $inp[0].type.toLowerCase();
                        if (inp_type == 'text') {
                            $inp.val(val);
                        }
                        if (inp_type == 'hidden') {
                            $inp.val(val);
                        }
                        //console.log(inp_tag_name);
                        if (inp_tag_name == 'textarea') {
                            $inp.val(val);
                        }
                        if (inp_tag_name == 'select') {
                            //console.log(val);
                            selectize_me($inp, val);
                        }
                    }
                    let $inp2 = $(`.profilestudent-radio-${key}`);
                    if ($inp2.length) {
                        //alert($inp2[0].type);
                        //alert($inp2[0].type);
                        //let inp2_type = ($inp.first())[0].type.toLowerCase();
                        //alert($inp2.length)
                        //if (inp2_type == 'radio') {
                            //alert(1);
                            let $inp_ch = $(`.profilestudent-${key}-${val}`);
                            if ($inp_ch.length) {
                                $inp_ch[0].checked = true;
                            }
                        //}
                    }
                });

                /**/
                if ("images" in User) {
                    $.each(User.images, (key, val) => {
                        //console.log(`.profilestudent-${key}`);
                        $profile_student_form.find(`.profilestudent-${key}`).each(function () {
                            $(this)[0].src = val;
                            $(this).attr('data-user-id', User.user_id);
                        });
                    });
                }

                /**/
                if ("user_are_native_codes" in User) {
                    //console.log(User.user_are_native_codes);
                    $.each(User.user_are_native_codes, (key, val) => {
                        $(`#radio-user-are-native-${key}`)[0].click();// = true;
                    });
                }

                /**/
                if ("user_speak_also_codes" in User) {
                    //console.log(User.user_speak_also_codes);
                    $.each(User.user_speak_also_codes, (key, val) => {
                        $(`#radio-user-speak-also-${key}`)[0].click();// = true;
                        $(`#radio-user-speak-also-${key}`).val(val)
                        selectize_me($(`#select-user-speak-also-${key}`), val);
                    });
                }

                /**/
                if ("user_goals_of_education_codes" in User) {
                    //console.log(User.user_goals_of_education_codes);
                    $.each(User.user_goals_of_education_codes, (key, val) => {
                        $(`#radio-user-goals-of-education-${key}`)[0].click();// = true;
                    });
                }

                /**/
                if (User.user_photo === null) {
                    $('.photo-enabled').hide();
                    $('.photo-disabled').show();
                } else {
                    $('.photo-enabled').show();
                    $('.photo-disabled').hide();
                }

            }
            $('#trigger-open-add-edit-student-popup')[0].click();
        });
    }
}

function saveUserData()
{
    /**/
    if ($profile_student_form.find('.has-error').length) {
        //flash_msg($translate_text_messages.attr('data-msg-3'), 'error', FLASH_TIMEOUT);
        prettyAlert($translate_text_messages.attr('data-msg-3'));
        return false;
    }

    /**/
    $profile_student_form.find('.save-student-data').each(function() {
        $(this)[0].disabled = true;
    });

    /**/
    let data = {};

    $profile_student_form.find('.collect-input').each(function() {
        let $inp = $(this);
        //console.log($inp[0].name);
        if ($inp.length) {
            let inp_tag_name = $inp[0].tagName.toLowerCase();
            let inp_type = $inp[0].type; //.toLowerCase();
            if (typeof inp_type != 'undefined') {

                inp_type = $inp[0].type.toLowerCase();
                //console.log(inp_type, inp_tag_name, typeof inp_type, $inp[0].name);

                if (inp_type == 'text') {
                    data[$inp[0].name] = $inp.val();
                }
                if (inp_type == 'hidden') {
                    data[$inp[0].name] = $inp.val();
                }
                if (inp_tag_name == 'textarea') {
                    data[$inp[0].name] = $inp.val();
                }
                if (inp_type == 'radio' && $inp[0].checked) {
                    data[$inp[0].name] = $inp.val();
                }
                if (inp_type == 'checkbox' && $inp[0].checked && !$inp[0].disabled) {
                    data[$inp[0].name] = $inp.val();
                }
                if (inp_tag_name == 'select') {
                    data[$inp[0].name] = $inp.val();
                }

            }
        }

    });

    //$profile_student_form.yiiActiveForm('data').submitting = true;
    //$profile_student_form.yiiActiveForm('validate');

    /**/
    window.setTimeout(function () {

        /**/
        console.log(data);
        data['is_after_validate'] = '1';
        $.ajax({
            type: 'post',
            url: '/admin/save-user-data', //?sort=price_lowest',
            data: data,
            dataType: 'json'
        }).done(function (response) {

            if ("status" in response && response.status && "data" in response && "user_id" in response.data) {

                $('#profilestudent-user_id').val(response.data.user_id);
                $('.profilestudent-user_photo').attr('data-user-id', response.data.user_id);
                $upload_img_for_user_id.val(response.data.user_id);
                $edit_action.removeClass('_hidden');
                $add_action.addClass('_hidden');
                prettyAlert($translate_text_messages.attr('data-msg-18'));

                //students-list-content
                try { $.pjax.reload({container: "#students-list-content", async: false}); }
                catch (e) { console.log('info:: Skipped. Not found pjax container #students-list-content for reload.'); }

            } else {

                prettyAlert(response.info);
                console.log(response.info);

            }
            /**/
            $profile_student_form.find('.save-student-data').each(function() {
                $(this)[0].disabled = false;
            });

        });

    }, 500);

    return false;
}

function checkNewFormFills()
{
    $.ajax({
        type: 'get',
        url: '/admin/get-ff-count',
        dataType: 'json'
    }).done(function (response) {
        if ("status" in response && response.status) {

            let old_count = parseInt($ff_count.attr('data-count'));
            let new_count = parseInt(response.ff_count);
            if (old_count != new_count) {
                $ff_count.html(new_count);
                $ff_count.attr('data-count', new_count);

                if (new_count > 0) {
                    $ff_button.removeClass('hidden');
                    try { $.pjax.reload({container: "#ff-list-content", async: true}); }
                    catch (e) { console.log('info:: Skipped. Not found pjax container #ff-list-content for reload.'); }
                } else {
                    $ff_button.addClass('hidden');
                }

            }
        }
    });

    setTimeout(function() {
        checkNewFormFills();
    }, TTL_FF_UPDATE*1000);
}

/** ***** **/
$(document).ready(function() {

    /**/
    setTimeout(function() {
        checkNewFormFills();
    }, TTL_FF_UPDATE*1000);

    /**/
    $(document).on('click', '.show-teacher-info', function() {

        getUserData($(this).data('user_id'), true, 1);

    });

    /**/
    $(document).on('click', '.show-student-info', function() {

        getUserData($(this).data('user_id'), false, 0);

    });

    /**/
    $(document).on('click', '#button-approve-user', function() {

        $.ajax({
            type: 'get',
            url: '/admin/approve-user', //?sort=price_lowest',
            data: {
                user_id: $(this).data('user_id')
            },
            dataType: 'json'
        }).done(function (response) {

            if ("status" in response && "result" in response) {
                if (response.status) {
                    try {
                        $.pjax.reload({container: "#teachers-list-content", async: false});
                    }
                    catch (e) {
                        console.log('info:: Skipped. Not found pjax container #teachers-list-content for reload.');
                    }
                    $('#teacher-info--popup').removeClass('_opened');
                    flash_msg(response.result, 'success', FLASH_TIMEOUT);
                } else {
                    prettyAlert(response.result);
                }
            }

        });

    });

    /**/
    $(document).on('click', '.js-open-edit-student-popup', function() {

        initStudentAddEditForm($(this).data('student_user_id'));

    });

    /**/
    $(document).on('click', '.js-open-edit-teacher-popup', function() {

        //initTeacherAddEditForm($(this).data('teacher_user_id'));

    });


    /**/
    $(document).on('click', '.save-student-data', function() {

        //if ($profile_student_form.find('.has-error').length) {
        //    prettyAlert($translate_text_messages.attr('data-msg-3'));
        //    return false;
        //}

    });
    $profile_student_form.on('onsubmit', function() {
        return false;
    });
    $profile_student_form.on('beforeSubmit', function() {

        let $yiiform = $(this);
        $.ajax({
            type: $yiiform.attr('method'),
            url: $yiiform.attr('action'),
            data: $yiiform.serializeArray()
        }).done(function(data) {
            console.log(data);
            //if(!data.length) {
            //    //alert(1);
            //    console.log('starting saveUserData()');
            //    saveUserData();
            //} else {
            //    $yiiform.yiiActiveForm('updateMessages', data, true);
            //    //prettyAlert($translate_text_messages.attr('data-msg-3'));
            //}

            $yiiform.yiiActiveForm('updateMessages', data, true);
            saveUserData();
        });

        return false;
    });

    /**/
    $(document).on('click', '.confirm-delete-dialog', function(e) {
        e.preventDefault();
        let $self = $(this);
        prettyConfirm(function () {
            $.ajax({
                type: 'get',
                url: '/admin/delete-user',
                data: {
                    user_id: $self.attr('data-user_id')
                },
                dataType: 'json'
            }).done(function (response) {

                try { $.pjax.reload({container: "#students-list-content", async: true}); }
                catch (e) { console.log('info:: Skipped. Not found pjax container #students-list-content for reload.'); }
                try { $.pjax.reload({container: "#teachers-list-content", async: true}); }
                catch (e) { console.log('info:: Skipped. Not found pjax container #students-list-content for reload.'); }

            });
        }, false, $self.data('confirm-text'));

        return false;
    });

    /**/
    $(document).on('ready pjax:end', function (event) {
        if (typeof wrapHidden == 'function') { wrapHidden(); }
    });

    $(document).on('click', '.set-ff-as-read', function() {
        let $self = $(this);
        let lead_id = $self.data('lead_id');

        $.ajax({
            type: 'get',
            url: '/admin/set-ff-as-read',
            data: {
                lead_id: lead_id
            },
            dataType: 'json'
        }).done(function (response) {

            if ("status" in response && response.status) {

                $ff_count.html(response.ff_count);
                $ff_count.attr('data-count', response.ff_count);
                if (response.ff_count > 0) {
                    $ff_button.removeClass('hidden');
                } else {
                    $ff_button.addClass('hidden');
                    $('#form-fills-popup').removeClass('_opened');
                }
                //$(`#review_${lead_id}`).remove();
                try { $.pjax.reload({container: "#ff-list-content", async: true}); }
                catch (e) { console.log('info:: Skipped. Not found pjax container #ff-list-content for reload.'); }
                //flash_msg(response.result, 'success', FLASH_TIMEOUT);

            } else {
                console.log(response.result);
            }

        });
    });
});