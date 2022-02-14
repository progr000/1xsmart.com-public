// https://learn.javascript.ru/same-origin-policy
// что бы заработал доступ к ифрейму жисти, нужно:
// 1. установить его на то же домене что и сайт но только установить на порту 8443
// 2. сделать на нгинкс, который работает с нашим сайтом прокси конфиг, который будет проксировать жисти с 8443 порта на поддомен  jitsi.нашдоменсайта
// 3. в яваскрипте жисти который крутится в докере добавить строку document.domain = 'нашдоменсайта'
// 4. в этом яваскрипте написать такой же домен document.domain = 'нашдоменсайта'
// sudo docker exec -it docker-jitsi-meet_web_1 bash
// docker exec -it docker-jitsi-meet_web_1 bash
// и внутри
// document_root: /usr/share/jitsi-meet;
//document.domain = 'smartsing.net.my';
document.domain = document.getElementById('present-info-content').getAttribute('data-document-domain');

const echoCancellation = true;
const noiseSuppression = false;
const autoGainControl = false;
//const jitsi_domain = 'edu2.smartsing.net';
//const jitsi_domain = 'smartsing.net.localhost:8443';
//const jitsi_domain = 'jitsi.smartsing.net.my';
let jitsi_domain = document.getElementById('present-info-content').getAttribute('data-jitsi-domain');

let currentSlide = 1;
let maxSlide = 12;
let api;

let jitsiAudioMuted = window.localStorage.getItem("jitsiAudioMuted");
jitsiAudioMuted = (jitsiAudioMuted == null) ? false : (parseInt(jitsiAudioMuted) == 1);
let jitsiVideoMuted = window.localStorage.getItem("jitsiVideoMuted");
jitsiVideoMuted = (jitsiVideoMuted == null) ? false : (parseInt(jitsiVideoMuted) == 1);

let countTriesChangeTitle = 0;
let changeTitle = function() {

    if (api && countTriesChangeTitle < 10) {
        countTriesChangeTitle++;
        try {

            //let t = api.getIFrame().contentWindow.document.getElementById("remoteVideos");
            //if ($(t).length > 0 && $(t).is(':visible')) {
            //    countTriesChangeTitle = 100; //остановит дальнейшие попытки сразу
            //    return void(0);
            //}

            let titles = api.getIFrame().contentWindow.document.getElementsByClassName("inlay__title");
            if ($(titles).length > 0) {
                for (let title of titles) {
                    title.innerHTML = 'AudioSmart HD запрашивает разрешение на использование камеры и микрофона.';
                }
                countTriesChangeTitle = 100; //остановит дальнейшие попытки сразу
                return void(0);
            }

        } catch (error) {
            console.log(`api.onload::Error (inlay__title): `, error.message);
        }
    }

    window.setTimeout(changeTitle, 500);

};

