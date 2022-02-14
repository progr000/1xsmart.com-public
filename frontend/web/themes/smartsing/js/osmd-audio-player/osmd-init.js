const TTL_WAIT_VOICE_FROM_STUDENT = 3000;
const CALIBRATION_FACTOR = 1.4; //0.5; //1.4;

const MIN_ANALYSE_FREQUENCY = 100;
const MAX_ANALYSE_FREQUENCY = 1200;

const targetCloseColor = "#FFFF00";
const targetHitColor = "#81cc00";
const targetCloseClass = "note-close";
const targetHitClass = "note-hit";
const targetColor = "#d5d8dc";
const updateInterval = 50;
const fftSize = 2048;
const notesName = [
    'ДО',
    'ДО#',
    'РЕ',
    'РЕ#',
    'МИ',
    'ФА',
    'ФА#',
    'СОЛЬ',
    'СОЛЬ#',
    'ЛЯ',
    'ЛЯ#',
    'СИ'
];
const pitchesArray = [
    'C0',  // до
    'C#0', // до#
    'D0',  // ре
    'D#0', // ре#
    'E0',  // ми
    'F0',  // фа
    'F#0', // фа#
    'G0',  // соль
    'G#0', // соль#
    'A0',  // ля
    'A#0', // ля#
    'B0',  // си
    'C1',
    'C#1',
    'D1',
    'D#1',
    'E1',
    'F1',
    'F#1',
    'G1',
    'G#1',
    'A1',
    'A#1',
    'B1',
    'C2',
    'C#2',
    'D2',
    'D#2',
    'E2',
    'F2',
    'F#2',
    'G2',
    'G#2',
    'A2',
    'A#2',
    'B2',
    'C3',
    'C#3',
    'D3',
    'D#3',
    'E3',
    'F3',
    'F#3',
    'G3',
    'G#3',
    'A3',
    'A#3',
    'B3',
    'C4',
    'C#4',
    'D4',
    'D#4',
    'E4',
    'F4',
    'F#4',
    'G4',
    'G#4',
    'A4',
    'A#4',
    'B4',
    'C5',
    'C#5',
    'D5',
    'D#5',
    'E5',
    'F5',
    'F#5',
    'G5',
    'G#5',
    'A5',
    'A#5',
    'B5',
    'C6',
    'C#6',
    'D6',
    'D#6',
    'E6',
    'F6',
    'F#6',
    'G6',
    'G#6',
    'A6',
    'A#6',
    'B6',
    'C7',
    'C#7',
    'D7',
    'D#7',
    'E7',
    'F7',
    'F#7',
    'G7',
    'G#7',
    'A7',
    'A#7',
    'B7',
    'C8'
];

let ownAnalyser = null;
let ownStream = null;
let ownMuted = false;
let ownSource = null;
let ownLastMeasuredPitch = -100;

let analyseAudioTimeout = -1;
let pitchToChangeNote = -100;
let dataArray = new Float32Array(fftSize);

let musicRms = [];
let silentRms = [];
let lowRmsCount = 0;

let notesPlayed = 0;
let notesHit = 0;
let notesClose = 0;
let lowestNote = -1;
let highestNote = -1;

let osmd = null;
let audioPlayer = null;
let audioCtx = null;
let audioPlayerCtx = null;
let noteElementsContainer = null;
let audioPlayerNotesToPlay = -1;
let targetPitchesClearTimeout = -1;
let targetPitches = [];
let cursorsParameters = new Map();
let markedNotesElements = new Map();

let ownRmsLimit = window.localStorage.getItem("ownRmsLimit");
ownRmsLimit = ownRmsLimit == null ? 0.01 : Number(ownRmsLimit);

let volumeAmplifier = window.localStorage.getItem("volumeAmplifier");
let osmdApBpm = 100;

let $osmd_canvas = $('#osmd-canvas');
let $osmd_volume = $('#osmd-ap-volume');
let $osmd_bpm = $('#osmd-ap-bpm');
let $btn_play = $('#btn-play');
let $btn_pause = $('#btn-pause');
let $count_notes_to_play = $('#count_notes_to_play');
let $btn_replay = $('#btn-replay');
let $btn_next = $('#btn-next');
let $btn_prev = $('#btn-prev');
let $btn_last = $('#btn-last');
let $btn_first = $('#btn-first');
let $system_action = $('#system-action');
let $user_action = $('#user-action');

let isTeacherConnected = false;
let isStudentConnected = false;
let isStudent = false;
let isTeacher = false;
let wasClose = false;
let audioPlayerLoaded = false;
let btnRegistered = false;
let isHomework = false;
let isMethodist = false;
let slaveCountNotesToPlay = -1;

let audioCtxInitialized = false;
let isCalibrated = false;
let studentIsCalibrated = false;
let isPlayOneForCalibrate = false;
let countCalibrateTries = 1;
let maxCalibrateTries = 5;

let notesHitArray = [];
let notesCloseArray = [];

let restoreContainer = {
    notesHitArray: [],
    notesCloseArray: [],
    position: 0,
    notesPlayed: 0,
    notesHit: 0,
    notesClose: 0,
    lowestNote: -1,
    highestNote: -1,
    count_notes_to_play: -1
};

function getRandomArbitrary(min, max) {
    return Math.random() * (max - min) + min;
}

if (window.AnalyserNode && !window.AnalyserNode.prototype.getFloatTimeDomainData) {
    var uint8 = new Uint8Array(2048);
    window.AnalyserNode.prototype.getFloatTimeDomainData = function (array) {
        this.getByteTimeDomainData(uint8);
        for (var i = 0, imax = array.length; i < imax; i++) {
            //array[i] = (uint8[i] - 128) * 0.0078125;
            array[i] = (uint8[i] - 128) * 0.0078125;
            //array[i] = (getRandomArbitrary(126, 128) - 128.0) * 0.0078125;
        }
    };
}

/**
 *
 */
function saveCurrentPreset()
{
    console.info(`saveCurrentPreset::saveCurrentPreset() is started`);

    restoreContainer.notesHitArray = notesHitArray.slice();
    restoreContainer.notesCloseArray = notesCloseArray.slice();
    restoreContainer.position = audioPlayer.currentIterationStep;
    restoreContainer.notesPlayed = notesPlayed;
    restoreContainer.notesHit = notesHit;
    restoreContainer.notesClose = notesClose;
    restoreContainer.lowestNote = lowestNote;
    restoreContainer.highestNote = highestNote;
    restoreContainer.count_notes_to_play = ($count_notes_to_play.length) ? parseInt($count_notes_to_play.val()) : slaveCountNotesToPlay;

    console.log('saveCurrentPreset::restoreContainer=', restoreContainer);

    console.info(`saveCurrentPreset::saveCurrentPreset() is finished`);
}

/**
 *
 */
function restoreCurrentPreset()
{
    console.info(`restoreCurrentPreset::restoreCurrentPreset() is started`);

    console.log('restoreCurrentPreset::restoreContainer=', restoreContainer);

    notesHitArray = restoreContainer.notesHitArray.slice();
    notesCloseArray = restoreContainer.notesCloseArray.slice();
    notesPlayed = restoreContainer.notesPlayed;
    notesHit = restoreContainer.notesHit;
    notesClose = restoreContainer.notesClose;
    lowestNote = restoreContainer.lowestNote;
    highestNote = restoreContainer.highestNote;
    audioPlayerNotesToPlay = restoreContainer.count_notes_to_play;

    audioPlayer.currentIterationStep = restoreContainer.position;
    audioPlayer.scheduler.reset();
    audioPlayer.scheduler.setIterationStep(audioPlayer.currentIterationStep);
    resetCursor();

    for (let i = 0; i < notesHitArray.length; i++) {
        markNote(notesHitArray[i], false, true); //hit
    }

    for (let i = 0; i < notesCloseArray.length; i++) {
        markNote(notesCloseArray[i], true, false); //close
    }

    console.info(`restoreCurrentPreset::restoreCurrentPreset() is finished`);
}

/**
 * @returns {number}
 */
function getTtlWaitVoiceFromStudent()
{
    if (!isCalibrated) {
        return 100;
    }
    console.log('getTtlWaitVoiceFromStudent::isCalibrated=', isCalibrated);
    let $wait_note = $('#wait-note');
    return $wait_note.length && $wait_note.val() ? parseInt($wait_note.val()) * 1000 : TTL_WAIT_VOICE_FROM_STUDENT;
}

/**
 *
 */
