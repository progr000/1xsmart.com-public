'use strict';
//https://webrtc.github.io/samples/src/content/devices/input-output/

/**/
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


/**/
const videoElement = document.querySelector('#video-test');
const audioElement = document.querySelector('#audio-output-test');
const audioInputSelect = document.querySelector('select#audioSource');
const audioOutputSelect = document.querySelector('select#audioOutput');
const videoSelect = document.querySelector('select#videoSource');
const selectors = [audioInputSelect, audioOutputSelect, videoSelect];
audioOutputSelect.disabled = !('sinkId' in HTMLMediaElement.prototype);

/**
 * @param deviceInfos
 */
function gotDevices(deviceInfos)
{
    //console.log(deviceInfos);
    // Handles being called several times to update labels. Preserve values.
    const values = selectors.map(select => select.value);
    selectors.forEach(select => {
        while (select.firstChild) {
            select.removeChild(select.firstChild);
        }
    });
    for (let i = 0; i !== deviceInfos.length; ++i) {
        const deviceInfo = deviceInfos[i];
        const option = document.createElement('option');
        option.value = deviceInfo.deviceId;
        if (deviceInfo.kind === 'audioinput') {
            option.text = deviceInfo.label || `microphone ${audioInputSelect.length + 1}`;
            audioInputSelect.appendChild(option);
        } else if (deviceInfo.kind === 'audiooutput') {
            option.text = deviceInfo.label || `speaker ${audioOutputSelect.length + 1}`;
            audioOutputSelect.appendChild(option);
        } else if (deviceInfo.kind === 'videoinput') {
            option.text = deviceInfo.label || `camera ${videoSelect.length + 1}`;
            videoSelect.appendChild(option);
            const option2 = document.createElement('option');
            //option2.value = deviceInfo.deviceId;
            //option2.text = deviceInfo.label + '(2)';
            //videoSelect.appendChild(option2);
        } else {
            console.log('Some other kind of source/device: ', deviceInfo);
        }
    }
    selectors.forEach((select, selectorIndex) => {
        if (Array.prototype.slice.call(select.childNodes).some(n => n.value === values[selectorIndex])) {
            select.value = values[selectorIndex];
        }
    });
}


/**
 * @param element
 * @param sinkId
 */
function attachSinkId(element, sinkId)
{
    if (typeof element.sinkId !== 'undefined') {
        element.setSinkId(sinkId)
            .then(() => {
                console.log(`Success, audio output device attached: ${sinkId}`);
            })
            .catch(error => {
                let errorMessage = error;
                if (error.name === 'SecurityError') {
                    errorMessage = `You need to use HTTPS for selecting audio output device: ${error}`;
                }
                console.error(errorMessage);
                // Jump back to first output device in the list as it's the default.
                audioOutputSelect.selectedIndex = 0;
            });
    } else {
        console.warn('Browser does not support output device selection.');
    }
}

/**
 *
 */
function changeAudioDestination()
{
    const audioDestination = audioOutputSelect.value;
    attachSinkId(audioElement, audioDestination);
}

function gotVideoStream(stream)
{
    window.stream = stream; // make stream available to console
    videoElement.srcObject = stream;
    //videoElement.src = window.webkitURL.createObjectURL(stream);
    // Refresh button list in case labels have become available
    return navigator.mediaDevices.enumerateDevices();
}

function testVideo()
{
    let $test_card_video_note = $('.test-card-video__note');
    let $btn_test_video = $('#btn-test-video');
    let status = $btn_test_video.attr('data-status');
    //console.log('testVideo::status=', status);

    let onFail = function (error) {
        $btn_test_video.attr('data-status', 'error');
        $btn_test_video.html($btn_test_video.data('start-test'));
        $test_card_video_note.html('Ошибка получения видеопотока с камеры.').addClass('error').show();
        console.log('testVideo::error: ', error.message, error.name);
    };

    let onSuccess = function () {
        $btn_test_video.html($btn_test_video.data('stop-test'));
        $btn_test_video.attr('data-status', 'progress');
        $test_card_video_note.html('Это пример качества видео. Вы должны видеть себя без искажений и задержек.').removeClass('error').show();
    };


    if (status == 'ready' || status == 'error') {

        const audioSource = audioInputSelect.value;
        const videoSource = videoSelect.value;
        const constraints = {
            audio: false, //{deviceId: audioSource ? {exact: audioSource} : undefined},
            video: {deviceId: videoSource ? {exact: videoSource} : undefined}
        };
        navigator.mediaDevices.getUserMedia(constraints)
            .then(gotVideoStream)
            .then(gotDevices)
            .then(onSuccess)
            .catch(onFail);

    } else if (status == 'progress') {

        $test_card_video_note.html('').removeClass('error').hide();
        $btn_test_video.html($btn_test_video.data('start-test'));
        $btn_test_video.attr('data-status', 'ready');
        //linkStream = null;
        const tracks = window.stream.getTracks();
        tracks.forEach(function(track) {
            track.stop();
        });
        videoElement.srcObject = null;

    }
}

