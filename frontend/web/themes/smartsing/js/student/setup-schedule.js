let $date_start = $('#date-start-input');
let $schedule_conf = $('#set-schedule-step-conf');
let currentStepNum;

/**
 * @param {int} num
 * @returns {*}
 */
function showStep(num)
{
    $('.set-schedule-steps').hide();
    $(`.set-schedule-step-${num}`).show();
    window.scroll(0, 0);

    let funct = `after_show_step_${num}`;
    if (typeof window[funct] == 'function') {
        return window[funct]();
    }

    return void(0);
}

/**
 * @returns {*}
 */
function after_show_step_3()
{
    try {
        $('#day-1').trigger('click');
    } catch (e) {

    }
    return void(0);
}

/**
 * @returns {*}
 */
function after_show_step_4()
{
    findAvailableTeachers();
    return void(0);
}

/**
 * @param {int} num
 */
function changeStep(num)
{
    let funct = `before_change_step_${num}`;
    if (typeof window[funct] == 'function') {
        if (!window[funct]()) {
            return false;
        }
    }

    if (typeof user_schedule == 'undefined' && num > 2) {
        prettyAlert('Waiting for schedule load... Please try again.');
        return false;
    }

    $.ajax({
        type: 'get',
        url: '/student/change-step',
        data: {
            step: num,
            date_start: $date_start.val()
        },
        dataType: 'json'
    }).done(function (response) {
        if ("data" in response && "status" in response && response.status) {

            if (response.data.changed) {

                showStep(num);

            } else {
                prettyAlert(response.data.info);
            }

        } else {
            console.log(response);
            prettyAlert('An internal server error occurred.');
        }

    });

    return true;
}

/**
 * @returns {boolean}
 */
function before_change_step_3()
{
    let test = $.trim($date_start.val());
    if (test == '') {
        prettyAlert($schedule_conf.data('check-error-3'));
        return false;
    }

    return true;
}

/**
 * @returns {boolean}
 */
function before_change_step_4()
{
    if (total_selected_count_lesson == 0) {
        prettyAlert($schedule_conf.data('check-error-4'));
        return false;
    }

    return true;
}

/**
 *
 */
function selectDateOnDatePicker()
{
    if ($date_start.length) {
        //console.log($date_start.val());
        let test = $date_start.val().split('-');
        //console.log(test);
        let $day = parseInt(test[2]),
            $month = parseInt(test[1]) - 1,
            $year = parseInt(test[0]);

        $('#begining-calendar').find('.datepicker--cell-day').each(function () {
            //console.log($(this));
            $(this).removeClass('-selected-');
            if ($(this).data('date') == $day && $(this).data('month') == $month && $(this).data('year') == $year) {
                $(this).addClass('-selected-');
            }
        });
    }
}

/**
 *
 */