function destroyOsmdAudioPlayer()
{
    /**/
    $osmd_canvas = $('#osmd-canvas');
    osmd = null;
    audioPlayer = null;
    $osmd_canvas.html('');

    /**/
    countCalibrateTries = 1;
    isCalibrated = false;
    studentIsCalibrated = false;
    isPlayOneForCalibrate = false;
    audioPlayerLoaded = false;

    /* инициализация данных о нотах */
    notesPlayed = 0;
    notesHit = 0;
    notesClose = 0;
    lowestNote = -1;
    highestNote = -1;
    notesHitArray.clear();
    notesCloseArray.clear();
}

/**
 * @param {url} mxml
 */
async function loadMusicXml(mxml)
{
    $osmd_canvas = $('#osmd-canvas');
    $system_action = $('#system-action');
    $user_action = $('#user-action');

    $user_action.html('').hide();
    $('#player-buttons-div').addClass('hidden');

    //console.info(`loadMusicXml::loadMusicXml('${mxml}') is started`);
    destroyOsmdAudioPlayer();

    /**/
    initAudioContexts();

    /**/
    $system_action.show().html('Загрузка музыкального пресета');

    osmd = new opensheetmusicdisplay.OpenSheetMusicDisplay($osmd_canvas[0], {
        followCursor: false,
        autoResize: false,
        drawCredits: false,
        backend: "canvas"
    });
    //const audioPlayer = new OsmdAudioPlayer();
    audioPlayer = new OsmdAudioPlayer(audioPlayerCtx);

    const scoreXml = await fetch(mxml).then(r => r.text());

    //console.info(`loadMusicXml::Score xml: ${scoreXml}`);

    await osmd.load(scoreXml)
        .then(() => {
            console.info("loadMusicXml::osmd loaded");
            //osmdLoaded = true;
            if (audioPlayer && audioPlayer.state == "PLAYING") {
                //pausePlayer();
                stopPlayer();
            }
            osmdRender();
        })
        .catch(error => {
            console.log("loadMusicXml::osmd error: ", error.message);
            $system_action.show().html('<span class="red">Ошибка во время загрузки музыкального пресета.</span>');
        });
    //await osmd.render();
    //await audioPlayer.loadScore(osmd);


    await audioPlayer.loadScore(osmd).then(() => {
        console.info("loadMusicXml::loadAudioPlayer score loaded");
        if (isTeacher && !isHomework) {
            $system_action.show().html('Музыкальный пресет загружен. Выполните отправку слайда ученику.');
        } else {
            //$system_action.show().html('Музыкальный пресет загружен. Теперь выполните калибровку.');
            $system_action.hide().html('Музыкальный пресет загружен. Теперь выполните калибровку.');
        }
        if (!osmdApBpm) {
            osmdApBpm = audioPlayer.playbackSettings.bpm;
            if (!isStudent) {
                sendPlayerCommandToSlave('setApBpm', osmdApBpm);
            }
        }
        setApBpm(osmdApBpm);
        audioPlayerLoaded = true;
        //setApVolume();
        targetPitches.clear();
        wasClose = false;
        //updatePanelControlButton();
        for (let p of audioPlayer.instrumentPlayer.players.values()) {
            let tracked = new Map();
            p.onstarted = (when, nodeId, node) => {
                tracked.set(nodeId, node);
            };
            p.onended = (now, nodeId, node) => {
                tracked.delete(nodeId);
            };
            p.stop = function (when, ids) {
                let node;
                ids = ids || tracked.keys();
                for (let id of ids) {
                    node = tracked.get(id);
                    if (node == null) continue;
                    tracked.delete(id);
                    node.disconnect();
                }
                return ids;
            }
        }
        //if (!isHomework) {
        //    window.setTimeout(initApi, 0);
        //}
    });

    audioPlayer.on("iteration", onNextNote);
    audioPlayer.on("state-change", onApStateChange);


    //initAudioContexts();
    hideLoadingMessage();
    registerButtonEvents(audioPlayer);
    setApVolume(false);
    if (isStudent || isHomework) {
        $('.calibrate-div-button').addClass('hidden');
        window.setTimeout(function () {
            //$('.calibrate-div-button').removeClass('hidden');
        }, 1000);
    }
    if (isStudent) {

        console.info('loadMusicXml::Trying start function {setupOwnAnalyserWithApi}');
        window.setTimeout(setupOwnAnalyserWithApi, 0);

    }

    //console.info(`loadMusicXml::loadMusicXml('${mxml}') is finished`);
}

/**
 *
 */
function osmdRender()
{
    //console.info("osmdRender::osmdRender() is started");

    osmd.render();
    markedNotesElements.clear();
    osmd.cursor.show();
    cursorsParameters.clear();
    let i = 0;
    while (!osmd.cursor.Iterator.EndReached) {
        cursorsParameters.set(i, {
            cursorTop: osmd.cursor.cursorElement.style.top,
            cursorLeft: osmd.cursor.cursorElement.style.left,
            cursorHeight: osmd.cursor.cursorElement.height,
            cursorWidth: osmd.cursor.cursorElement.width
        });
        osmd.cursor.next();
        ++i;
    }
    osmd.cursor.reset();
    if (audioPlayer != null) {
        audioPlayer.currentIterationStep = 0;
        if (audioPlayer.scheduler != null) {
            audioPlayer.scheduler.reset();
        }
        audioPlayer.cursor = osmd.cursor;
    }

    noteElementsContainer = document.getElementById("osmd-canvas").childNodes[0];

    //osmd.cursor.cursorElement.style.transition = "all 0.2s";
    //const c = document.createElement("canvas");
    //c.width = osmd.cursor.cursorElement.width;
    //c.height = 1;
    //const ctx = c.getContext("2d");
    //ctx.globalAlpha = 1;
    //ctx.fillStyle = targetColor;
    //ctx.fillRect(0, 0, osmd.cursor.cursorElement.width, osmd.cursor.cursorElement.height);
    //osmd.cursor.cursorElement.src = c.toDataURL("image/png");
    //osmd.cursor.cursorElement.style.zIndex = "-10";

    //console.info("osmdRender::osmdRender() is finished");
}

/**
 *
 */
