/**
 * echo "snd-aloop" >> /etc/modules
 * modprobe snd-aloop
 * lsmod | grep snd_aloop
 *
 * свои сертификаты для житси
 * https://community.jitsi.org/t/using-own-ssl-certificate-docker-installation/64007
 *
 * житси один из релизов в виде докер-сборки:
 * https://github.com/jitsi/docker-jitsi-meet/releases
 *
 * команды для удаления или установки контейнера:
 * https://linux-notes.org/ostanovit-udalit-vse-docker-kontejnery/
 * удалит все контейнеры:
 * docker rm $(docker ps -a -q) --force
 * остановид все контейнеры:
 * docker stop $(docker ps -a -q)
 * запустить все остановленные контейнеры
 * docker start $(docker ps -a -q -f status=exited)
 *
 * перед новой сборкой выполнить:
 * Если кратко, то ответом на поставленный вопрос может служить данный скрипт:
 * #!/bin/bash
 * # Stop all containers
 * docker stop $(docker ps -a -q)
 * # Delete all containers
 * docker rm $(docker ps -a -q)
 * # Delete all images
 * docker rmi $(docker images -q)
 *
 * релиз который работал на момент работы смартсинга
 * docker-jitsi-meet-stable-5142
 *
 * один из вариантов сборки:
 * docker-compose -f docker-compose.yml up -d
 * docker-compose -f docker-compose.yml -f jibri.yml up -d
 * docker-compose -f docker-compose.yml -f jigasi.yml -f jibri.yml up -d
 * docker-compose -f docker-compose.yml -f jibri.yml up --scale jibri=4 -d
 *
 * войти внутрь контейнера докера:
 * docker exec -it dockerjitsimeetstable51424_web_1 bash
 *
 * установка мс в докере, если его нет
 * echo 'deb http://ru.archive.ubuntu.com/ubuntu/ bionic universe'| tee -a /etc/apt/sources.list
 * sudo apt update
 * sudo apt install mc
 *
 * в папке сборки выполнив эту команду остановим все контейнеры
 * docker-compose -f docker-compose.yml -f jibri.yml stop
 *
 * в папке сборки выполнив эту команду удалим все контейнеры
 * docker-compose -f docker-compose.yml -f jibri.yml down
 *
 * покажет все даже стопнутые (обратить внимание на статус exit)
 * docker ps -a
 *
 * просмотр логов по контейнеру
 * docker logs dockerjitsimeetstable53903_jibri_1
 *
 * настройка
 * https://learn.javascript.ru/same-origin-policy
 * что бы заработал доступ к ифрейму жисти, нужно:
 * 1. установить его на то же домене что и сайт но только установить на порту 8443
 * 2. сделать на нгинкс, который работает с нашим сайтом прокси конфиг, который будет проксировать жисти с 8443 порта на поддомен  jitsi.нашдоменсайта
 * 3. в яваскрипте жисти который крутится в докере добавить строку document.domain = 'нашдоменсайта'
 * 4. в этом яваскрипте написать такой же домен document.domain = 'нашдоменсайта'
 * sudo docker exec -it docker-jitsi-meet_web_1 bash
 * docker exec -it docker-jitsi-meet_web_1 bash
 * и внутри
 * document_root: /usr/share/jitsi-meet;    (/home/jitsi-meet-cfg/webroot/)
 * правим файл index.html в этом каталоге
 * находим самый верхний тег <script> и перед ним вставляем
 * <script> document.domain = 'smartsing.net.my'; </script>
 *
 * исправление - размещаем этот код как можно ниже в конец документа а не в его начале но до тега закрытия </body>
    <script> document.domain = '1xsmart.com.my'; </script>
    <style>
        .large-video-labels .circular-label.file {
            display: none !important;
        }
        .tile-view #remoteConnectionMessage, .tile-view .watermark {
            display: none !important;
        }
        .watermark {
            display: none !important;
        }
        #rec-container {
            display: none !important;
        }
        #notifications-container {
            *display: none !important;
        }
        [data-testid="dialog.recording" i] {
            display: none !important;
        }
        [data-testid="recording.failedToStart" i] {
            display: none !important;
        }
    </style>

 * в папке докера /usr/share/jitsi-meet/sounds переименовать или удалить два файла
 * recordingOff.mp3 => recordingOff.mp3-off
 * recordingOn.mp3  => recordingOn.mp3-off
 *
 * в папке докера /usr/share/jitsi-meet/images переименовать или удалить файл
 * watermark.png => watermark.png-off
 *
 */