function findAvailableTeachers()
{
    $('.teachers-result').hide();
    $('.searching-teachers').show();


    let $available_teachers = $('#teachers-list');
    const wideCoachesSlider = document.querySelector('#teachers-list');
    const $wideCoachesSlider = $(wideCoachesSlider);

    try {
        $wideCoachesSlider.slick('unslick');
    } catch (e) {

    }
    //$available_teachers.html('');


    $.ajax({
        type: 'get',
        url: '/student/find-available-teachers?date_start=' + $date_start.val(),
        dataType: 'json'
    }).done(function (response) {
        if ("data" in response && "status" in response) {

            if (response.status) {
                let avail = response.data;
                let avail_list = "";
                let teacher_list = "";
                console.log(avail);
                let tpl = $('#tpl-teacher').html();
                //console.log(typeof avail['full_match']);

                avail_list += "Full match teachers hours: <br />";
                if (typeof avail['full_match'] != 'undefined') {
                    let avail_full = avail['full_match'];
                    for (let i = 0; i < avail_full.length; i++) {
                        avail_list += '<a href="#" class="void-0 set-teacher-for-student" data-teacher-user-id="' + avail_full[i]['teacher_user_id'] + '">' + avail_full[i]['user_full_name'] + "</a>"
                            + " (" + avail_full[i]['user_email'] + ")"
                            + " number_of_matching_hours="
                            + avail_full[i]['number_of_matching_hours']
                            + "<br />";
                        teacher_list += tpl.replace(/\{([a-z\_]+)\}/g, function (s, e) {
                            return avail_full[i][e];
                        });
                    }
                } else {
                    avail_list += "no full match hours teacher found";
                }


                avail_list += "<br /><br />";


                avail_list += "Partial match teachers hours: <br />";
                //console.log(typeof avail['partial_match']);
                if (typeof avail['partial_match'] != 'undefined') {
                    let avail_partial = avail['partial_match'];
                    for (i = 0; i < avail_partial.length; i++) {
                        avail_list += '<a href="#" class="void-0 set-teacher-for-student" data-teacher-user-id="' + avail_partial[i]['teacher_user_id'] + '">' + avail_partial[i]['user_full_name'] + "</a>"
                            + " (" + avail_partial[i]['user_email'] + ")"
                            + " number_of_matching_hours="
                            + avail_partial[i]['number_of_matching_hours']
                            + "<br />";
                    }
                } else {
                    avail_list += "no partial match hours teacher found";
                }

                //$('#teachers-list').slick('unslick');

                if (teacher_list !== "") {

                    $available_teachers.html(teacher_list);
                    $available_teachers.find('.user-photo-tpl').each(function() {
                        $(this).attr('src', $(this).data('src'));
                    });
                    $('.teachers-result').hide();
                    $('.teachers-found').show();

                } else {

                    $('.teachers-result').hide();
                    $('.teachers-found').hide();
                    $('.no-teachers-found').show();
                }

                /**/
                if ($wideCoachesSlider.length) {
                    const wideCoachesSliderWrap = wideCoachesSlider.parentNode;
                    const $wideCoachesSliderWrap = $(wideCoachesSliderWrap);

                    $wideCoachesSlider.slick({
                        dots: false,
                        arrows: true,
                        speed: 650,
                        slidesToScroll: 1,
                        slidesToShow: 4,
                        infinite: false,
                        useTransform: true,
                        useCss: true,
                        prevArrow: $wideCoachesSliderWrap.find('.slider-nav__item--prev'),
                        nextArrow: $wideCoachesSliderWrap.find('.slider-nav__item--next'),
                        responsive: [
                            {
                                breakpoint: 1261,
                                settings: {
                                    slidesToShow: 3
                                }
                            },
                            {
                                breakpoint: 961,
                                settings: {
                                    slidesToShow: 2
                                }
                            },
                            {
                                breakpoint: 541,
                                settings: {
                                    slidesToShow: 1
                                }
                            }
                        ]
                    });
                    //console.log(document.hasScrollbar());
                    //window.initModules('modal');
                }


            } else {
                prettyAlert(response.data);
            }

        } else {
            console.log(response);
            prettyAlert('An internal server error occurred.');
        }

    });
}

/**
 *
 */
function unsetTeacherForStudent()
{
    $.ajax({
        type: 'get',
        url: '/student/unset-teacher-for-student',
        dataType: 'json'
    }).done(function (response) {
        if ("data" in response && "status" in response) {

            if (response.status) {

                prettyAlert(response.data);

            } else {

                prettyAlert(response.data);

            }

        } else {
            console.log(response);
            prettyAlert('An internal server error occurred.');
        }

    });
}

/**
 * @param {integer} teacher_user_id
 */
function setTeacherForStudent(teacher_user_id)
{
    $.ajax({
        type: 'get',
        url: '/student/set-teacher-for-student?teacher_user_id=' + teacher_user_id + '&date_start=' + $date_start.val(),
        dataType: 'json'
    }).done(function (response) {
        if ("data" in response && "status" in response) {

            if (response.status) {

                console.log(response.data);
                $('#choose-teacher-modal').find('.js-close-modal').first().trigger('click');
                let $thnx =  $('#thnx-msg-step5');
                let tmp = $thnx.html();

                //for (let i = 0; i < response.data.length; i++) {
                let replaced =  tmp.replace(/\{([a-z\_]+)\}/g, function (s, e) {
                    return response.data[e];
                });
                //}

                $thnx.html(replaced);

                changeStep(5);

            } else {

                prettyAlert(response.data);
                console.log(response.data);

            }

        } else {
            console.log(response);
            prettyAlert('An internal server error occurred.');
        }

    });
}