function calibrate()
{
    //console.info("calibrate::calibrate() is started");

    if (ownAnalyser == null || !audioPlayerLoaded) {
        console.log('calibrate::ownAnalyser = ', ownAnalyser);
        console.log('calibrate::audioPlayerLoaded = ', audioPlayerLoaded);
        window.setTimeout(calibrate, updateInterval);
        return;
    }
    //isCalibrated = true;
    try {
        //console.log(dataArray);
        ownAnalyser.getFloatTimeDomainData(dataArray);
    } catch (error) {
        console.log('calibrate::ownAnalyser.getFloatTimeDomainData error: ', error.message);
    }

    let SIZE = dataArray.length;
    let rms = 0;

    for (let i=0;i<SIZE;i++) {
        let val = dataArray[i];
        rms += val*val;
    }
    rms = Math.sqrt(rms/SIZE);
    if (audioPlayerCtx && (audioPlayerCtx.state == 'suspended' || audioPlayerCtx.state == 'interrupted')) {
        audioPlayerCtx.resume();
    }
    if (silentRms.length == 0) {
        silentRms.push(rms);
        audioPlayer.instrumentPlayer.players.values().next().value.play('A4');
    } else if (musicRms.length == 3) {
        musicRms.push(rms);
        audioPlayer.instrumentPlayer.players.values().next().value.play('A5');
    } else if (musicRms.length == 6) {
        musicRms.push(rms);
        audioPlayer.instrumentPlayer.players.values().next().value.play('A6');
    } else if (musicRms.length < 10) {
        musicRms.push(rms);
    } else if (silentRms.length < 10) {
        silentRms.push(rms);
    } else {
        let maxSilentRms = 0;
        for (let i=0; i<silentRms.length; i++) {
            if (silentRms[i] > maxSilentRms) {
                maxSilentRms = silentRms[i];
            }
        }
        let maxMusicRms = 0;
        for (let i=0; i<musicRms.length; i++) {
            if (musicRms[i] > maxMusicRms) {
                maxMusicRms = musicRms[i];
            }
            console.debug ("calibrate::music rms: ", musicRms[i]);
        }

        silentRms.clear();
        musicRms.clear();
        ownRmsLimit = maxMusicRms > maxSilentRms ? maxMusicRms : maxSilentRms;
        ownRmsLimit = ownRmsLimit * CALIBRATION_FACTOR;
        let $rms_factor = $('#rms-factor');
        let c_f = $rms_factor.length && $rms_factor.val() ? parseFloat($rms_factor.val()) / 10 : CALIBRATION_FACTOR;
        console.log('calibrate::c_f = ', c_f);
        ownRmsLimit = ownRmsLimit * c_f;
        window.localStorage.setItem("ownRmsLimit", ownRmsLimit);
        console.info(`calibrate::maxSilentRms:  ${maxSilentRms}, maxMusicRms: ${maxMusicRms}, ownRmsLimit: ${ownRmsLimit}`);
        console.info("calibrate::calibrate() is finished");
        console.log('calibrate::dataArray = ', dataArray);

        /**/
        let sum = 0;
        for (var i = 0, imax = dataArray.length; i < imax; i++) {
            sum += dataArray[i];
        }
        if (sum == 0) {
            if (countCalibrateTries < maxCalibrateTries) {
                countCalibrateTries++;
                console.log('calibrate::failed sum = ', sum);
                $system_action.show().html(`Калибровка не удалась, повторная попытка #${countCalibrateTries}`);
                sendPlayerCommandToMaster('addTextToUserAction', `Калибровка устройства ученика не удалась, повторная попытка #${countCalibrateTries}`);
                window.setTimeout(calibrate, updateInterval);
                return;
            } else {
                $(document).find('.calibrate-flash').first().remove();
                $system_action.show().html(`<span class="red">Калибровка не удалась. Возможно у вас проблемы с микрофоном или он выключен.</span>`);
                if (isStudent) {
                    studentIsCalibrated = false;
                    isCalibrated = false;
                    if (typeof sendEndpointTextMessage == 'function') {
                        sendEndpointTextMessage(JSON.stringify({
                            studentExecuteCalibrate: false
                        }));
                    }
                }
                return;
            }
        }

        /**/
        if (isPlayOneForCalibrate) {
            $system_action.show().html('Калибровка микрофона завершнена');
            if (isStudent) {
                $(document).find('.calibrate-flash').first().remove();
                studentIsCalibrated = true;
                if (typeof sendEndpointTextMessage == 'function') {
                    sendEndpointTextMessage(JSON.stringify({
                        studentExecuteCalibrate: true
                    }));
                }
            }
        }

        //analyseAudioTimeout = window.setTimeout(analyseAudio, updateInterval);
        isCalibrated = true;
        return;
    }

    console.log("calibrate::Calibrating. Please keep silence...");

    if (isPlayOneForCalibrate) {
        $system_action.show().html('Постарайтесь не шуметь. Производится калибровка микрофона...');
    }

    window.setTimeout(calibrate, updateInterval);

    //console.info("calibrate::calibrate() is finished");
}

/**
 *
 * @param {boolean} need_restore
 */
function playOneForCalibrate(need_restore=false)
{
    console.info('playOneForCalibrate');
    //var audio = new Audio('/assets/smartsing-min/sounds/calibrating.mp3');
    //audio.play();

    isPlayOneForCalibrate = true;

    $('.calibrate-div-button').addClass('hidden');
    //audioPlayerNotesToPlay = 1;
    //audioPlayer.play();
    console.log('playOneForCalibrate::audioPlayer = ', audioPlayer);
    //stopPlayer();
    targetPitches.clear();
    wasClose = false;
    initAudioContexts();

    //calibrate();

    window.setTimeout(() => {
        if (typeof api != 'undefined' && api) {
            setupOwnAnalyserWithApi();
        } else {
            setupOwnAnalyserWithMedia();
        }
        calibrate();
        $('#player-buttons-div').removeClass('hidden');
        //stopPlayer();
        targetPitches.clear();
        wasClose = false;
        $osmd_canvas.find('img.note-hit').remove();
        $osmd_canvas.find('img.note-close').remove();
        if (need_restore) {
            restoreCurrentPreset();
        }

        //if (isStudent) {
        //    $(document).find('.calibrate-flash').first().remove();
        //    studentIsCalibrated = true;
        //    if (typeof sendEndpointTextMessage == 'function') {
        //        sendEndpointTextMessage(JSON.stringify({
        //            studentExecuteCalibrate: true
        //        }));
        //    }
        //}
        //isCalibrated = true;
    }, 500);
}

/**
 *
 */
function initAudioContexts()
{
    //if (isPresentation) return;
    //console.info("initAudioContexts::initAudioContexts() is started");

    let AudioContext = window.AudioContext // Default
        || window.webkitAudioContext // Safari and old versions of Chrome
        || false;

    if (AudioContext) {

        if (!audioCtx) {
            audioCtx = new AudioContext();
        }
        console.log('initAudioContexts::audioCtx: ', audioCtx);
        if (audioCtx && (audioCtx.state == 'suspended' || audioCtx == 'interrupted')) {
            audioCtx.resume();
        }

        if (!audioPlayerCtx) {
            audioPlayerCtx = new AudioContext();
        }
        if (audioPlayerCtx && (audioPlayerCtx.state == 'suspended' || audioPlayerCtx.state == 'interrupted')) {
            audioPlayerCtx.resume();
        }

    } else {
        console.log("Sorry, but the Web Audio API is not supported by your browser.");
        flash_msg('Sorry, but the Web Audio API is not supported by your browser.', 'error', FLASH_TIMEOUT);
    }
    //console.info("initAudioContexts::initAudioContexts() is finished");
}

/**
 *
 */
function followCursor()
{
    let cursorElement = document.getElementById('cursorImg-0');
    //cursorElement.scrollIntoView({behavior:t<1e3?"smooth":"auto",block:"center"}); this.cursorElement.scrollTop -= 20
    cursorElement.scrollIntoView({ behavior:"auto", block:"center" });
    window.scrollBy(0, -100);
}

/**
 * @param notes
 */
function onNextNote(notes)
{
    //console.info(`onNextNote::audioPlayer.iterationSteps = ${audioPlayer.iterationSteps}`);
    //console.info(`onNextNote::audioPlayer.currentIterationStep = ${audioPlayer.currentIterationStep}`);

    /**/
    console.log(`onNextNote::audioPlayer.state = `, audioPlayer.state);
    console.log(`onNextNote::audioPlayerNotesToPlay = `, audioPlayerNotesToPlay);

    $system_action.show().html('Играем ноту...');
    if (audioPlayer.state == "PLAYING") {
        if (audioPlayerNotesToPlay == -1) {

            // играем весь нотный ряд

        } else if (audioPlayerNotesToPlay > 1) {

            audioPlayerNotesToPlay -= 1;

        } else if (audioPlayerNotesToPlay == 2) {

            audioPlayer.setBpm(50);

        } else if (audioPlayerNotesToPlay == 1) {

            audioPlayerNotesToPlay = 0;
            //if (isTeacher) {
            //    pausePlayer(false);
            //}
            pausePlayer(true);

        }

        //console.log(`onNextNote::audioPlayerNotesToPlay(after) = `, audioPlayerNotesToPlay);

        // оставнока плеера если достигнут конец нотного ряда
        if (audioPlayer.currentIterationStep >= audioPlayer.iterationSteps) {
            console.log("onNextNote:: end reached");
            $system_action.show().html('Достигнут конец нотного ряда.');
            audioPlayer.currentIterationStep = audioPlayer.iterationSteps;
            pausePlayer(true);
            return void(0);
        }

        /* очистка предыдущего попадания (подсветки) - например если это был реплей */
        let position = audioPlayer.currentIterationStep - 1;
        let note_id = `note-position-${position}`;
        let $note_el = $(`#${note_id}`);
        if ($note_el.length) {
            $note_el.remove();
        }
        let index_del;
        index_del = notesHitArray.indexOf(position);
        if (index_del > -1) {
            notesHitArray.splice(index_del, 1);
        }
        index_del = notesCloseArray.indexOf(position);
        if (index_del > -1) {
            notesCloseArray.splice(index_del, 1);
        }
        console.log('onNextNote::notesHitArray=', notesHitArray);
        console.log('onNextNote::notesCloseArray=', notesCloseArray);

        /* обновляем массив нот */
        //console.log(`onNextNote::isCalibrated = `, isCalibrated);
        if (isStudent || isHomework) {
            if (isCalibrated) {

                updateTargetNote();

            } else {

                //calibrate();

            }
        } else if (isTeacher) {

            showInfoTargetNote();

        }

        /* если заглючит followCursor: true тогда попробуем вот это настроить */
        //var scrollTop = $('#cursorImg-0').offset().top;
        //console.log('scrollTop = ', scrollTop);
        //$('#present-info-content').scrollTop(scrollTop);
        //$(window).scrollTop();
        followCursor();

    } else {
        console.log("onNextNote:: wrong state");
        //pausePlayer(true);
        //audioPlayer.scheduler.reset();
        //audioPlayer.pause();
    }
}