document.domain = document.getElementById('present-info-content').getAttribute('data-document-domain');
const echoCancellation = true;
const noiseSuppression = false;
const autoGainControl = false;
//const jitsi_domain = 'edu2.smartsing.net';
//const jitsi_domain = 'smartsing.net.localhost:8443';
//const jitsi_domain = 'smartsing.net.my:8443';
let jitsi_domain = document.getElementById('present-info-content').getAttribute('data-jitsi-domain');

let currentSlide = 1;
let maxSlide = 12;
let api;
let last_slide_is_done = false;
let unload_text = 'Attention!!! You did not complete the final slide with student results. It is your responsibility as a Methodist.';
let is_class_room = true;

let jitsiAudioMuted = window.localStorage.getItem("jitsiAudioMuted");
jitsiAudioMuted = (jitsiAudioMuted == null) ? false : (parseInt(jitsiAudioMuted) == 1);
let jitsiVideoMuted = window.localStorage.getItem("jitsiVideoMuted");
jitsiVideoMuted = (jitsiVideoMuted == null) ? false : (parseInt(jitsiVideoMuted) == 1);
let jitsi_recording = false;

let countTriesChangeTitle = 0;
let changeTitle = function() {

    if (api && countTriesChangeTitle < 10) {
        countTriesChangeTitle++;
        try {

            //let t = JITSI_IFRAME.contentWindow.document.getElementById("remoteVideos");
            //if ($(t).length > 0 && $(t).is(':visible')) {
            //    countTriesChangeTitle = 100; //остановит дальнейшие попытки сразу
            //    return void(0);
            //}

            let titles = JITSI_IFRAME.contentWindow.document.getElementsByClassName("inlay__title");
            if ($(titles).length > 0) {
                for (let title of titles) {
                    title.innerHTML = 'AudioSmart HD requests permission to use camera and microphone.';
                }
                countTriesChangeTitle = 100; //остановит дальнейшие попытки сразу
                return void(0);
            }

        } catch (error) {
            console.log(`changeTitle::Error (inlay__title): `, error.message);
        }
    }

    window.setTimeout(changeTitle, 500);

};

let JITSI_IFRAME = null;
let recording = (USER_TYPE == USER_TYPES['TYPE_STUDENT']) ? 'recording-no' : 'recording-no';
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
        disableDeepLinking: true, // не показывать предложение об установке моб приложения
        //enableNoisyMicDetection: false,
        //enableNoAudioDetection: false,
        //disableAudioLevels: true,
        //disableAP: false, // disables all 3 settings below
        //disableAEC: !echoCancellation,
        //disableNS: !noiseSuppression,
        //disableAGC: !autoGainControl,
        //disableHPF: false, // unknown
        //stereo: true,
        //p2p: {
        //    enabled: false
        //},
        startWithAudioMuted: false, //!!!!!!!!!!!!!!
        startWithVideoMuted: false, //!!!!!!!!!!!!!!
        //startWithAudioMuted: jitsiAudioMuted,
        //startWithVideoMuted: jitsiVideoMuted,
        //disableH264: true,
        //enableLayerSuspension: true,

        /* мое */
        //channelLastN: 1,
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
            'tileview', 'videobackgroundblur', 'mute-everyone', recording
        ]
    },
    onload: function() {
        JITSI_IFRAME = api.getIFrame();
        console.log(`JITSI_IFRAME = ${JITSI_IFRAME.src}`);
        console.info("api.onload::jitsi frame onload");
        //if (apiLoaded) return;
        try {
            JITSI_IFRAME.contentWindow.APP.store.getState()["features/base/user-interaction"].interacted = true;
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
                let watermarks = JITSI_IFRAME.contentWindow.document.getElementsByClassName("watermark");
                console.info("api.onload::watermarks found: watermarks.length=", watermarks.length);
                for (let watermark of watermarks) {
                    //watermark.parentNode.removeChild(watermark);
                    //$(watermark).remove();
                }
                //let filmStrip = JITSI_IFRAME.contentWindow.document.getElementById("filmstripLocalVideo");

                /* убирает странный квадрат, но тогда и плитка не работает */
                //let filmStrip = JITSI_IFRAME.contentWindow.document.getElementById("filmstripRemoteVideos");
                //$(filmStrip).hide();

            } catch (error) {
                console.log(`api.onload::Error (watermarks): `, error.message);
            }
        }, 100);

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

