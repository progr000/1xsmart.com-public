let $tpl_loading_teachers = $('#tpl-loading-teachers');
let $tpl_loading_methodists = $('#tpl-loading-methodists');
let $tpl_loading_date = $('#tpl-loading-date');

let $teacher_user_id_select = $('#student-teacher_user_id');
let $methodist_user_id_select = $('#student-methodist_user_id');
let $introduce_lesson_time_select = $('#student-introduce_lesson_time');

/**
 * @param {int} student_user_id
 * @param {int} methodist_user_id
 */
function getAvailableMethodistSchedule(student_user_id, methodist_user_id)
{
    $introduce_lesson_time_select.prop("disabled", true).attr('disabled', 'disabled');
    $introduce_lesson_time_select.html($tpl_loading_date.html());

    if (methodist_user_id > 0) {
        $.ajax({
            type: 'get',
            url: '/admin-common/get-available-methodist-schedule',
            data: {
                student_user_id: student_user_id,
                methodist_user_id: methodist_user_id
            },
            dataType: 'json'
        }).done(function (response) {

            if ("data" in response && "status" in response && response.status) {

                let $introduce_lesson_time_select = $('#student-introduce_lesson_time');
                //$introduce_lesson_time_select.attr('data-current-val', $this.data('teacher_user_id'));
                $introduce_lesson_time_select.html(response.data.available_lessons_time);
                $introduce_lesson_time_select.prop("disabled", false).removeAttr('disabled');
                //$introduce_lesson_time_select.find(`option[value=${$this.data('teacher_user_id')}]`).first().prop('selected', true);

            } else {
                console.log(response);
                prettyAlert('An internal server error occurred.');
            }

        });
    }
}