/**
 *
 */
function updateTargetNote()
{
    //console.info("updateTargetNote::updateTargetNote() is started");

    //let position = osmd.cursor.Iterator.currentVoiceEntryIndex;
    let position = audioPlayer.currentIterationStep - 1;

    //console.log(`updateTargetNote::osmd.cursor.Iterator.currentVoiceEntryIndex = ${osmd.cursor.Iterator.currentVoiceEntryIndex}`);
    //console.log(`updateTargetNote::audioPlayer.currentIterationStep = ${audioPlayer.currentIterationStep}`);

    //console.log('updateTargetNote::LLL', osmd.cursor.VoicesUnderCursor()[0].notes[0].length.realValue);

    if (osmd.cursor.VoicesUnderCursor().length > 0 &&
        osmd.cursor.VoicesUnderCursor()[0].notes.length > 0 &&
        osmd.cursor.VoicesUnderCursor()[0].notes[0].pitch) {

        let pitch = osmd.cursor.VoicesUnderCursor()[0].notes[0].pitch.frequency;

        //console.log(`updateTargetNote::position = ${position}`);
        //console.log(`updateTargetNote::VoicesUnderCursor = `, osmd.cursor.VoicesUnderCursor()[0].notes[0]);
        //console.log(`updateTargetNote::pitch = ${pitch}`);

        /* тут происходит запуск анализатора звуков ожидаемых от студента */
        if ((isStudent || isHomework) && targetPitches.length == 0 && analyseAudioTimeout == -1) {
            if (isStudent) {
                //$system_action.show().html(`<span class="green">Анализатор запущен. Начинайте пытаться спеть ноту.</span>`);
            } else {
                addTextToUserAction(`<span class="green">Анализатор запущен. Начинайте пытаться спеть ноту.</span>`);
            }
            if (isTeacherConnected) {
                sendPlayerCommandToMaster('addTextToUserAction', `<span class="green">Анализатор запущен (у ученика). Ждем попытки спеть ноту.</span>`);
            }
            console.info("updateTargetNote::start analysing audio");
            analyseAudioTimeout = window.setTimeout(analyseAudio, updateInterval);
        }

        wasClose = false;
        let { noteIndex, noteCode, noteName } = getNote(pitch);
        if (!isStudent) {
            $system_action.show().html(`Проиграна ${position + 1}-я нота "${noteName}", ее код "${noteCode}", ее частота "${pitch}", ee индекс ${noteIndex}`);
        }
        if (isStudent) {
            $system_action.show().html(`Проиграна ${position + 1}-я нота`);
            //$system_action.show().html(`Проиграна ${position + 1}-я нота "${noteName}"`);
        }
        console.log(`updateTargetNote::played the ${position + 1}-st(d) note "${noteName}", its code "${noteCode}", its frequency "${pitch}", its index is "${noteIndex}"`);
        targetPitches.push({position: position, pitch: pitch, noteIndex: noteIndex});

        /* тут происходит остановка анализатора звуков ожидаемых от студента */
        if (isStudent || isHomework) {
            let WaitNote = getTtlWaitVoiceFromStudent();
            console.log('onApStateChange::WaitNote = ', WaitNote);

            targetPitchesClearTimeout = window.setTimeout(() => {
                targetPitches.clear();
                wasClose = false;
            }, WaitNote);
        }

        console.log('updateTargetNote::targetPitches = ', targetPitches);
        notesPlayed += 1;
        if (targetPitches.length == 2) {
            targetPitches.shift();
        }
        pitchToChangeNote = pitch * 2;
    }

    //console.info("updateTargetNote::updateTargetNote() is finished");
}

/**
 *
 */
function showInfoTargetNote()
{
    let position = audioPlayer.currentIterationStep - 1;

    if (osmd.cursor.VoicesUnderCursor().length > 0 &&
        osmd.cursor.VoicesUnderCursor()[0].notes.length > 0 &&
        osmd.cursor.VoicesUnderCursor()[0].notes[0].pitch) {

        let pitch = osmd.cursor.VoicesUnderCursor()[0].notes[0].pitch.frequency;
        let { noteIndex, noteCode, noteName } = getNote(pitch);
        $system_action.show().html(`Проиграна ${position + 1}-я нота "${noteName}", ее код "${noteCode}", ее частота "${pitch}", ee индекс ${noteIndex}`);
        console.log(`showInfoTargetNote::played the ${position + 1}-st(d) note "${noteName}", its code "${noteCode}", its frequency "${pitch}", its index is "${noteIndex}"`);
    }
}

/**
 * @param data
 */
function synchronizeStudentResultData(data)
{
    notesPlayed = data.notesPlayed;
    notesHit = data.notesHit;
    notesClose = data.notesClose;
    lowestNote = data.lowestNote;
    highestNote = data.highestNote;
}

/**
 * @param pitch
 * @returns {string}
 */
function getNoteStr(pitch) {
    if (pitch < 0) {
        return "";
    }
    var noteNum = 12 * (Math.log( pitch / 440 )/Math.log(2) );
    var note = Math.round( noteNum ) + 69 - 12;
    if (note >= pitchesArray.length ) return "";
    return pitchesArray[note];
}

/**
 * @param pitch
 * @returns {*}
 */
function getNote(pitch)
{
    if (pitch < 0) {
        return -1;
    }
    let noteNum = 12 * (Math.log( pitch / 440 )/Math.log(2) );
    let note = Math.round( noteNum ) + 69 - 12;
    //console.log('getNote::pitch = ', pitch);
    //console.log('getNote::note = ', note);
    //console.log('getNote::pitchesArray[note] = ', pitchesArray[note]);
    if (note >= pitchesArray.length ) return -1;
    let noteIndex = note % 12;
    return {
        noteIndex: noteIndex,
        note: note,
        pitch: pitch,
        noteCode: (typeof pitchesArray[note] != 'undefined') ? pitchesArray[note] : 'Unknown',
        noteName: (typeof notesName[noteIndex] != 'undefined') ? notesName[noteIndex] : 'Unknown'
    };
}

/**
 * @returns {boolean}
 */
function setupOwnAnalyserWithApi()
{
    //console.info("setupOwnAnalyserWithApi::setupOwnAnalyserWithApi() is started");

    if (!ownStream) {

        ownStream = null;
        if (!api) {
            window.setTimeout(setupOwnAnalyserWithApi, 100);
            return;
        }
        //api.isAudioMuted()
        //    .then(muted => {
        //        ownMuted = muted;
        try {
            let tracks = api.getIFrame().contentWindow.APP.store.getState()["features/base/tracks"];
            for (let track of tracks) {
                if (track
                    && track.jitsiTrack
                    && track.jitsiTrack.stream
                    && track.jitsiTrack.stream.getAudioTracks().length > 0
                    && track.mediaType == "audio"
                ) {
                    if (track.local) {
                        ownStream = track.jitsiTrack.stream;
                        break;
                    }
                }
            }
        } catch (error) {
            console.log(`setupOwnAnalyserWithApi::receive tracks error: ${error.message}`);
            return false;
        }

        if (ownStream) {
            setupOwnAnalyser();
        } else {
            window.setTimeout(setupOwnAnalyserWithApi, 100);
        }

        //    });
        console.info('setupOwnAnalyserWithApi::ownStream:', ownStream);

    }

    //console.info("setupOwnAnalyserWithApi::setupOwnAnalyserWithApi() is finished");
}

/**
 *
 */