/**
 *
 */
function modal_closed_choose_teacher_modal()
{
    let iframe = document.querySelector('#teacher-video-iframe');
    if (iframe) {
        iframe.setAttribute('src', "");
        iframe.remove();
    }

    $('#user-local-video')[0].src = "";
}

/**
 *
 */
function setupTeacherVideo() {
    //return;

    let generateURL = function(id) {
        let query = '?rel=0&showinfo=0&autoplay=1';
        return 'https://www.youtube.com/embed/' + id + query;
    };

    let video = document.querySelector('#video-about-teacher');
    let link = video.querySelector('.video__link');
    //let media = $link.attr('href');
    let button = video.querySelector('.video__btn');
    let video_id = $(link).attr('data-youtube_video_id');
    let iframe = document.querySelector('#teacher-video-iframe');
    if (!iframe) {
        iframe = document.createElement('iframe');
        iframe.id = 'teacher-video-iframe';
        video.appendChild(iframe);
    }
    iframe.setAttribute('src', "");

    $(link).show();
    $(button).show();
    video.classList.remove('_ready');
    video.classList.remove('video--enabled');
    iframe.classList.remove('video__media');

    video.addEventListener('click', () => {

        //$(iframe).show();
        iframe.setAttribute('allowfullscreen', '');
        iframe.setAttribute('allow', 'autoplay');
        iframe.setAttribute('src', generateURL(video_id));
        iframe.setAttribute('autoplay', 1);

        $(link).hide();
        $(button).hide();
        video.classList.add('_ready');
        iframe.classList.add('video__media');
        return false;

    });

    //link.removeAttribute('href');
    video.classList.add('video--enabled');
}