let SlidesWebSocket;

/**
 *
 */
function repairVideoSize()
{
    return;
    if (isSafari()) {
        return;
    }
    if (api) {
        let $main_v = $('#main-container-video-slide');
        //let t = JITSI_IFRAME.contentWindow.document.getElementById("new-toolbox");
        //$(t).find('.toolbox-content').first().css({ 'margin-bottom': '80px' });
        let main_width = $main_v.width();
        let main_height = $main_v.height();
        let v = JITSI_IFRAME.contentWindow.document.getElementById("largeVideoWrapper");
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
    //_hwPlayer();

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
 * @param {string} json
 */
function sendEndpointTextMessage________by_jitsi_api(json) //off
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
 * @param {string} json
 */
function sendEndpointTextMessage(json)
{
    console.info("sendEndpointTextMessage::sendEndpointTextMessage() is started");
    if (SlidesWebSocket) {
        try {

            SlidesWebSocket.send(json);

        } catch (error) {
            console.log(`send Command error: ${error}`);
        }
    }
    console.info("sendEndpointTextMessage::sendEndpointTextMessage() is finished");
}

/**
 *
 */
function noScrollFmWindow()
{
    window.scrollTo(0, 0);
}
//window.addEventListener('scroll', noScrollFmWindow);


/******
 ******
 *****/
$(document).ready(function() {

    is_class_room = (parseInt($present_info_content.data('is-class-room')) == 1);
    jitsi_recording = (parseInt($present_info_content.data('jitsi-recording')) == 1);

    /**/
    let ChatWebSocket;
    ChatWebSocket = new Ws($present_info_content.data('chat-wss-url'), 5);
    ChatWebSocket.onopen = function() {
        console.log('Connected to ' + this._url);
    };
    ChatWebSocket.onmessage = function (message) {};
    ChatWebSocket.send(JSON.stringify(
        {
            chat: {
                user_id: parseInt($present_info_content.data('student-user-id'))
            }
        }
    ));
    ChatWebSocket.send(JSON.stringify(
        {
            chat: {
                user_id: parseInt($present_info_content.data('teacher-user-id'))
            }
        }
    ));

    /**/
    $(document).on('click', '#js-change-iframe-src', function() {
        $('#slide2-iframe-src')[0].src = $('#url-for-iframe-src').val();
    });

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
        return void(0);
    });
    api.on('audioMuteStatusChanged', data => window.setTimeout(() => {
        let ownMuted = data.muted;
        window.localStorage.setItem("jitsiAudioMuted", data.muted ? "1" : "0");
        if (ownMuted) {
            flash_msg($translate_text_messages.attr('data-msg-4'), 'error', 0);
        } else {
            flash_msg($translate_text_messages.attr('data-msg-4'), 'error', -1);
        }
        sendEndpointTextMessage(JSON.stringify({
            device_status: 'changed',
            additional_data: {
                device: 'audio',
                muted: ownMuted
            }
        }));
        console.log('api.audioMuteStatusChanged::data.muted=', data.muted);
    }, 100));
    api.on('videoMuteStatusChanged', data => window.setTimeout(() => {
        let ownMuted = data.muted;
        window.localStorage.setItem("jitsiVideoMuted", data.muted ? "1" : "0");
        if (ownMuted) {
            flash_msg($translate_text_messages.attr('data-msg-5'), 'error', 0);
        } else {
            flash_msg($translate_text_messages.attr('data-msg-5'), 'error', -1);
        }
        sendEndpointTextMessage(JSON.stringify({
            device_status: 'changed',
            additional_data: {
                device: 'video',
                muted: ownMuted
            }
        }));
        console.log('api.jitsiVideoMuted::data.muted=', data.muted);
    }, 100));
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
        api.executeCommand('stopRecording', 'file');
        setTimeout(function () {
            try {
                let participants = JITSI_IFRAME.contentWindow.APP.conference.listMembersIds();
                console.log(`participants=${participants}`);
                //alert(participants.length);
                if (participants.length == 0) {
                    //alert(1);
                    //api.executeCommand('stopRecording', 'file');
                    if (jitsi_recording) {
                        api.executeCommand('startRecording', {
                            mode: 'file'
                            //dropboxToken: 'sl.AteCfeCd6z8QyZVsdPfbtm7R6mMIqI8WYoc16iKJCc3XYS5Cam4a6mSCFXdD8BK9K9QMB8CiHW5PcHxn9aGXk1rnE9Pyz1OxOX3aRn_JxnCxtbrOwKHqrGfKOeGV8Bi6SRkSCGi_j8s'
                        });
                    }
                }
            } catch (error) {
                console.log(`api.videoConferenceJoined::Error: `, error.message);
            }
        }, 5000);
    }, 0));
    api.on('videoConferenceLeft', data => window.setTimeout(() => {
        //api.executeCommand('stopRecording', 'file');
        console.info("api.videoConferenceLeft::data=", data);
        //hidePanel();
        if (isTeacher) {
            flash_msg($translate_text_messages.attr('data-msg-6'), 'danger', FLASH_TIMEOUT);
            isStudentConnected = false;

            $('#player-buttons-div').addClass('hidden');
            $system_action.show().html('<span class="red">Ученик отключился.</span>');
            $user_action.html('').hide();
            stopPlayer(true);
            $(`.control-menu__item_${currentSlide}`).removeClass('_passed');
        }
        try {
            let participants = JITSI_IFRAME.contentWindow.APP.conference.listMembersIds();
            if (participants.length == 0) {
                api.executeCommand('stopRecording', 'file');
            }
        } catch (error) {
            console.log(`api.videoConferenceLeft::Error: `, error.message);
        }
    }, 0));
    api.on('participantJoined', data => {
        console.info("api.participantJoined:: data=", data);
        window.setTimeout(() => {
            try {
                //let participants = JITSI_IFRAME.contentWindow.APP.conference.listMembersIds();
                //if (participants.length >= 1) {
                    if (isStudent) {
                        isTeacherConnected = true;
                        flash_msg($translate_text_messages.attr('data-msg-7'), 'success', FLASH_TIMEOUT);
                    } else {
                        flash_msg($translate_text_messages.attr('data-msg-8'), 'success', FLASH_TIMEOUT);
                        isStudentConnected = true;
                    }
                    //if (isTeacher) {
                    //    startMediaRecorder();
                    //}
                //}
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
            //let participants = JITSI_IFRAME.contentWindow.APP.conference.listMembersIds();
            //if (participants.length <= 1) {
                if (isStudent) {
                    flash_msg($translate_text_messages.attr('data-msg-9'), 'danger', FLASH_TIMEOUT);
                    isTeacherConnected = false;
                } else {
                    flash_msg($translate_text_messages.attr('data-msg-6'), 'danger', FLASH_TIMEOUT);
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
            //}
            //if (panelIsHidden) {
            //    controlPanel();
            //}
            //updatePanelControlButton();
        } catch (error) {
            console.log(`api.participantLeft::Error: `, error.message);
        }
        try {
            let participants = JITSI_IFRAME.contentWindow.APP.conference.listMembersIds();
            if (participants.length == 0) {
                api.executeCommand('stopRecording', 'file');
            }
        } catch (error) {
            console.log(`api.participantLeft::Error: `, error.message);
        }
    });
    api.on('readyToClose', () => {
        try {
            let participants = JITSI_IFRAME.contentWindow.APP.conference.listMembersIds();
            if (participants.length == 0) {
                api.executeCommand('stopRecording', 'file');
            }
        } catch (error) {
            console.log(`api.readyToClose::Error: `, error.message);
        }

        try {
            console.info("api.readyToClose::");
            console.log('iframe.src=', JITSI_IFRAME.src);
            JITSI_IFRAME.src = '/show-logo?empty-layout=1';
            flash_msg($translate_text_messages.attr('data-msg-10'), 'error', 0);
            setTimeout(function() {
                flash_msg($translate_text_messages.attr('data-msg-5'), 'error', -1);
            }, 300);
            //api = null;
        } catch (error) {
            console.log(`api.readyToClose::Error: `, error.message);
        }

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

    /*
    //https://jitsi.github.io/handbook/docs/dev-guide/dev-guide-iframe#startrecording
    //https://github.com/jitsi/jitsi-meet/issues/6954
    //https://community.jitsi.org/t/jitsi-recording-with-executecommand-external-api/68632/2
    //https://community.jitsi.org/t/proper-way-to-use-startrecording-to-stream-to-custom-rtmp-link/86872/6
    api.executeCommand('startRecording', {
        mode: string //recording mode, either `file` or `stream`.
        dropboxToken: 'sl.AteCfeCd6z8QyZVsdPfbtm7R6mMIqI8WYoc16iKJCc3XYS5Cam4a6mSCFXdD8BK9K9QMB8CiHW5PcHxn9aGXk1rnE9Pyz1OxOX3aRn_JxnCxtbrOwKHqrGfKOeGV8Bi6SRkSCGi_j8s', //dropbox oauth2 token.
        shouldShare: boolean, //whether the recording should be shared with the participants or not. Only applies to certain jitsi meet deploys.
        rtmpStreamKey: string, //the RTMP stream key.
        rtmpBroadcastID: string, //the RTMP broadcast ID.
        youtubeStreamKey: string, //the youtube stream key.
        youtubeBroadcastID: string //the youtube broacast ID.
    });
    */
    //api.addEventListener('videoConferenceJoined', (e) => { api.executeCommand('startRecording', { mode: 'file' }); });

    /**/
    let $test = $present_info_content.find('.is-editable');
    if ($test.length) {
        $('.only-for-editable').show();
    } else {
        $('.only-for-editable').hide();
    }


    /* SlidesWebSocket */
    if ($present_info_content.length && $present_info_content[0].hasAttribute('data-wss-url')) {

        //wss_user = $present_info_content.data('wss-user');

        /**/
        let alerted_about_lost_connection = false;
        SlidesWebSocket = new Ws($present_info_content.data('wss-url'), 5); // второй параметр если 0 то реконекта не будет, иначе реконект после заданного кол-ва секунд
        SlidesWebSocket.onopen = function() {
            //this.connected = true;
            alerted_about_lost_connection = false;
            console.log('Connected to ' + this._url);
        };
        SlidesWebSocket.onmessage = function (message) {

            console.info("api.endpointTextMessageReceived:: data=", message.data);
            let parsed = JSON.parse(message.data);

            /**/
            if (isStudent || !is_class_room) {

                /**/
                if ("redirectTo" in parsed) {
                    console.info('api.endpointTextMessageReceived::redirectTo=', parsed.redirectTo);
                    window.location.href = parsed.redirectTo;
                }

                /**/
                if ("device_status" in parsed && "additional_data" in parsed) {
                    //if (parsed.additional_data.device == 'audio') {
                    //    if (parsed.additional_data.muted) {
                    //        flash_msg($translate_text_messages.attr('data-msg-11'), 'error', 0);
                    //    } else {
                    //        flash_msg($translate_text_messages.attr('data-msg-12'), 'success', FLASH_TIMEOUT);
                    //    }
                    //}
                    //if (parsed.additional_data.device == 'video') {
                    //    if (parsed.additional_data.muted) {
                    //        flash_msg($translate_text_messages.attr('data-msg-13'), 'error', 0);
                    //    } else {
                    //        flash_msg($translate_text_messages.attr('data-msg-14'), 'success', FLASH_TIMEOUT);
                    //    }
                    //}
                }
            }

        };
        SlidesWebSocket.onerror = function(error) {
            if (!alerted_about_lost_connection) {
                if (isTeacher) {
                    prettyAlert($translate_text_messages.attr('data-msg-16'));
                    alerted_about_lost_connection = true;
                }
            }
        };
        SlidesWebSocket.send = function(message) {
            if (this.getState() == this.State.CONNECTING) {
                setTimeout(function () {
                    this.send(message);
                }.bind(this), 1000);
            } else {
                if (this.connected) {
                    //console.log('Sent: ' + message);
                    this._ws.send(message);
                } else {
                    prettyAlert($translate_text_messages.attr('data-msg-17'));
                    console.log("Socket connection error!");
                }
            }
        }
    }
});


/******
 ******
 *****/
$(window).bind('beforeunload', function(eventObject) {
    var returnValue = undefined;
    //TYPE_METHODIST; TYPE_TEACHER; TYPE_STUDENT
    if (USER_TYPE != USER_TYPES.TYPE_STUDENT && !last_slide_is_done && is_class_room) {

        if (JITSI_IFRAME && api) {
            try {
                let participants = JITSI_IFRAME.contentWindow.APP.conference.listMembersIds();
                if (participants.length == 0) {
                    api.executeCommand('stopRecording', 'file');
                }
            } catch (error) {
                console.log(`beforeunload::Error: `, error.message);
            }
        }

        //prettyAlert(unload_text);
        returnValue = "Do you really want to close?";
        eventObject.returnValue = returnValue;
        return returnValue;
    }
    //return false;
    //$(window).trigger('unload');
});