/**
 *
 */
function testAudio()
{
    let $test_card_video_note = $('.test-card-audio__note');
    let $btn_test_audio = $('#btn-test-audio');
    let status = $btn_test_audio.attr('data-status');
    //console.log('testVideo::status=', status);

    let onFail = function (error) {
        $btn_test_audio.attr('data-status', 'error');
        $btn_test_audio.html($btn_test_audio.data('start-test'));
        $test_card_video_note.html('Ошибка получения аудиопотока с устройства.').addClass('error').show();
        console.log('testAudio::error: ', error.message);
    };

    let onSuccess = function () {
        $btn_test_audio.html($btn_test_audio.data('stop-test'));
        $btn_test_audio.attr('data-status', 'progress');
        $test_card_video_note.html('Скажите что-нибудь в микрофон и шкала должна изменится.').removeClass('error').show();
    };

    let num = 24;
    let array = new Uint8Array(num*2);
    let context, analyser, src, height;
    let maxVolume = 255;
    let $level = $('#test-audio-output-level');

    let loop = function() {
        analyser.getByteFrequencyData(array);
        let maxHeight = 0;
        //analyser.getByteTimeDomainData(array);
        for(var i = 0 ; i < num ; i++){
            height = array[i+num];
            if (maxHeight < height) { maxHeight = height; }
        }

        let n = 1;
        let curVolume = parseInt(num * maxHeight / maxVolume);
        //console.log(curVolume);
        $level.find('.audio-progress__item').each(function() {
            if (curVolume < n) {
                $(this).removeClass('_filled').removeClass('_active');
            } else {
                $(this).addClass('_filled').removeClass('_active');
            }
            if (curVolume == n) {
                $(this).addClass('_active');
            }
            n++;
        });

        let loopStatus = $btn_test_audio.attr('data-status');
        //console.log('loopStatus=', loopStatus);
        if (loopStatus == 'progress') {
            setTimeout(loop, 100);
        }
    };

    if (status == 'ready' || status == 'error') {

        const audioSource = audioInputSelect.value;
        const videoSource = videoSelect.value;
        const constraints = {
            audio: {deviceId: audioSource ? {exact: audioSource} : undefined},
            video: false//{deviceId: videoSource ? {exact: videoSource} : undefined}
        };

        let AudioContext = window.AudioContext // Default
            || window.webkitAudioContext // Safari and old versions of Chrome
            || false;

        if (AudioContext) {

            context = new AudioContext();
            analyser = context.createAnalyser();

            navigator.mediaDevices.getUserMedia(constraints)
                .then(stream => {
                    window.audioStream = stream; // make stream available to console
                    onSuccess();
                    src = context.createMediaStreamSource(stream);
                    src.connect(analyser);
                    loop();
                })
                .catch(onFail);

        } else {
            console.log('No AudioContext present in this browser');
        }


    } else if (status == 'progress') {

        $test_card_video_note.html('').removeClass('error').hide();
        $btn_test_audio.html($btn_test_audio.data('start-test'));
        $btn_test_audio.attr('data-status', 'ready');
        const tracks = window.audioStream.getTracks();
        tracks.forEach(function(track) {
            track.stop();
        });
        if (context) {
            context.close();
            context = null;
            //analyser.destroy();
            analyser = null;
        }
    }

}



$(document).ready(function() {

    $(document).on('click', '#btn-test-video', function() {

        testVideo();

    });

    navigator.mediaDevices.enumerateDevices()
        .then(gotDevices)
        .catch(function(error) {
            console.log('navigator.mediaDevices.enumerateDevices::error: ', error.message, error.name);
        });

    videoSelect.onchange = function () {
        testVideo();
    };

    $(document).on('click', '#btn-test-audio', function() {

        testAudio();

    });

    audioOutputSelect.onchange = changeAudioDestination;

    $(document).on('click', '#btn-test-audio-output', function() {
        audioElement.play();
    });

});