async function setupOwnAnalyserWithMedia()
{
    //console.info("setupOwnAnalyserWithMedia::setupOwnAnalyserWithMedia() is started");

    //ownStream = null;
    //console.log('setupOwnAnalyserWithMedia::navigator.mediaDevices.getUserMedia = ', (typeof navigator.mediaDevices.getUserMedia));
    //navigator.mediaDevices.getUserMedia({
    //        audio: {
    //            autoGainControl: false,
    //            googAutoGainControl: false,
    //            echoCancellation: false,
    //            googEchoCancellation: false,
    //            noiseSuppression: false,
    //            googNoiseSuppression: false,
    //            stereo: true
    //        }
    //    })
    //    .then(stream => {
    //        ownStream = stream;
    //        console.log('setupOwnAnalyserWithMedia::ownStream = ', (typeof ownStream));
    //        setupOwnAnalyser();
    //    })
    //    .catch(error => {
    //        console.error("setupOwnAnalyserWithMedia:: error: ", error.message);
    //        window.setTimeout(setupOwnAnalyserWithMedia, 1000);
    //    });


    if (!ownStream) {

        ownStream = null;
        try {
            if (navigator.mediaDevices === undefined) {
                navigator.mediaDevices = {};
            }

            if (navigator.mediaDevices.getUserMedia === undefined) {
                navigator.mediaDevices.getUserMedia = function (constraints) {
                    // First get ahold of the legacy getUserMedia, if present
                    let getUserMedia = navigator.getUserMedia
                        || navigator.webkitGetUserMedia
                        || navigator.mozGetUserMedia
                        || MediaDevices.getUserMedia();

                    // Some browsers just don't implement it - return a rejected promise with an error
                    // to keep a consistent interface
                    if (!getUserMedia) {
                        return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
                    }

                    // Otherwise, wrap the call to the old navigator.getUserMedia with a Promise
                    return new Promise(function (resolve, reject) {
                        getUserMedia.call(navigator, constraints, resolve, reject);
                    });
                }
            }

            ownStream = await navigator.mediaDevices.getUserMedia({
                audio: {
                    autoGainControl: false,
                    googAutoGainControl: false,
                    echoCancellation: false,
                    googEchoCancellation: false,
                    noiseSuppression: false,
                    googNoiseSuppression: false,
                    stereo: true
                }
            });
            console.log('setupOwnAnalyserWithMedia::ownStream = ', (typeof ownStream));
            setupOwnAnalyser();
            /* используем поток */
        } catch (error) {
            /* обработка ошибки */
            console.log('setupOwnAnalyserWithMedia::navigator.mediaDevices = ', (typeof navigator.mediaDevices));
            console.error("setupOwnAnalyserWithMedia:: error: ", error.message);
            //console.log(navigator.mediaDevices.getSupportedConstraints());
            window.setTimeout(setupOwnAnalyserWithMedia, 1000);
        }

    }

    //console.info("setupOwnAnalyserWithMedia::setupOwnAnalyserWithMedia() is finished");
}

/**
 *
 */
function setupOwnAnalyser()
{
    //console.info("setupOwnAnalyser::setupOwnAnalyser() is started");

    if (!ownAnalyser) {

        ownAnalyser = audioCtx.createAnalyser();
        ownAnalyser.smoothingTimeConstant = 0.005;
        ownAnalyser.fftSize = fftSize;
        ownSource = audioCtx.createMediaStreamSource(ownStream);
        ownSource.connect(ownAnalyser);

        if (!isCalibrated) {
            //window.setTimeout(calibrate, 0);
        }

    }

    //console.info("setupOwnAnalyser::setupOwnAnalyser() is finished");
}

/**
 *
 */
function analyseAudio()
{
    //console.info("analyseAudio::analyseAudio() is started");

    /* остановка анализатора в случае если массив targetPitches уже пустой */
    //if (panelIsHidden || (targetPitches.length == 0 && audioPlayer && audioPlayer.state != "PLAYING")) {
    if (targetPitches.length == 0 && audioPlayer && audioPlayer.state != "PLAYING") {
        console.info("analyseAudio::stop analysing audio");
        if (isStudent) {
            //$system_action.show().html(`<span class="red">Анализатор остановлен. Уже можно не петь.</span>`);
        } else {
            addTextToUserAction(`<span class="red">Анализатор остановлен. Уже можно не петь.</span>`);
        }
        if (isTeacherConnected) {
            sendPlayerCommandToMaster('addTextToUserAction', `<span class="red">Анализатор остановлен (у ученика).</span>`);
        }
        $('#audio-analyser-calibrate').innerText = "CALIBRATE";
        clearTimeout(analyseAudioTimeout);
        analyseAudioTimeout = -1;
        lowRmsCount = 0;
        wasClose = false;
        return;
    }

    if (audioCtx.state == 'suspended' || audioCtx.state == 'interrupted') {
        console.info("analyseAudio::resume audio ctx");
        audioCtx.resume();
        analyseAudioTimeout = window.setTimeout(analyseAudio, updateInterval);
        return;
    }

    if (!ownStream || !ownStream.active) {
        console.error("analyseAudio::Stream stopped, restarting!");
        window.setTimeout(() => {
            if (isHomework) {
                setupOwnAnalyserWithMedia();
            } else {
                setupOwnAnalyserWithApi();
            }
        }, 1000);
        //clearTimeout(analyseAudioTimeout);
        //analyseAudioTimeout = -1;
        //analyseAudioTimeout = window.setTimeout(analyseAudio, updateInterval);
        return;
    }

    if (targetPitches.length > 0) {
        if (ownMuted) {

        } else {
            try {
                ownAnalyser.getFloatTimeDomainData(dataArray);
            } catch (error) {
                console.log('analyseAudio::ownAnalyser.getFloatTimeDomainData error: ', error.message);
            }
            ownLastMeasuredPitch = analyseAudioData(true, ownLastMeasuredPitch, ownRmsLimit);
        }
    }

    analyseAudioTimeout = window.setTimeout(analyseAudio, updateInterval);

    //console.info("analyseAudio() is finished");
}

/**
 * @param isOwn
 * @param lastMeasuredPitch
 * @param rmsLimit
 * @returns {number}
 */
function analyseAudioData(isOwn, lastMeasuredPitch, rmsLimit)
{
    //console.info("analyseAudioData::analyseAudioData() is started");

    let { pitch, rms } = autoCorrelate(dataArray, audioCtx.sampleRate, rmsLimit);

    if (pitch < 0) {
        if (isOwn) {
            lowRmsCount += 1;
            if (lowRmsCount == 30 && !wasClose) {
                //$system_action.html(`<span class="red">Вас не слышно, попробуйте испльзовать гарнитуру или увеличить громкость (автоматически будет произведена перекалибровка)</span>`);
                if (isTeacherConnected) {
                    sendPlayerCommandToMaster('addTextToUserAction', `<span class="red">Ученика не слышно. Возможно проблемы с его микрофоном или гарнитурой.</span>`);
                }
                console.log("analyseAudioData::Can't hear you, use headphones or decrease music volume and recalibrate");
                return 0;
            }
        }
        return 0;
    } else if (isOwn) {
        lowRmsCount = 0;
        $('#audio-analyser-calibrate').innerText = "CALIBRATE";
    }

    if (lastMeasuredPitch > pitch * 1.15 || lastMeasuredPitch < pitch * 0.85) {
        //console.info("analyseAudioData::pitch: ", pitch, " and lastMeasured pitch: ", lastMeasuredPitch, " differs too much")
        return pitch;
    }
    if (pitch < MIN_ANALYSE_FREQUENCY) {
        if (!isStudent) {
            addTextToUserAction(`Пропет звук с частотй ниже чем ${MIN_ANALYSE_FREQUENCY}. Такие частоты мы не анализируем.`);
        }
        if (isTeacherConnected) {
            sendPlayerCommandToMaster('addTextToUserAction', `Пропет звук с частотй ниже чем ${MIN_ANALYSE_FREQUENCY}. Такие частоты мы не анализируем.`);
        }
        console.log(`Sung sound with frequencies lower than ${MIN_ANALYSE_FREQUENCY}. We do not analyze such frequencies.`);
        return 0;
    }
    if (pitch > MAX_ANALYSE_FREQUENCY) {
        if (!isStudent) {
            addTextToUserAction(`Пропет звук с частотй выше чем ${MAX_ANALYSE_FREQUENCY}. Такие частоты мы не анализируем.`);
        }
        if (isTeacherConnected) {
            sendPlayerCommandToMaster('addTextToUserAction', `Пропет звук с частотй выше чем ${MAX_ANALYSE_FREQUENCY}. Такие частоты мы не анализируем.`);
        }
        console.log(`Sung sound with frequencies higher than ${MAX_ANALYSE_FREQUENCY}. We do not analyze such frequencies.`);
        return 0;
    }
    if (isHomework || isStudent) {
        checkNoteHit(pitch, rms);
    }

    //console.info("analyseAudioData::analyseAudioData() is finished");

    return pitch;
}

/**
 * @param buf
 * @param sampleRate
 * @param rmsLimit
 * @returns {{pitch: number, rms: number}}
 */