let options = {
    //roomName: roomUid,
    roomName: 'JitsiMeetAPIExample',
    userInfo: {
        displayName: 'UserName',
    },
    width: '100%',
    height: '100%',
    //width: window.innerWidth > window.innerHeight ? '50%' : "100%",
    //height: window.innerWidth > window.innerHeight ? '100%' : "50%",
    parentNode: document.querySelector('#jitsi'),
    configOverwrite: {
        disableDeepLinking: true,
        enableNoisyMicDetection: false,
        enableNoAudioDetection: false,
        disableAudioLevels: true,
        disableAP: false, // disables all 3 settings below
        disableAEC: !echoCancellation,
        disableNS: !noiseSuppression,
        disableAGC: !autoGainControl,
        disableHPF: false, // unknown
        stereo: true,
        p2p: {
            enabled: false
        },
        //startWithAudioMuted: false, //!!!!!!!!!!!!!!
        //startWithVideoMuted: false, //!!!!!!!!!!!!!!
        startWithAudioMuted: jitsiAudioMuted,
        startWithVideoMuted: jitsiVideoMuted,
        disableH264: true,
        enableLayerSuspension: true,

        /* мое */
        channelLastN: 1,
        enableClosePage: false,
        disableInviteFunctions: true,
        doNotStoreRoom: true
    },
    interfaceConfigOverwrite: {
        SHOW_JITSI_WATERMARK: false,
        DISABLE_FOCUS_INDICATOR: true,
        DISABLE_DOMINANT_SPEAKER_INDICATOR: true,
        DISABLE_VIDEO_BACKGROUND: true,
        SET_FILMSTRIP_ENABLED: false,
        DISABLE_JOIN_LEAVE_NOTIFICATIONS: true,
        //VERTICAL_FILMSTRIP: false,
        TOOLBAR_BUTTONS: [
            'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
            'fodeviceselection', 'hangup', 'chat',
            'etherpad', 'sharedvideo', 'settings',
            'videoquality', 'filmstrip', 'stats', 'shortcuts',
            'tileview', 'videobackgroundblur', 'mute-everyone',
        ]
    },
    onload: function() {
        console.info("api.onload::jitsi frame onload");
        //if (apiLoaded) return;
        try {
            api.getIFrame().contentWindow.APP.store.getState()["features/base/user-interaction"].interacted = true;
        } catch (error) {
            console.log(`api.onload::Error (jitsi frame onload): `, error.message);
        }

        /* принудительно включит микрофон при загрузке житси */
        //api.isAudioMuted().then(muted => {
        //    //if (muted) api.executeCommand('toggleAudio');
        //});

        /* смена титла запроса разрешения на доступ к камере и микрофону */
        changeTitle();

        /* удаление ватермарк жисти на видео */
        window.setTimeout(() => {
            try {
                let watermarks = api.getIFrame().contentWindow.document.getElementsByClassName("watermark");
                for (let watermark of watermarks) {
                    watermark.parentNode.removeChild(watermark);
                }
                //let filmStrip = api.getIFrame().contentWindow.document.getElementById("filmstripLocalVideo");
                let filmStrip = api.getIFrame().contentWindow.document.getElementById("filmstripRemoteVideos");
                $(filmStrip).hide();
                console.info("api.onload::watermarks found: watermarks.length=", watermarks.length);

            } catch (error) {
                console.log(`api.onload::Error (watermarks): `, error.message);
            }
        }, 0);

        /* repair size of video */
        window.setTimeout(() => {

            repairVideoSize();

        }, 2000);

        //if (isStudent) {
        //    if (typeof setupOwnAnalyserWithApi == 'function') {
        //        console.info('api.onload::Trying start function {setupOwnAnalyserWithApi}');
        //        window.setTimeout(setupOwnAnalyserWithApi, 0);
        //    }
        //}
    }
};

let $slides_menu_btn = $('#slides-menu-btn');
let $send_slides_menu = $('#send-slides-menu');
let $present_info_content = $('#present-info-content');

function repairVideoSize()
{
    return;
    if (isSafari()) {
        return;
    }
    if (api) {
        let $main_v = $('#main-container-video-slide');
        //let t = api.getIFrame().contentWindow.document.getElementById("new-toolbox");
        //$(t).find('.toolbox-content').first().css({ 'margin-bottom': '80px' });
        let main_width = $main_v.width();
        let main_height = $main_v.height();
        let v = api.getIFrame().contentWindow.document.getElementById("largeVideoWrapper");
        let v_width = parseInt($(v).css('width')); //$(v).width()
        let v_height = parseInt($(v).css('height')); //$(v).height()

        console.log('vv.style=', main_width, ' ; ', main_height);
        console.log('v.style=', v_width, ' ; ', v_height);

        if (v_width > main_width) {
            let height = v_height * main_width / v_width;
            let inset_h = (main_height - height) / 2;
            //v.style.cssText = ''; //'width: 368.4px; height: 321.8px; inset: 43.6px 0px; overflow: hidden;';
            $(v).css({
                width: main_width + 'px',
                height: height + 'px',
                'left': '0px',
                'top': inset_h + 'px',
                //inset: 'unset'
                inset: inset_h + 'px 0px'
                /* 'inset-inline': 'auto'/*, , overflow: 'hidden'*/
            });
        } else if (v_height > main_height) {
            let width = v_width * main_height / v_height;
            let inset_w = (main_width - width) / 2;
            $(v).css({
                width: width + 'px',
                height: main_height + 'px',
                'left': inset_w + 'px',
                'top': '0px',
                //inset: 'unset'
                inset: '0px ' + inset_w +  'px'
                /* 'inset-inline': 'auto'/*, , overflow: 'hidden'*/
            });
        }
    }
}

/**
 *
 */
function onResizePageSlides()
{
    repairVideoSize();
    _hwPlayer();

}

/**
 *
 * @param {int} num
 * @param {boolean} is_slave
 * @param {json} additional_data_for_slave
 */