/** ***** **/
$(document).ready(function() {

    /* попробуем получить расписание ученика, если оно есть */
    getSchedule();

    /* если есть элемент который указывает нам текущий день недели, то переключим расписание на этот день недели */
    let $test = $('#main-schedule-container');
    if ($test.length && typeof $test.data('current-week-day') != 'undefined') {
        $(`#day-${$test.data('current-week-day')}`).trigger('click');
    }

    /* создадим календарь для выбора даты начала занятий */
    let maxCountDayAfterStart = 21;
    let dEnd = new Date();
    //dEnd.setMonth(dEnd.getMonth() + 3);
    dEnd.setDate(dEnd.getDate() + maxCountDayAfterStart);
    $('#my-js-big-datepicker').datepicker({
        inline: true,
        language: 'custom_ru',
        dateFormat: 'yyyy-mm-dd',
        classes: 'big-datepicker',
        navTitles: {
            days: 'MM',
            months: 'MM',
            years: 'yyyy'
        },
        prevHtml: '<svg class="svg-icon" width="11" height="20"><use xlink:href="#left-2"></use></svg>',
        nextHtml: '<svg class="svg-icon" width="11" height="20"><use xlink:href="#right-2"></use></svg>',
        startDate: new Date($date_start.val()),
        defaultDate: new Date($date_start.val()),
        minDate: new Date(),
        maxDate: dEnd
    });
    setTimeout(function() {
        selectDateOnDatePicker();
    }, 1000);

    /* покажем первый шаг */
    showStep(parseInt($schedule_conf.data('current-step')));
    //showStep(4);

    /* переход на второй шаг при выборе количества предпочитаемых занятий в неделю */
    $(document).on('click', 'input[name=days-count]', function () {
        changeStep(2);
    });

    /* смена шагов при клике на соответствующую кнопку */
    $(document).on('click', '.step__nav-btn', function () {
        if (typeof $(this).data('check') !== 'undefined') {
            let funct = $(this).data('check');
            if (typeof window[funct] == 'function') {
                let test = window[funct]();
                if (!test) {
                    prettyAlert($(this).data('check-error'));
                    return false;
                }
            }
        }
        changeStep($(this).data('step'));
    });

    /* переход на третий шаг в случае клика на кнопку даты "Незамедлительно" */
    $(document).on('click', '.date-start-immediately', function () {
        //$('#date-start-select-calendar').hide();
        let d = new Date();
        let Y = d.getFullYear();
        let M = d.getMonth() + 1;
        let D = d.getDate();
        M = (M<10) ? '0' + M : M;
        D = (D<10) ? '0' + D : D;
        let d_str = Y + '-' + M + '-' + D;
        $date_start.val(d_str);
        changeStep(3);
    });

    /* обработка календаря при его открытии когда клик на кнопке выбора даты вручную */
    $(document).on('click', '.date-start-mannualy', function () {
        //$('.datepicker--cell-day').removeClass('-selected-');
        //$date_start.val('');
        selectDateOnDatePicker();
    });

    /* обработка клика на конкретной дате календаря */
    $(document).on('click', '.datepicker--cell-day', function() {
        if (!$(this).hasClass('-selected-')) {
            if (!$(this).hasClass('-disabled-')) {
                let day = parseInt($(this).data('date'));
                let month = parseInt($(this).data('month')) + 1;
                let year = parseInt($(this).data('year'));
                if (day < 10) {
                    day = '0' + day;
                }
                if (month < 10) {
                    month = '0' + month;
                }

                $date_start.val(year + '-' + month + '-' + day);
            }
        } else {
            $date_start.val('');
        }
    });

    /* установка или снятие отметки с конкретного часа в расписании */
    $(document).on('change', '.js-beginning-time', function() {
        changeWorkHour($(this));
    });

    /* открытие детальной информации об учителе */
    $(document).on('click', '.js-open-coach-details', function() {
        let $this = $(this);
        let $video_about_teacher = $('#video-about-teacher');
        let $video_about_teacher_local = $('#video-about-teacher-local');

        let $coach_detail_modal = $('#choose-teacher-modal');
        $coach_detail_modal.find('.teacher_user_id').first().attr('data-teacher_user_id', $this.data('teacher_user_id'));
        $coach_detail_modal.find('.user_photo').first().attr('src', $this.data('user_photo'));
        $coach_detail_modal.find('.user_first_name').first().html($this.data('user_first_name'));
        $coach_detail_modal.find('.user_age').first().html($this.data('user_age'));
        $coach_detail_modal.find('.user_music_genres').first().html($this.data('user_music_genres'));
        $coach_detail_modal.find('.user_additional_info').first().html($this.data('user_additional_info'));
        $coach_detail_modal.find('.admin_notice').first().html($this.data('admin_notice'));


        if ($this.data('youtube_video') !== null && $this.data('youtube_video') != 'null') {

            //alert($this.data('youtube_video'));
            //let $coach_detail_modal = $(`#${$(this).data('modal-id')}`);
            $coach_detail_modal.find('.youtube_video').first().attr('href', $this.data('youtube_video'));
            $coach_detail_modal.find('.youtube_image_webp').first().attr('srcset', $this.data('youtube_image_webp'));
            $coach_detail_modal.find('.youtube_image_jpg').first().attr('src', $this.data('youtube_image_jpg'));
            $coach_detail_modal.find('.youtube_video_id').first().attr('data-youtube_video_id', $this.data('youtube_video_id'));

            $video_about_teacher_local.hide();
            $video_about_teacher.show();
            setupTeacherVideo();

        } else if ($this.data('user_local_video') != 'null') {

            //alert(2);
            $('#user-local-video')[0].src = $this.data('user_local_video');
            $video_about_teacher.hide();
            $video_about_teacher_local.show();

        } else {
            $video_about_teacher.hide();
            $video_about_teacher_local.hide();
        }

        /**/
        //console.log($this[0].dataset);
        //jQuery.each($this[0].dataset, function (key, val) {
        //    $coach_detail_modal.find(`.${key}`).first().html(val);
        //});
    });

    /* установка выбранного учителя для ученика */
    $(document).on('click', '.js-select-teacher', function() {
        setTeacherForStudent($(this).data('teacher_user_id'));
    });

});

$('#choose-teacher-modal').on('hide.bs.modal', function() {
    alert(1);
});