function autoCorrelate(buf, sampleRate, rmsLimit)
{
    let SIZE = buf.length;
    let rms = 0;

    for (let i=0;i<SIZE;i++) {
        let val = buf[i];
        rms += val*val;
    }
    rms = Math.sqrt(rms/SIZE);
    if (rms < rmsLimit) { // not enough signal
        return {pitch: -1, rms: rms};
    }

    let r1=0, r2=SIZE-1, thres=0.2;
    for (let i=0; i<SIZE/2; i++)
        if (Math.abs(buf[i])<thres) { r1=i; break; }
    for (let i=1; i<SIZE/2; i++)
        if (Math.abs(buf[SIZE-i])<thres) { r2=SIZE-i; break; }

    buf = buf.slice(r1,r2);
    SIZE = buf.length;

    let c = new Array(SIZE).fill(0);
    for (let i=0; i<SIZE; i++)
        for (let j=0; j<SIZE-i; j++)
            c[i] = c[i] + buf[j]*buf[j+i];

    let d=0; while (c[d]>c[d+1]) d++;
    let maxval=-1, maxpos=-1;
    for (let i=d; i<SIZE; i++) {
        if (c[i] > maxval) {
            maxval = c[i];
            maxpos = i;
        }
    }
    let T0 = maxpos;

    let x1=c[T0-1], x2=c[T0], x3=c[T0+1];
    let a = (x1 + x3 - 2*x2)/2;
    let b = (x3 - x1)/2;
    if (a) T0 = T0 - b/(2*a);

    return {pitch: sampleRate/T0, rms: rms};
}

/**
 * @param pitch
 * @param rms
 */
function checkNoteHit(pitch, rms)
{
    //let $pitch_deviation = $('#pitch-deviation');
    //let p_d = $pitch_deviation.length && $pitch_deviation.val() ? parseFloat($pitch_deviation.val()) / 100 : 1;
    //console.log('checkNoteHit::p_d = ', p_d);

    /**/
    let {noteIndex, note, noteName, noteCode} = getNote(pitch);
    if (noteIndex < 0) return;

    if (!isStudent) {
        addTextToUserAction(`Пропета нота "${noteName}", ее код "${noteCode}", ее частота "${pitch}, ee индекс ${noteIndex}"`);
    }
    if (isTeacherConnected) {
        sendPlayerCommandToMaster('addTextToUserAction', `Пропета нота "${noteName}", ее код "${noteCode}", ее частота "${pitch}, ee индекс ${noteIndex}"`);
    }
    console.log(`checkNoteHit::The note "${noteName}" is sung, its code is "${noteCode}", its frequency is "${pitch}", its index is "${noteIndex}"`);

    /**/
    for (let i = 0; i < targetPitches.length; ) {
        let target = targetPitches[i];
        console.info("checkNoteHit::pitch: ", pitch, " target: ", target.pitch, ", noteIndex: ", noteIndex, " target: ", target.noteIndex, ", rms: ", rms);
        //console.info('checkNoteHit::target.pitch - % = ', target.pitch - (target.pitch * p_d));
        //console.info('checkNoteHit::target.pitch + % = ', target.pitch + (target.pitch * p_d));
        //if ((pitch >= target.pitch - (target.pitch * p_d)) && (pitch <= target.pitch + (target.pitch * p_d))) {
        if (target.noteIndex == noteIndex) {
            targetPitches.splice(i, 1);
            console.log('checkNoteHit::noteHit');
            markNote(target.position, false, true);
            notesHit += 1;
            if (wasClose) {
                notesClose -= 1;
            }
            if (note < lowestNote || lowestNote == -1) {
                lowestNote = note;
            }
            if (note > highestNote || highestNote == -1) {
                highestNote = note;
            }

            notesHitArray.push(target.position);

            //wasClose = true;
            //analyseAudioTimeout = -1;
            //clearTimeout(analyseAudioTimeout);

            //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            sendPlayerCommandToMaster('noteHit', target.position);
            //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

            break;
        } else if (!wasClose && (target.noteIndex == noteIndex + 1 || target.noteIndex == noteIndex - 1 ||
            (target.noteIndex == 11 && noteIndex == 0) || (target.noteIndex == 0 && noteIndex == 11))) {
            wasClose = true;
            notesClose += 1;
            console.log('checkNoteHit::noteClose');
            markNote(target.position, true, false);

            notesCloseArray.push(target.position);

            //targetPitches.splice(i, 1);
            //analyseAudioTimeout = -1;
            //clearTimeout(analyseAudioTimeout);

            //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            sendPlayerCommandToMaster('noteClose', target.position);
            //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

            if (note < lowestNote || lowestNote == -1) {
                lowestNote = note;
            }
            if (note > highestNote || highestNote == -1) {
                highestNote = note;
            }
            break;
        } else {
            i++;
        }
        //} else {
        //    i++;
        //}
    }

    /**/
    sendPlayerCommandToMaster('synchronizeStudentResultData', {
        notesPlayed: notesPlayed,
        notesHit: notesHit,
        notesClose: notesClose,
        lowestNote: lowestNote,
        highestNote: highestNote
    });
}

/**
 * @param position
 * @param noteClose
 * @param noteHit
 */
function markNote(position, noteClose, noteHit)
{
    //console.info(`markNote::markNote(${position}) is started ++filter-here`);

    //position = position - 1;

    let color = targetColor;
    let elem = markedNotesElements.get(position);
    if (elem) {
        //noteElementsContainer.removeChild(elem);
    }
    if (noteHit) {
        color = targetHitColor;
    } else if (noteClose) {
        color = targetCloseColor;
    }

    let cursorParameters = cursorsParameters.get(position);
    //console.log(`markNote::cursorParameters: ++filter-here`, cursorParameters);
    if (!cursorParameters) return;
    let { cursorTop, cursorLeft, cursorHeight, cursorWidth } = cursorParameters;

    let note_id = `note-position-${position}`;
    //let $note_el = $(`#${note_id}`);
    //if ($note_el.length) {
    //    $note_el.remove();
    //}
    //let noteElement = document.createElement("div");
    let noteElement = document.createElement("img");
    noteElement.style.position = "absolute";
    //noteElement.style.zIndex = noteHit ? '-12' : '-13';
    noteElement.style.top = cursorTop;
    noteElement.style.left = cursorLeft;
    noteElement.style.opacity = 0;
    noteElement.style.transition = "all 0.1s";
    noteElement.style.width = cursorWidth + 'px';
    noteElement.style.height = cursorHeight + 'px';
    noteElement.className = noteHit ? 'note-hit' : 'note-close';
    noteElement.height = cursorHeight;
    noteElement.width = cursorWidth;
    noteElement.id = note_id;

    const c = document.createElement("canvas");
    c.width = cursorWidth;
    c.height = 1;
    const ctx = c.getContext("2d");
    ctx.globalAlpha = 1;
    ctx.fillStyle = color;
    ctx.fillRect(0, 0, cursorWidth, cursorHeight);
    noteElement.src = c.toDataURL("image/png");

    noteElementsContainer.appendChild(noteElement);
    markedNotesElements.set(position, noteElement);
    window.setTimeout(() => { noteElement.style.opacity = 1; }, 0);

    //console.info("markNote::markNote() is finished");
}

/**
 * @param command
 * @param data
 */
function casePlayerCommandForTeacher(command, data)
{
    switch(command) {
        case 'noteHit':
            noteHit(data);
            break;
        case 'noteClose':
            noteClose(data);
            break;
        case 'addTextToUserAction':
            addTextToUserAction(data);
            break;
        case 'synchronizeStudentResultData':
            synchronizeStudentResultData(data);
            break;
        default:
            console.error("casePlayerCommandForTeacher::Unknown command for teacher: ", command);
            break;
    }

}

/**
 * @param target_position
 */
function noteHit(target_position)
{
    //console.info(`noteHit::noteHit(${target_position}) is started ++filter-here`);
    markNote(target_position, false, true);
    //console.info("noteHit::noteHit() is finished");
}

/**
 * @param target_position
 */
function noteClose(target_position)
{
    //console.info(`noteClose::noteClose(${target_position}) is started ++filter-here`);
    markNote(target_position, true, false);
    //console.info("noteClose::noteClose() is finished");
}





/***************************************************** *******/

/**
 *
 */
function resetCursor()
{
    let start = 1;
    audioPlayer.cursor.reset();
    for (let i = start; i < audioPlayer.currentIterationStep; ++i) {
        audioPlayer.cursor.next();
    }
    followCursor();
}

/**
 * @param position
 * @returns {*}
 */