function showSlide(num, is_slave, additional_data_for_slave)
{
    if (api) {

        if (num <= 1) {
            num = 1;
        }
        if (num >= maxSlide) {
            num = maxSlide;
        }
        currentSlide = num;

        $.ajax({
            type: 'get',
            url: '/user/get-slide',
            data: {
                num: num,
                is_slave: is_slave ? 1 : 0,
                is_test_student: isStudent ? 1 : 0
            },
            dataType: 'html'
        }).done(function (response) {

            $present_info_content.html(response);
            $present_info_content.removeClass();
            $present_info_content.addClass($present_info_content.data('main-class'));
            $present_info_content.addClass($present_info_content.data(`additional-class-${num}`));

            let $test = $present_info_content.find('.is-editable');
            //console.log('showSlide:ajax:done:: $test.length=', $test.length);
            if ($test.length) {
                $('.only-for-editable').show();
            } else {
                $('.only-for-editable').hide();
            }

            console.log(`showSlide:ajax:done::Slide num = ${num}`);

            if (is_slave && additional_data_for_slave) {
                let funct1 = 'slave_after_show_slide';
                if (typeof window[funct1] == 'function') {
                    window[funct1](additional_data_for_slave);
                }

                let funct2 = `slave_after_show_slide_${num}`;
                if (typeof window[funct2] == 'function') {
                    window[funct2](additional_data_for_slave);
                }

                if (isTeacherConnected) {
                    sendEndpointTextMessage(JSON.stringify({
                        slide_showed: 'ok',
                        slide_num: num
                    }));
                }
            }


            if (num == 2 /*&&isTeacher*/) {
                _hwPlayer();
            }

            if (num == 11) {
                _formats();
            }

            if (num == 12) {
                jQuery(function ($) {
                    jQuery('#result-form').yiiActiveForm([{"id":"users-user_level_general","name":"user_level_general","container":".field-user_level_general","input":"#user_level_general","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Необходимо заполнить «User Level General»."});yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Значение «User Level General» должно быть целым числом.","min":0,"tooSmall":"Значение «User Level General» должно быть не меньше 0.","max":11,"tooBig":"Значение «User Level General» не должно превышать 11.","skipOnEmpty":1});}},{"id":"users-user_level_general_notice","name":"user_level_general_notice","container":".field-user_level_general_notice","input":"#user_level_general_notice","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.string(value, messages, {"message":"Значение «User Level General Notice» должно быть строкой.","skipOnEmpty":1});}},{"id":"users-user_level_range","name":"user_level_range","container":".field-user_level_range","input":"#user_level_range","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Необходимо заполнить «User Level Range»."});yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Значение «User Level Range» должно быть целым числом.","min":0,"tooSmall":"Значение «User Level Range» должно быть не меньше 0.","max":9,"tooBig":"Значение «User Level Range» не должно превышать 9.","skipOnEmpty":1});}},{"id":"users-user_level_range_notice","name":"user_level_range_notice","container":".field-user_level_range_notice","input":"#user_level_range_notice","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.string(value, messages, {"message":"Значение «User Level Range Notice» должно быть строкой.","skipOnEmpty":1});}},{"id":"users-user_level_coordination","name":"user_level_coordination","container":".field-user_level_coordination","input":"#user_level_coordination","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Необходимо заполнить «User Level Coordination»."});yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Значение «User Level Coordination» должно быть целым числом.","min":0,"tooSmall":"Значение «User Level Coordination» должно быть не меньше 0.","max":9,"tooBig":"Значение «User Level Coordination» не должно превышать 9.","skipOnEmpty":1});}},{"id":"users-user_level_coordination_notice","name":"user_level_coordination_notice","container":".field-user_level_coordination_notice","input":"#user_level_coordination_notice","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.string(value, messages, {"message":"Значение «User Level Coordination Notice» должно быть строкой.","skipOnEmpty":1});}},{"id":"users-user_level_timbre","name":"user_level_timbre","container":".field-user_level_timbre","input":"#user_level_timbre","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Необходимо заполнить «User Level Timbre»."});yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Значение «User Level Timbre» должно быть целым числом.","min":0,"tooSmall":"Значение «User Level Timbre» должно быть не меньше 0.","max":9,"tooBig":"Значение «User Level Timbre» не должно превышать 9.","skipOnEmpty":1});}},{"id":"users-user_level_timbre_notice","name":"user_level_timbre_notice","container":".field-user_level_timbre_notice","input":"#user_level_timbre_notice","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.string(value, messages, {"message":"Значение «User Level Timbre Notice» должно быть строкой.","skipOnEmpty":1});}},{"id":"users-notes_played","name":"notes_played","container":".field-notes_played","input":"#notes_played","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Значение «Notes Played» должно быть целым числом.","min":0,"tooSmall":"Значение «Notes Played» должно быть не меньше 0.","skipOnEmpty":1});}},{"id":"users-notes_hit","name":"notes_hit","container":".field-notes_hit","input":"#notes_hit","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Значение «Notes Hit» должно быть целым числом.","min":0,"tooSmall":"Значение «Notes Hit» должно быть не меньше 0.","skipOnEmpty":1});}},{"id":"users-notes_close","name":"notes_close","container":".field-notes_close","input":"#notes_close","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Значение «Notes Close» должно быть целым числом.","min":0,"tooSmall":"Значение «Notes Close» должно быть не меньше 0.","skipOnEmpty":1});}},{"id":"users-notes_lowest","name":"notes_lowest","container":".field-notes_lowest","input":"#notes_lowest","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Значение «Notes Lowest» должно быть целым числом.","min":0,"tooSmall":"Значение «Notes Lowest» должно быть не меньше 0.","skipOnEmpty":1});}},{"id":"users-notes_highest","name":"notes_highest","container":".field-notes_highest","input":"#notes_highest","error":".help-block.help-block-error","validate":function (attribute, value, messages, deferred, $form) {yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Значение «Notes Highest» должно быть целым числом.","min":0,"tooSmall":"Значение «Notes Highest» должно быть не меньше 0.","skipOnEmpty":1});}}], []);
                });

                $('#notes_played').val(notesPlayed);
                $('#notes_hit').val(notesHit);
                $('#notes_close').val(notesClose);
                $('#notes_lowest').val(lowestNote);
                $('#notes_highest').val(highestNote);
                $('#room_hash').val($present_info_content.data('room-uuid'));
                $('#student_user_id').val($present_info_content.data('student-user-id'));
            }

            initToolTip();

        });

    }
}

/**
 * @returns {boolean}
 */
function check_is_ok_slide_2()
{
    if ($('#presets_var').val() == '') {
        flash_msg('Слайд не отправлен. Нужно выбрать пресет перед отправкой', 'error', FLASH_TIMEOUT);
        $send_slides_menu.trigger('click');
        return false;
    }
    return true;
}

/**
 *
 * @returns {{}}
 */
function prepare_additional_data_for_slide()
{
    let additional_data = {};
    let inp_obj = {};
    let html_obj = {};
    $present_info_content.find('.data-inputs').each(function () {
        let $el = $(this);
        inp_obj[$el.attr('id')] = $el.val();
        if ($(this).hasClass('data-select')) {
            inp_obj[$el.attr('id') + '_text'] = $(this).find('option:selected').first().text();
        }
    });
    additional_data.inp_obj = inp_obj;
    //console.log('prepare_additional_data_for_slide:: additional_data=', additional_data);
    $present_info_content.find('.data-html').each(function () {
        let $el = $(this);
        //console.log($el.html());
        //console.log($el.attr('id'));
        html_obj[$el.attr('id')] = $el.html();
        //[$el.attr('id')]
    });
    additional_data.html_obj = html_obj;
    console.log('prepare_additional_data_for_slide:: additional_data=', additional_data);
    return additional_data;
}

/**
 *
 * @param additional_data
 */
function slave_after_show_slide(additional_data)
{
    console.log('slave_after_show_slide:: additional_data=', additional_data);
    if ("inp_obj" in additional_data) {
        let inp_obj = additional_data.inp_obj;
        for (let item in inp_obj) {
            //console.log(item);
            //console.log(inp_obj[item]);
            $('.' + item).html(inp_obj[item]);
        }
    }

    if ("html_obj" in additional_data) {
        let html_obj = additional_data.html_obj;
        for (let item in html_obj) {
            //console.log(item);
            //console.log(html_obj[item]);
            $('#' + item).html(html_obj[item]);
        }
    }
}

/**
 * @param {int} slide_num
 */
function master_after_show_slide_for_slave(slide_num)
{
    if (slide_num == 2) {
        flash_msg(
            'Ожидаем пока ученик выполнит калибровку микрофона.',
            'danger',
            0,
            true,
            null,
            null,
            'calibrate-wait-flash'
        );

        $('#system-action').show().html('Ученик получил слайд. Кнопки управления плеером станут доступны, как только ученик выполнит калибровку.');
    }
}

/**
 * @param additional_data
 */
function slave_after_show_slide_2(additional_data)
{
    isPlayOneForCalibrate = false;

    //$osmd_volume.val(volumeAmplifier);
    flash_msg(
        'В процессе упражнений при появлении любых неприятных ощущений просьба незамедлительно сообщать преподавателю. Безопасность Вашего голоса является приоритетом номер 1 для нас',
        'danger',
        0);
    if ("inp_obj" in additional_data) {
        if ("presets_var" in additional_data.inp_obj && "presets_var_text" in additional_data.inp_obj) {
            $('.preset-name').html(additional_data.inp_obj.presets_var_text);
            setTimeout(function() {
                initAudioContexts();
                loadMusicXml(additional_data.inp_obj.presets_var);
                if ("count_notes_to_play" in additional_data.inp_obj) {
                    setTimeout(function() {
                        setSlaveCountNotesToPlay(additional_data.inp_obj.count_notes_to_play);
                    }, 500);

                    //if (!isCalibrated || !studentIsCalibrated) {
                        flash_msg(
                            `Преподаватель загрузил новый пресет "${additional_data.inp_obj.presets_var_text}" для вашего урока`,
                            'success',
                            FLASH_TIMEOUT
                        );
                        flash_msg(
                            'Пожалуйста, выполните калибровку вашего микрофона, для этого нажмите на кнопку "Калибровка"',
                            'danger',
                            0,
                            true,
                            null,
                            null,
                            'calibrate-flash'
                        );
                    //} else {
                        //$('#player-buttons-div').removeClass('hidden');
                    //}
                }
            }, 500);
        }
    }
}

/**
 * @param {string} json
 */
function sendEndpointTextMessage(json)
{
    console.info("sendEndpointTextMessage::sendEndpointTextMessage() is started");
    if (api) {
        try {
            api.executeCommand('sendEndpointTextMessage', '', json);
        } catch (error) {
            console.log(`send Command error: ${error}`);
        }
    }
    console.info("sendEndpointTextMessage::sendEndpointTextMessage() is finished");
}

/**
 *
 */
function noScrollFmWindow() {
    window.scrollTo(0, 0);
}
//window.addEventListener('scroll', noScrollFmWindow);


/******
 ******
 *****/
$(document).ready(function() {

    /**/
    let slidesTimeoutOnResize;
    $(window).on('resize', function(e) {
        slidesTimeoutOnResize = setTimeout(function() {

            onResizePageSlides();

        }, 400);
    });

    /**/
    $('.save-slide-data').hide();
    //$('.slide-manage-controls').hide();

    /**/
    isStudent = (parseInt($present_info_content.data('is-student')) == 1);
    isTeacher = !isStudent;

    /** API INITIALIZE */
    options.roomName = $present_info_content.data('room-uuid');
    options.userInfo.displayName = $present_info_content.data('user-name');
    api = new JitsiMeetExternalAPI(jitsi_domain, options);
    api.on('endpointTextMessageReceived', data => {
        console.info("api.endpointTextMessageReceived:: data=", data);
        let parsed = JSON.parse(data.data.eventData.text);

        /**/
        if (isStudent) {
            if ("num" in parsed && "additional_data" in parsed) {
                let num = parseInt(parsed.num);
                let additional_data = parsed.additional_data;

                console.log(`api.endpointTextMessageReceived::Received command for change slide. New slide num = ${num}`);
                showSlide(num, true, additional_data);
            }

            if ("playerCommand" in parsed) {
                if (typeof window[parsed.playerCommand] == 'function') {
                    if ("additional_data" in parsed && parsed.additional_data) {
                        console.log(`api.endpointTextMessageReceived::Received playerCommand ${parsed.playerCommand}(${parsed.additional_data})`);
                        window[parsed.playerCommand](parsed.additional_data);
                    } else {
                        console.log(`api.endpointTextMessageReceived::Received playerCommand ${parsed.playerCommand}()`);
                        window[parsed.playerCommand]();
                    }
                }
            }

            if ("redirectTo" in parsed) {
                console.info('api.endpointTextMessageReceived::redirectTo=', parsed.redirectTo);
                window.location.href = parsed.redirectTo;
            }
        }

        /**/
        if (isTeacher) {
            if (("slide_num" in parsed) && ("slide_showed" in parsed)) {
                let num = parseInt(parsed.slide_num);
                $(`.control-menu__item_${num}`).addClass('_passed');
                flash_msg('Слайд отправлен и успешно показан у ученика.', 'success', FLASH_TIMEOUT);
                $send_slides_menu.trigger('click');

                master_after_show_slide_for_slave(num);
            }

            if ("playerCommand" in parsed) {
                //if (typeof window[parsed.playerCommand] == 'function') {
                if ("additional_data" in parsed /*&& parsed.additional_data*/) {
                    console.log(`api.endpointTextMessageReceived::Received playerCommand for Teacher ${parsed.playerCommand}(${parsed.additional_data})`);
                    casePlayerCommandForTeacher(parsed.playerCommand, parsed.additional_data);
                } else {
                    console.log(`api.endpointTextMessageReceived::Received playerCommand ${parsed.playerCommand}()`);
                    casePlayerCommandForTeacher(parsed.playerCommand, null);
                }
                //}
            }

            if ('studentExecuteCalibrate' in parsed) {
                if (parsed.studentExecuteCalibrate) {
                    $('#player-buttons-div').removeClass('hidden');
                    $(document).find('.calibrate-wait-flash').first().remove();
                    flash_msg('Ученик выполнил калибровку устройства. Теперь вы можете приступать к занятию.', 'success', FLASH_TIMEOUT);
                    $('#system-action').show().html('Теперь вы можете приступать к занятию.');
                } else {
                    $('#system-action').show().html('<span class="red">Калибровка устройства ученика не удалась. Дальнейшая работа с плеером невозможна до устранения этой проблемы.</span>');
                    $('#player-buttons-div').addClass('hidden');
                    $(`.control-menu__item_${currentSlide}`).removeClass('_passed');
                }
            }
        }

    });
    api.on('audioMuteStatusChanged', data => window.setTimeout(() => {
        ownMuted = data.muted;
        window.localStorage.setItem("jitsiAudioMuted", data.muted ? "1" : "0");
        console.log('api.audioMuteStatusChanged::data.muted=', data.muted);
    }, 0));
    api.on('videoMuteStatusChanged', data => window.setTimeout(() => {
        ownMuted = data.muted;
        window.localStorage.setItem("jitsiVideoMuted", data.muted ? "1" : "0");
        console.log('api.jitsiVideoMuted::data.muted=', data.muted);
    }, 0));
    api.on('audioAvailabilityChanged', data => {
        console.info("api.audioAvailabilityChanged:: data.available=", data.available);
    });
    api.on('micError', data => {
        console.info("api.micError:: type=", data.type, " message=", data.message);
    });
    api.on('deviceListChanged', data => {
        console.info("api.deviceListChanged:: data=", data);
    });
    api.on('videoConferenceJoined', data => window.setTimeout(() => {
        console.info("api.videoConferenceJoined::data=", data);
        let { id, displayName } = data;
        api.isVideoMuted().then(muted => {
            // принудительно включит камеру, если она была muted
            //if (muted) api.executeCommand('toggleVideo');
        });
    }, 0));
    api.on('videoConferenceLeft', data => window.setTimeout(() => {
        console.info("api.videoConferenceLeft::data=", data);
        //hidePanel();
        if (isTeacher) {
            flash_msg('Ученик отключился.', 'danger', FLASH_TIMEOUT);
            isStudentConnected = false;

            $('#player-buttons-div').addClass('hidden');
            $system_action.show().html('<span class="red">Ученик отключился.</span>');
            $user_action.html('').hide();
            stopPlayer(true);
            $(`.control-menu__item_${currentSlide}`).removeClass('_passed');
        }
    }, 0));
    api.on('participantJoined', data => {
        console.info("api.participantJoined:: data=", data);
        window.setTimeout(() => {
            try {
                let participants = api.getIFrame().contentWindow.APP.conference.listMembersIds();
                if (participants.length >= 1) {
                    if (isStudent) {
                        isTeacherConnected = true;
                        flash_msg('Преподаватель подключился.', 'success', FLASH_TIMEOUT);
                    } else {
                        flash_msg('Ученик подключился.', 'success', FLASH_TIMEOUT);
                        isStudentConnected = true;
                    }
                    //if (isTeacher) {
                    //    startMediaRecorder();
                    //}
                }
                //if (osmdLoaded && audioPlayer != null) {
                //    osmdApBegin();
                //}
                //if (isStudent && osmdLoaded) {
                //    sendCommand("studentOsmdAndApLoaded true");
                //}
                //updatePanelControlButton();
            } catch (error) {
                console.log(`api.participantJoined::Error: `, error.message);
            }
        }, 5000); //12000
    });
    api.on('participantLeft', data => {
        console.info("api.participantLeft:: data=", data);
        try {
            console.log(api.getIFrame().src);
            let participants = api.getIFrame().contentWindow.APP.conference.listMembersIds();
            if (participants.length < 1) {
                if (isStudent) {
                    flash_msg('Преподаватель отключился.', 'danger', FLASH_TIMEOUT);
                    isTeacherConnected = false;
                } else {
                    flash_msg('Ученик отключился.', 'danger', FLASH_TIMEOUT);
                    isStudentConnected = false;

                    $('#player-buttons-div').addClass('hidden');
                    $system_action.show().html('<span class="red">Ученик отключился.</span>');
                    $user_action.html('').hide();
                    stopPlayer(true);
                    $(`.control-menu__item_${currentSlide}`).removeClass('_passed');
                }
                //if (isTeacher) {
                //    stopMediaRecorder();
                //}
            }
            //if (panelIsHidden) {
            //    controlPanel();
            //}
            //updatePanelControlButton();
        } catch (error) {
            console.log(`api.participantLeft::Error: `, error.message);
        }
    });
    api.on('readyToClose', () => {
        console.info("api.readyToClose::");
        console.log('iframe.src=', api.getIFrame().src);
        api.getIFrame().src = '/show-logo?empty-layout=1';
        api = null;
        //if (!panelIsHidden) {
        //    controlPanel();
        //}
        //if (isTeacher) {
        //    stopMediaRecorder();
        //}
        //$('#panel-control').style.visibility = "hidden";
        //$('#start').innerText = "START";
        //$('#start').onclick = start;
        //$('#start').style.visibility = "visible";
        //$('#jitsi').removeChild($('#jitsi').childNodes[0]);
        //api = null;
        //isApLoading = false;
        //audioPlayerLoaded = false;
        //osmdLoaded = false;
    });


    /**/
    let $test = $present_info_content.find('.is-editable');
    //console.log('showSlide:ajax:done:: $test.length=', $test.length);
    if ($test.length) {
        $('.only-for-editable').show();
    } else {
        $('.only-for-editable').hide();
    }
    //currentSlide = 12;
    //showSlide(currentSlide, false, null);

    /**/
    $(document).on('click touchstart', '.show-slide', function() {
        if ($('.save-slide-data').is(':visible')) {
            $('.save-slide-data').trigger('click');
            flash_msg('Изменения в слайде сохранены, но он не был отправлен ученику и вы переключились на другой слайд.', 'danger', FLASH_TIMEOUT);
        }
        currentSlide = parseInt($(this).data('slide-num'));
        showSlide(currentSlide, false, null);
        if ($slides_menu_btn.hasClass('_active')) {
            $slides_menu_btn.trigger('click');
        }
    });

    /**/
    $(document).on('click touchstart', '.context-menu__link, #send-slides-menu', function () {
        if ($slides_menu_btn.hasClass('_active')) {
            $slides_menu_btn.trigger('click');
        }
    });

    /**/
    $(document).on('click  touchstart', '.edit-slide-data', function() {
        flash_msg('Слайд открыт для редактирования.<br/>После редактирования нажмите кнопку "Зафикисировать" и потом можно отправить слайд ученику, нажав кнопку "Отправить"', 'danger', FLASH_TIMEOUT);
        $('.slide-manage-info').hide();
        $('.slide-manage-controls').show();
        $('.data-inputs').removeClass('hidden');
        $('.save-slide-data').show();
        $('.edit-slide-data').hide();
        $('.send-slide-to-student').hide();
        $(`.control-menu__item_${currentSlide}`).removeClass('_passed');
    });

    /**/
    $(document).on('click touchstart', '.save-slide-data', function() {
        $('.slide-manage-info').show();
        $('.slide-manage-controls').hide();
        $('.data-inputs').addClass('hidden');
        $('.save-slide-data').hide();
        $('.edit-slide-data').show();
        $('.send-slide-to-student').show();
        $(`.control-menu__item_${currentSlide}`).removeClass('_passed');

        $present_info_content.find('.data-inputs').each(function () {
            let $el = $(this);
            $('.' + $el.attr('id')).html($el.val());
        });

        flash_msg('Изменения зафикисрованы.<br/>Не забудьте отправить слайд ученику, нажав кнопку "Отправить"', 'danger', FLASH_TIMEOUT);
    });

    $(document).on('click touchstart', '.send-slide-to-student', function() {
        if (!$('.slide-manage-controls').is(':visible')) {
            let additional_data = {};
            console.log('on.click .send-slide-to-student:: currentSlide=', currentSlide);

            let $control_menu_item_currentSlide = $(`.control-menu__item_${currentSlide}`);

            if (!isStudentConnected) {
                flash_msg('Слайд не отправлен. Ученик еще не подключился к занятию', 'error', FLASH_TIMEOUT);
                $send_slides_menu.trigger('click');
                return false;
            }

            if ($control_menu_item_currentSlide.hasClass('_passed')) {
                let $test = $present_info_content.find('.is-editable, .is-osmd');
                if ($test.length) {
                    if (!confirm('Слайд уже был отправлен, точно повторить отправку?')) {
                        flash_msg('Этот слайд уже был отправлен ученику. После этого в нем ничего не менялось.', 'error', FLASH_TIMEOUT);
                        //$send_slides_menu.trigger('click');
                        return false;
                    }
                }
            }

            $control_menu_item_currentSlide.removeClass('_passed');

            let funct1 = `check_is_ok_slide_${currentSlide}`;
            if (typeof window[funct1] == 'function') {
                let check_is_ok = window[funct1]();
                if (!check_is_ok) {
                    return false;
                }
            }
            let funct2 = 'prepare_additional_data_for_slide';
            if (typeof window[funct2] == 'function') {
                additional_data = window[funct2]();
            }
            //console.log('on.click .send-slide-to-student:: additional_data=', additional_data);

            /**/
            sendEndpointTextMessage(JSON.stringify({
                num: currentSlide,
                additional_data: additional_data
            }));

        }
    });

    /**/
    $(document).on('click touchstart', '.js-set-keys-piano', function() {
        $('.js-set-keys-piano').removeClass('active');
        $(this).addClass('active');
    });

    /**/
    $(document).on('click touchstart', '.keys__item', function() {
        if ($('.slide-manage-controls').is(':visible')) {
            let $active_class = $(document).find('.js-set-keys-piano.active').first();
            $(this).removeClass('keys__item--s3 keys__item--s2 keys__item--s1 keys__item--s0');
            $(this).addClass($active_class.data('class'));
        }
    });

    /**/
    $(document).on('click touchstart', '.chart__label, .chart__col', function() {
        if (isTeacher) {
            let $this = $(this);
            let level = $this.data('level');
            $('.chart__label').removeClass('_filled');
            $('.chart__col').removeClass('_filled');
            let $lbl = $(`.label_level-${level}`);
            $lbl.addClass('_filled');
            $('#level-text').html($lbl.children().first().html());
            setTimeout(function () {
                for (let i = 1; i <= level; i++) {
                    $(`.col-level-${i}`).addClass('_filled');
                }
            }, 300);
        } else {
            return false;
        }
    });

    /**/
    $(document).on('change', '#presets_var', function() {
        let $loading_preset = $('#loading-preset');
        $loading_preset.show();
        $(`.control-menu__item_${currentSlide}`).removeClass('_passed');
        initAudioContexts();
        $('.preset-name').html($(this).find('option:selected').first().text());

        loadMusicXml($(this).val());

        if (isTeacher) {
            flash_msg('Внимание, после выбора пресета, нужно выполнить отправку слайда ученику.<br />Не забудьте нажать кнопку "Отправить".', 'error', FLASH_TIMEOUT);
        }
    });

    /**/
    //document.body.querySelector('.js-open-params').addEventListener('touchstart', function (e) {
    $(document).on('touchstart', '.open-params-btn', function(e) {

        _showHideVolumeParam(e.target);


        //e.preventDefault();
        //e.stopPropagation();
        //e.stopImmediatePropagation();
    });


    /**/
    $(document).on('click', '.send-student-result', function() {
        let $form = $('#result-form');

        $form.yiiActiveForm('data').submitting = true;
        $form.yiiActiveForm('validate');
        //$form.yiiActiveForm;

        window.setTimeout(function() {
            //$form.yiiActiveForm('validate');
            if ($form.find('.has-error').length) {
                flash_msg('Форма не заполнена до конца или имеются ошибки в полях', 'error', FLASH_TIMEOUT);
                return false;
            }

            let inp_obj = {};
            $present_info_content.find('.data-inputs').each(function () {
                let $el = $(this);
                inp_obj[$el.attr('id')] = $el.val();
                if ($(this).hasClass('data-select')) {
                    inp_obj[$el.attr('id') + '_text'] = $(this).find('option:selected').first().text();
                }
            });

            console.log('send-student-result::inp_obj=', inp_obj);
            $.ajax({
                type: 'post',
                url: '/methodist/save-student-result',
                data: inp_obj,
                dataType: 'json'
            }).done(function (response) {

                if ("status" in response && response.status) {

                    if (!isStudentConnected) {
                        flash_msg('Url перенаправления на страницу after-trial не отправлен. Ученик уже отключился.', 'error', FLASH_TIMEOUT);
                        return false;
                    }

                    /**/
                    sendEndpointTextMessage(JSON.stringify({
                        redirectTo: response.data.url
                    }));

                } else {
                    console.log(response);
                    prettyAlert('An internal server error occurred.');
                }

            });

        }, 1000);

    });

    //$(document).on('submit', '#result-form', function() {
    //    prettyAlert('OK');
    //    return false;
    //});

    //$(document).on('beforeSubmit', '#result-form', function() {
    //    let $frm = $(this);
    //
    //    if ($frm.find('.has-error').length) {
    //        flash_msg('Форма не заполнена до конца или имеются ошибки в полях', 'error', FLASH_TIMEOUT);
    //        return false;
    //    }
    //})

});