/** ***** **/
$(document).ready(function() {

    /**/
    $(document).on('click', '.js-show-all-data', function() {
        let $this = $(this);
        let $modal = $(`#${$this.data('modal-id')}`);
        let $receiver_title = $modal.find('.modal__title-receiver').first();
        let $receiver_html = $modal.find('.receiver-container').first();
        let $sender_html = $(`#${$this.data('get-content-from-div-id')}`);
        $receiver_title.html($sender_html.data('title-for-modal'));
        $receiver_html.html($sender_html.html());
        initMessaging();
    });

    /**/
    $(document).on('change', '#operatorslistsearch-sort', function() {
        let $this = $(this);
        let $sel = $this.find('option:selected').first();
        //console.log($sel.data('sort-val'));
        //$('#select option:selected')
        $('#sort-data').val($sel.data('sort-val'));
        $('#submit-filter-operators-list').trigger('click');
    });

    /**/
    $(document).on('change', '#methodistslistsearch-sort', function() {
        let $this = $(this);
        let $sel = $this.find('option:selected').first();
        //console.log($sel.data('sort-val'));
        //$('#select option:selected')
        $('#sort-data').val($sel.data('sort-val'));
        $('#submit-filter-methodists-list').trigger('click');
    });

    /**/
    $(document).on('change', '#request-search-sort', function() {
        let $this = $(this);
        let $sel = $this.find('option:selected').first();
        //console.log($sel.data('sort-val'));
        //$('#select option:selected')
        $('#teacher-request-sort-data').val($sel.data('sort-val'));
        $('#submit-filter-teachers-request').trigger('click');
    });

    /**/
    $(document).on('change', '#teacherslistsearch-sort', function() {
        let $this = $(this);
        let $sel = $this.find('option:selected').first();
        //console.log($sel.data('sort-val'));
        //$('#select option:selected')
        $('#teacher-sort-data').val($sel.data('sort-val'));
        $('#submit-filter-teachers-list').trigger('click');
    });

    /**/
    $(document).on('change', '#studentslistsearch-sort', function() {
        let $this = $(this);
        let $sel = $this.find('option:selected').first();
        //console.log($sel.data('sort-val'));
        //$('#select option:selected')
        $('#student-sort-data').val($sel.data('sort-val'));
        $('#submit-filter-students-list').trigger('click');
    });

    /**/
    $(document).on('beforeSubmit', '.filter-search-list', function() {
        let $frm = $(this);
        let $sel = $frm.find('.list-search-sort').first();
        let $opt = $sel.find('option:selected').first();

        $frm.find('.field-sort-data').first().val($opt.data('sort-val'));
    });

    /**/
    $(document).on('click', '.js-open-operators-list-modal', function() {
        $('#operator-for-lead_id').val($(this).data('lead_id'));
    });

    /**/
    $(document).on('click', '.js-assign-operator-for-lead', function() {
        let $this = $(this);

        $.ajax({
            type: 'get',
            url: '/admin/assign-operator-for-lead',
            data: {
                lead_id: $('#operator-for-lead_id').val(),
                operator_user_id: $this.data('operator_user_id')
            },
            dataType: 'json'
        }).done(function (response) {

            if ("status" in response && response.status) {

                $.pjax.reload({container: "#students-request-content", async: false});
                $('#operators-list-modal').find('.js-close-modal').first().trigger('click');

            } else {
                console.log(response);
                prettyAlert('An internal server error occurred.');
            }

        });
    });

    /**/
    $(document).on('click', '.js-open-request-info-modal', function() {
        //help-block
        let $this = $(this);

        $('#request-lead_id').html($this.data('lead_id'));
        $('#request-lead_name').html($this.data('lead_name'));
        $('#request-lead_created').html($this.data('lead_created'));
        $('#request-lead_phone').html($this.data('lead_phone'));
        $('#request-operator_notice').html($this.data('operator_notice'));
        $('#request-textarea-operator_notice').val($this.data('operator_notice'));
        $('#request-lead_email').html($this.data('lead_email'));
        $('#request-additional_service_info').html($this.data('additional_service_info'));
    });

    /**/
    $(document).on('click', '.js-btn-add-new-operator', function() {
        //help-block
        let $this = $(this);
        let $modal = $(`#${$this.data('modal-id')}`);
        $modal.find('input:text').each(function() {
            $(this).val('');
        });
        $modal.find('.input-wrap').each(function() {
            $(this).removeClass('has-error');
        });
        $modal.find('.help-block').each(function() {
            $(this).html('');
        });

        let is_new = parseInt($this.data('is-new'));
        let $title = $('#operator-modal-title');
        $title.html($title.data(`is-new-${is_new}`));
        let $btn_submit = $('#operator-modal-btn-submit');
        $btn_submit.html($btn_submit.data(`is-new-${is_new}`));

        $('#operator-user_id').val($this.data('user_id'));
        $('#operator-user_first_name').val($this.data('user_first_name'));
        $('#operator-user_middle_name').val($this.data('user_middle_name'));
        $('#operator-user_last_name').val($this.data('user_last_name'));
        $('#operator-user_phone').val($this.data('user_phone'));
        $('#operator-user_email').val($this.data('user_email'));
        $('#operator-_user_skype').val($this.data('_user_skype'));
        $('#operator-_user_telegram').val($this.data('_user_telegram'));
        $('#operator-additional_service_notice').val($this.data('additional_service_notice'));
        $('#operator-admin_notice').val($this.data('admin_notice'));
    });

    /**/
    $(document).on('click', '.js-btn-add-new-methodist', function() {
        //help-block
        let $this = $(this);
        let $modal = $(`#${$this.data('modal-id')}`);
        $modal.find('input:text').each(function() {
            $(this).val('');
        });
        $modal.find('.input-wrap').each(function() {
            $(this).removeClass('has-error');
        });
        $modal.find('.help-block').each(function() {
            $(this).html('');
        });

        let is_new = parseInt($this.data('is-new'));
        let $title = $('#methodist-modal-title');
        $title.html($title.data(`is-new-${is_new}`));
        let $btn_submit = $('#methodist-modal-btn-submit');
        $btn_submit.html($btn_submit.data(`is-new-${is_new}`));

        $('#methodist-user_id').val($this.data('user_id'));
        $('#methodist-user_first_name').val($this.data('user_first_name'));
        $('#methodist-user_middle_name').val($this.data('user_middle_name'));
        $('#methodist-user_last_name').val($this.data('user_last_name'));
        $('#methodist-user_phone').val($this.data('user_phone'));
        $('#methodist-user_email').val($this.data('user_email'));
        $('#methodist-_user_skype').val($this.data('_user_skype'));
        $('#methodist-_user_telegram').val($this.data('_user_telegram'));
        $('#methodist-additional_service_notice').val($this.data('additional_service_notice'));
        $('#methodist-admin_notice').val($this.data('admin_notice'));
    });

    /**/
    $(document).on('click', '.js-btn-add-new-teacher', function() {
        //help-block
        let $this = $(this);
        let $modal = $(`#${$this.data('modal-id')}`);
        $modal.find('input:text').each(function() {
            $(this).val('');
        });
        $modal.find('.input-wrap').each(function() {
            $(this).removeClass('has-error');
        });
        $modal.find('.help-block').each(function() {
            $(this).html('');
        });

        let is_new = parseInt($this.data('is-new'));
        let $title = $('#teacher-modal-title');
        $title.html($title.data(`is-new-${is_new}`));
        let $btn_submit = $('#teacher-modal-btn-submit');
        $btn_submit.html($btn_submit.data(`is-new-${is_new}`));

        $('#teacher-lead_id').val($this.data('lead-id'));
        $('#teacher-user_id').val($this.data('user_id'));
        $('#teacher-user_first_name').val($this.data('user_first_name'));
        $('#teacher-user_middle_name').val($this.data('user_middle_name'));
        $('#teacher-user_last_name').val($this.data('user_last_name'));
        $('#teacher-user_phone').val($this.data('user_phone'));
        $('#teacher-user_email').val($this.data('user_email'));
        $('#teacher-_user_skype').val($this.data('_user_skype'));
        $('#teacher-_user_telegram').val($this.data('_user_telegram'));
        $('#teacher-additional_service_notice').val($this.data('additional_service_notice'));
        $('#teacher-admin_notice').val($this.data('admin_notice'));
        $('#teacher-user_youtube_video').val($this.data('user_youtube_video'));
    });

    /**/
    $(document).on('click', '.js-btn-reject-new-request', function() {
        let $this = $(this);

        prettyConfirm(
            function () {
                window.location.href = $this.data('href');
            },
            function () {

            },
            "Вы уверены что хотите отказать пользователю.<br />Будет сформирован отказ и отправен на емейл.<br />Заявка будет удалена."
        );

        return false;
    });

    /**/
    $(document).on('click', '.js-btn-add-new-student', function() {
        //help-block
        let $this = $(this);
        let $modal = $(`#${$this.data('modal-id')}`);
        $modal.find('input:text').each(function() {
            $(this).val('');
        });
        $modal.find('.input-wrap').each(function() {
            $(this).removeClass('has-error');
        });
        $modal.find('.help-block').each(function() {
            $(this).html('');
        });

        let is_new = parseInt($this.data('is-new'));
        let $title = $('#student-modal-title');
        $title.html($title.data(`is-new-${is_new}`));
        let $btn_submit = $('#student-modal-btn-submit');
        $btn_submit.html($btn_submit.data(`is-new-${is_new}`));

        $('#student-user_id').val($this.data('user_id'));
        $('#student-user_first_name').val($this.data('user_first_name'));
        $('#student-user_phone').val($this.data('user_phone'));
        $('#student-user_email').val($this.data('user_email'));
        $('#student-_user_skype').val($this.data('_user_skype'));
        $('#student-_user_telegram').val($this.data('_user_telegram'));
        $('#student-additional_service_notice').val($this.data('additional_service_notice'));
        $('#student-admin_notice').val($this.data('admin_notice'));
        $('#student-operator_notice').val($this.data('operator_notice'));
        $('#student-operator_user_id').find(`option[value=${$this.data('operator_user_id')}]`).first().prop('selected', true);

        $teacher_user_id_select.attr('data-current-val', $this.data('teacher_user_id'));
        $teacher_user_id_select.prop("disabled", true).attr('disabled', 'disabled');
        $teacher_user_id_select.html($tpl_loading_teachers.html());

        $methodist_user_id_select.attr('data-current-val', $this.data('methodist_user_id'));
        $methodist_user_id_select.prop("disabled", true).attr('disabled', 'disabled');
        $methodist_user_id_select.html($tpl_loading_methodists.html());

        $introduce_lesson_time_select.prop("disabled", true).attr('disabled', 'disabled');
        $introduce_lesson_time_select.html($tpl_loading_date.html());

        $.ajax({
            type: 'get',
            url: '/admin-common/get-available-methodists-and-teachers-for',
            data: { student_user_id: $this.data('user_id') },
            dataType: 'json'
        }).done(function (response) {

            if ("data" in response && "status" in response && response.status) {

                $teacher_user_id_select.html(response.data.teachers_list);
                $teacher_user_id_select.prop("disabled", false).removeAttr('disabled');
                let $selected_teacher = $teacher_user_id_select.find(`option[value=${$this.data('teacher_user_id')}]`).first();
                $selected_teacher.prop('selected', true);
                if (parseInt($this.data('teacher_user_id')) > 0) {
                    $selected_teacher.html($selected_teacher.html() + ' (назначенный на данный момент)');
                }

                $methodist_user_id_select.html(response.data.methodists_list);
                $methodist_user_id_select.prop("disabled", false).removeAttr('disabled');
                let $selected_methodist = $methodist_user_id_select.find(`option[value=${$this.data('methodist_user_id')}]`).first();
                $selected_methodist.prop('selected', true);
                if (parseInt($this.data('methodist_user_id')) > 0) {
                    $selected_methodist.html($selected_methodist.html() + ' (назначенный на данный момент)');
                }

                let methodist_user_id = parseInt($this.data('methodist_user_id'));
                if (methodist_user_id > 0) {
                    getAvailableMethodistSchedule($this.data('user_id'), methodist_user_id);
                }

            } else {
                console.log(response);
                prettyAlert('An internal server error occurred.');
            }

        });
    });

    /**/
    $(document).on('change', '#student-teacher_user_id', function() {
        let $this = $(this);
        let is_new = (parseInt($('#student-user_id').val()) == 0);
        let current_val = parseInt($this.attr('data-current-val'));
        if ((current_val > 0) && (current_val != $this.val()) && !is_new) {
            prettyConfirm(
                function () {
                    // $this.attr('data-current-val', $this.val());
                    // возможно тут будем отправлять на сервер запрос для смены учителя через аякс
                    // хотя возможно и через полную отправку формы по сабмиту
                },
                function () {
                    $this.val(current_val);
                },
                "Внимание, все назначенные занятия этого ученика с предыдущим учителем будут отменены (если они есть).<br />Точно хотите продолжить?"
            );
        } else {
            // $this.attr('data-current-val', $this.val());
            // возможно тут будем отправлять на сервер запрос для смены учителя через аякс
            // хотя возможно и через полную отправку формы по сабмиту
        }
    });

    /**/
    $(document).on('change', '#student-methodist_user_id', function() {
        let $this = $(this);
        let student_user_id = parseInt($('#student-user_id').val());
        let is_new = (student_user_id == 0);
        let current_val = parseInt($this.attr('data-current-val'));
        if ((current_val > 0) && (current_val != $this.val()) && !is_new) {
            prettyConfirm(
                function () {
                    // $this.attr('data-current-val', $this.val());
                    // возможно тут будем отправлять на сервер запрос для смены методиста через аякс
                    // хотя возможно и через полную отправку формы по сабмиту
                    getAvailableMethodistSchedule(student_user_id, $this.val());
                },
                function () {
                    $this.val(current_val);
                    getAvailableMethodistSchedule(student_user_id, $this.val());
                },
                "Внимание, назначенное занятие этого ученика с предыдущим методистом будет отменено (если оно есть).<br />Точно хотите продолжить?"
            );
        } else {
            // $this.attr('data-current-val', $this.val());
            // возможно тут будем отправлять на сервер запрос для смены учителя через аякс
            // хотя возможно и через полную отправку формы по сабмиту
            getAvailableMethodistSchedule(student_user_id, $this.val());
        }
    });

    /**/
    $(document).on('click focus change', '#student-introduce_lesson_time', function() {
        if (parseInt($methodist_user_id_select.val()) == 0) {
            prettyAlert('Сначала необходимо выбрать методиста.');
            return false;
        }
    });
});