function slaveSynchronizePlayerPosition(position)
{

    if (typeof position == 'undefined')
    {
        position = 0;
    }

    console.log(`slaveSynchronizePlayerPosition::position=`, position);

    position = parseInt(position);
    if (position < 0) {
        position = 0;
    }
    let test_max = audioPlayer.iterationSteps;
    if (position > test_max) {
        position = test_max;
    }

    try {
        audioPlayer.currentIterationStep = position;
        audioPlayer.scheduler.reset();
        audioPlayer.scheduler.setIterationStep(audioPlayer.currentIterationStep);
        resetCursor();
    } catch (error) {
        console.log(`slaveSynchronizePlayerPosition::error: `, error.message);
    }
}

/**
 *
 */
function playPlayer()
{
    console.info("playPlayer");

    /**/
    //sendPlayerCommandToSlave('slaveSynchronizePlayerPosition', audioPlayer.currentIterationStep);

    /**/
    //$system_action.show().html('Играем ноту...');

    /* очистка массива сыгранных нот перед началом проигрывания */
    targetPitches.clear();

    /* если пришли в конец нот и это не пауза, то остановка плеера */
    //if ((audioPlayer.currentIterationStep >= audioPlayer.iterationSteps) || (audioPlayer.state != "PAUSED")) {
    //    audioPlayer.currentIterationStep = 0;
    //    audioPlayer.cursor.reset();
    //    audioPlayer.scheduler.reset();
    //    audioPlayer.scheduler.setIterationStep(0);
    //    audioPlayer.clearTimeouts();
    //    targetPitches.clear();
    //    stopPlayer(true);
    //}


    /**/
    //if (audioPlayer.state == "PAUSED") {
    //    let test_count = ($count_notes_to_play.length) ? parseInt($count_notes_to_play.val()) : slaveCountNotesToPlay;
    //    if (test_count == -1 && audioPlayer.currentIterationStep > 0) {
    //        audioPlayer.currentIterationStep -= 1;
    //    } else {
    //        //audioPlayer.currentIterationStep += 1;
    //    }
    //    audioPlayer.scheduler.reset();
    //    audioPlayer.scheduler.setIterationStep(audioPlayer.currentIterationStep);
    //    resetCursor();
    //}


    if (audioPlayer.state === "STOPPED" || audioPlayer.state === "PAUSED") {

        audioPlayerNotesToPlay = ($count_notes_to_play.length) ? parseInt($count_notes_to_play.val()) : slaveCountNotesToPlay;
        console.info(`playPlayer::audioPlayerNotesToPlay = ${audioPlayerNotesToPlay}`);

        if (audioPlayerNotesToPlay == 1) {
            audioPlayer.setBpm(50);
        } else {
            setApBpm();
        }

        audioPlayer.play();
    }

    sendPlayerCommandToSlave('playPlayer', null);
}

/**
 * @param {boolean} internal
 */
function pausePlayer(internal=false)
{
    console.info("pausePlayer");

    let storeCurrentIterationStep = audioPlayer.currentIterationStep;

    audioPlayer.scheduler.reset();
    audioPlayer.scheduler.setIterationStep(audioPlayer.currentIterationStep);
    resetCursor();
    audioPlayer.clearTimeouts();
    //targetPitches.clear();
    //wasClose = false;
    audioPlayer.setState("STOPPED"); //audioPlayer.setState("STOPPED");
    //audioPlayer.pause();

    audioPlayer.scheduler.reset();
    audioPlayer.scheduler.setIterationStep(storeCurrentIterationStep);
    resetCursor();
    audioPlayer.setState("PAUSED");

    if (targetPitchesClearTimeout) {
        clearTimeout(targetPitchesClearTimeout);
    }

    //let WaitNote = getTtlWaitVoiceFromStudent();
    //console.log('pausePlayer::WaitNote = ', WaitNote);
    //
    //targetPitchesClearTimeout = window.setTimeout(() => {
    //    targetPitches.clear();
    //    wasClose = false;
    //}, WaitNote);

    setApBpm();

    sendPlayerCommandToMaster('synchronizeStudentResultData', {
        notesPlayed: notesPlayed,
        notesHit: notesHit,
        notesClose: notesClose,
        lowestNote: lowestNote,
        highestNote: highestNote
    });

    if (!internal) {
        sendPlayerCommandToSlave('pausePlayer', null);

        //sendPlayerCommandToSlave('slaveSynchronizePlayerPosition', audioPlayer.currentIterationStep);
    }
}

/**
 * @param {boolean} internal
 */
function stopPlayer(internal=false)
{
    console.info("stopPlayer");

    if (audioPlayer) {
        if (audioPlayer.state === "PLAYING" || audioPlayer.state === "PAUSED") {
            audioPlayer.stop();
        }
        osmd.cursor.show();
    }

    if (!internal) {
        sendPlayerCommandToSlave('stopPlayer', null);

        //sendPlayerCommandToSlave('slaveSynchronizePlayerPosition', audioPlayer.currentIterationStep);
    }
}

/**
 *
 */
function replayPlayer()
{
    console.info("replayPlayer");

    if (audioPlayer.state === "STOPPED" || audioPlayer.state === "PAUSED") {
        console.info("replayPlayer");

        let test_count = ($count_notes_to_play.length) ? parseInt($count_notes_to_play.val()) : slaveCountNotesToPlay;

        if (test_count < 0) {

            /* если коичество проигрываемых нот установлено как ALL (играть весь нотный ряд) */
            stopPlayer(true);

            audioPlayer.scheduler.reset();
            audioPlayer.scheduler.setIterationStep(0);
            audioPlayer.clearTimeouts();
            targetPitches.clear();

        } else {

            /* если задано проиграть определенное количество нот */
            let rewindTo = audioPlayer.currentIterationStep - test_count;
            console.log(`replayPlayer::rewindTo = ${rewindTo}`);
            if (rewindTo <= 0) {
                stopPlayer(true);
            } else {
                audioPlayer.currentIterationStep = rewindTo;
                audioPlayer.scheduler.reset();
                audioPlayer.scheduler.setIterationStep(audioPlayer.currentIterationStep);
                resetCursor();
            }

        }

        window.setTimeout(playPlayer, 200);
    }

    //sendPlayerCommandToSlave('replayPlayer', null);
    sendPlayerCommandToSlave('slaveSynchronizePlayerPosition', audioPlayer.currentIterationStep);
}

/**
 *
 */
function nextNote()
{
    console.info('nextNote');

    //console.log('nextNote:: markedNotesElements=', markedNotesElements);
    if (audioPlayer.state === "STOPPED" || audioPlayer.state === "PAUSED") {
        let test_max = audioPlayer.iterationSteps;
        if (audioPlayer.currentIterationStep >= test_max) {
            return;
        }
        audioPlayer.currentIterationStep += 1;
        audioPlayer.scheduler.reset();
        audioPlayer.scheduler.setIterationStep(audioPlayer.currentIterationStep);
        //console.log(`nextNote::audioPlayer.currentIterationStep = ${audioPlayer.currentIterationStep}`);
        //console.log(`nextNote::osmd.cursor.Iterator.currentVoiceEntryIndex = `, osmd.cursor.Iterator.currentVoiceEntryIndex)
        resetCursor();
    }

    $system_action.show().html('Перешли к следующей ноте.');

    sendPlayerCommandToSlave('nextNote', null);
    sendPlayerCommandToSlave('slaveSynchronizePlayerPosition', audioPlayer.currentIterationStep);
}

/**
 *
 */
function prevNote()
{
    console.info('prevNote');

    if (audioPlayer.state === "STOPPED" || audioPlayer.state === "PAUSED") {
        if (audioPlayer.currentIterationStep <= 0) {
            return;
        }
        audioPlayer.currentIterationStep -= 1;
        audioPlayer.scheduler.reset();
        audioPlayer.scheduler.setIterationStep(audioPlayer.currentIterationStep);
        //console.log(`prevNote::audioPlayer.currentIterationStep = ${audioPlayer.currentIterationStep}`);
        //console.log(`nextNote::osmd.cursor.Iterator.currentVoiceEntryIndex = `, osmd.cursor.Iterator.currentVoiceEntryIndex)
        resetCursor();
    }

    $system_action.show().html('Перешли к предыдущей ноте.');

    sendPlayerCommandToSlave('prevNote', null);
    sendPlayerCommandToSlave('slaveSynchronizePlayerPosition', audioPlayer.currentIterationStep);
}

/**
 *
 */
function firstNote()
{
    console.info('firstNote');

    if (audioPlayer.state === "STOPPED" || audioPlayer.state === "PAUSED") {
        //stopPlayer(true);
        audioPlayer.currentIterationStep = 0;
        audioPlayer.scheduler.reset();
        audioPlayer.scheduler.setIterationStep(audioPlayer.currentIterationStep);
        //console.log(`firstNote::audioPlayer.currentIterationStep = ${audioPlayer.currentIterationStep}`);
        osmd.cursor.show();
        resetCursor();
    }

    $system_action.show().html('Перешли к первой ноте.');

    sendPlayerCommandToSlave('firstNote', null);
    //sendPlayerCommandToSlave('slaveSynchronizePlayerPosition', audioPlayer.currentIterationStep);
}

/**
 *
 */
function lastNote()
{
    console.info('lastNote');

    if (audioPlayer.state === "STOPPED" || audioPlayer.state === "PAUSED") {
        audioPlayer.currentIterationStep = audioPlayer.iterationSteps;
        audioPlayer.scheduler.reset();
        audioPlayer.scheduler.setIterationStep(audioPlayer.currentIterationStep);
        //console.log(`lastNote::audioPlayer.currentIterationStep = ${audioPlayer.currentIterationStep}`);
        resetCursor();
    }

    $system_action.show().html('Перешли к последней ноте.');

    sendPlayerCommandToSlave('lastNote', null);
    sendPlayerCommandToSlave('slaveSynchronizePlayerPosition', audioPlayer.currentIterationStep);
}

/**
 *
 */
function hideLoadingMessage()
{
    $("#loading-preset").hide();
}

/**
 * @param {string} state
 */
function onApStateChange(state)
{
    if (state == "PLAYING") {
        $btn_play.hide();
        $btn_pause.show();
        $btn_replay.addClass('disabled');
        $btn_next.addClass('disabled');
        $btn_prev.addClass('disabled');
        $btn_last.addClass('disabled');
        $btn_first.addClass('disabled');
        $('.select2-selection.select2-selection--single').addClass('disabled');
        //$('.select2-results__options').addClass('disabled');
    } else {
        $btn_play.show();
        $btn_pause.hide();
        $btn_replay.removeClass('disabled');
        $btn_next.removeClass('disabled');
        $btn_prev.removeClass('disabled');
        $btn_last.removeClass('disabled');
        $btn_first.removeClass('disabled');
        $('.select2-selection.select2-selection--single').removeClass('disabled');
        //$('.select2-results__options').addClass('disabled');

        if (isStudent || isHomework) {

            //let WaitNote = getTtlWaitVoiceFromStudent();
            //console.log('onApStateChange::WaitNote = ', WaitNote);
            //
            //targetPitchesClearTimeout = window.setTimeout(() => {
            //    targetPitches.clear();
            //    wasClose = false;
            //}, WaitNote);
        }

    }
}

/**
 *
 */
function setApVolume(needCalibrate = true)
{
    console.info("setApVolume");

    volumeAmplifier = Number($osmd_volume.val());
    window.localStorage.setItem("volumeAmplifier", volumeAmplifier);

    console.log(`setApVolume::value: ${volumeAmplifier}`);

    if (audioPlayer) {
        for (let p of audioPlayer.instrumentPlayer.players.values()) {
            //console.log('setApVolume:: p=', p);
            p.out.gain.value = volumeAmplifier;
        }
    }

    if (isStudent || isHomework) {
        //if (isHomework) {
        if (needCalibrate) {
            calibrate();
        }
    }
}

/**
 * @param {int|null} val
 */
function setApBpm(val=null)
{
    console.info("setApBpm");

    if (val === null) {
        osmdApBpm = parseInt($osmd_bpm.val());
    } else {
        osmdApBpm = val;
    }

    console.log(`setApBpm::value: ${osmdApBpm}`);

    if (audioPlayer) { audioPlayer.setBpm(osmdApBpm); }

    sendPlayerCommandToSlave('setApBpm', osmdApBpm);
}

/**
 * @param {string} playerCommand
 * @param {json|string|int|number} additional_data
 */
function sendPlayerCommandToSlave(playerCommand, additional_data)
{
    if (typeof sendEndpointTextMessage == 'function') {
        if (!isStudent && isStudentConnected) {
            try {
                sendEndpointTextMessage(JSON.stringify({
                    playerCommand: playerCommand,
                    additional_data: additional_data
                }));
            } catch (error) {
                console.log(`sendPlayerCommandToSlave::sendEndpointTextMessage: ${error.message}`);
            }
        }
    }
}

/**
 * @param {string} playerCommand
 * @param {json|string|int|number} additional_data
 */
function sendPlayerCommandToMaster(playerCommand, additional_data)
{
    if (isStudent && isTeacherConnected) {
        if (typeof sendEndpointTextMessage == 'function') {
            try {
                sendEndpointTextMessage(JSON.stringify({
                    playerCommand: playerCommand,
                    additional_data: additional_data
                }));
            } catch (error) {
                console.log(`sendPlayerCommandToMaster::sendEndpointTextMessage: ${error.message}`);
            }
        }
    }
}

/**
 * @param {int} count
 */
function setSlaveCountNotesToPlay(count)
{
    //console.info("setSlaveCountNotesToPlay::setSlaveCountNotesToPlay() is started");
    slaveCountNotesToPlay = parseInt(count);
    //console.info("setSlaveCountNotesToPlay::setSlaveCountNotesToPlay() is finished");
}

/**
 * @param text
 */
function addTextToUserAction(text)
{
    let current_date = new Date();
    text =
        //current_date.getDay() + "/" + current_date.getMonth() + "/" + current_date.getFullYear() +
        //" @ " +
        current_date.getHours() + ":" + current_date.getMinutes() + ":" + current_date.getSeconds() +
        " => " + text;
    let content = $.trim($user_action.html());
    if (content.length) { content += "<br />"; }
    content += text;
    $user_action.show().html(content);
    //$user_action.animate({ scrollTop: $user_action.prop("scrollHeight")}, 500);
    $user_action.scrollTop($user_action.prop("scrollHeight"));
}

/**
 *
 */
function registerButtonEvents()
{
    if (!btnRegistered) {

        $osmd_volume = $('#osmd-ap-volume');
        $osmd_bpm = $('#osmd-ap-bpm');
        $btn_play = $('#btn-play');
        $btn_pause = $('#btn-pause');
        $count_notes_to_play = $('#count_notes_to_play');
        $btn_replay = $('#btn-replay');
        $btn_next = $('#btn-next');
        $btn_prev = $('#btn-prev');
        $btn_last = $('#btn-last');
        $btn_first = $('#btn-first');

        $(document).on('click scroll change mouseover mouseout', 'body', function(e) {
            if (!audioCtxInitialized) {
                //audioCtxInitialized = true;
                //console.log('registerButtonEvents:: document.event', e);
                //initAudioContexts();
            }
        });

        $(document).on('click', '#btn-calibrate', function() {
            playOneForCalibrate(false);
        });

        $(document).on('change', '#count_notes_to_play', function () {
            sendPlayerCommandToSlave('setSlaveCountNotesToPlay', $(this).val());
        });
        $(document).on('change', '#rms-factor', function(){
            setApVolume();
        });
        $(document).on('click', '#btn-play', function () {
            playPlayer();
        });
        $(document).on('click', '#btn-pause', function () {
            pausePlayer(false);
        });
        $(document).on('click', '#btn-stop', function () {
            stopPlayer();
        });
        $(document).on('click', '#btn-replay', function () {
            replayPlayer()
        });
        $(document).on('click', '#btn-next', function () {
            nextNote();
        });
        $(document).on('click', '#btn-prev', function () {
            prevNote();
        });
        $(document).on('click', '#btn-first', function () {
            firstNote();
        });
        $(document).on('click', '#btn-last', function () {
            lastNote();
        });
        $(document).on('change', '#osmd-ap-volume', function () {
            setApVolume();
        });
        $(document).on('change', '#osmd-ap-bpm', function () {
            setApBpm();
        });
    }
    btnRegistered = true;
}

/**
 *
 */
$(document).ready(function() {
    let $present_info_content = $('#present-info-content');
    isStudent = (parseInt($present_info_content.data('is-student')) == 1);
    isTeacher = !isStudent;
    isHomework = (parseInt($present_info_content.data('is-home-work')) == 1);

    if (isHomework) {
        isTeacher = false;
        isStudent = false;
    }

    volumeAmplifier = volumeAmplifier == null
        ? ((isStudent || isHomework) ? 10 : 3)
        : Number(volumeAmplifier);

    $osmd_volume.val(volumeAmplifier);
});
