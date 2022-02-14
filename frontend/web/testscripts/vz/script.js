var body, num, array, width, context, logo, myElements, analyser, src, height, n;

body = document.querySelector('body');

n = 0;

num = 32;

array = new Uint8Array(num*2);

width = 10;

function test() {

    //alert(11);
    if(context) return;

    //body.querySelector('a').remove();

    //for(var i = 0 ; i < num ; i++){
    //    logo = document.createElement('div');
    //    logo.className = 'logo';
    //    logo.style.background = 'red';
    //    logo.style.minWidth = width+'px';
    //    body.appendChild(logo);
    //}
    //
    //myElements = document.getElementsByClassName('logo');
    let AudioContext = window.AudioContext // Default
        || window.webkitAudioContext // Safari and old versions of Chrome
        || false;

    if (AudioContext) {

        context = new AudioContext();
        analyser = context.createAnalyser();

        navigator.mediaDevices.getUserMedia({
            audio: true
        }).then(stream => {
            alert('getUserMedia ok');
            src = context.createMediaStreamSource(stream);
            src.connect(analyser);
            loop();
        }).catch(error => {
            alert(error + '\r\n\ Отклонено. Страница будет обновлена!');
            location.reload();
        });

    } else {
        alert('No AudioContext present in this browser');
    }
}

function loop() {
    //window.requestAnimationFrame(loop);
    //analyser.getByteFrequencyData(array);
    analyser.getByteTimeDomainData(array);
    for(var i = 0 ; i < num ; i++){
        height = array[i+num];

        document.getElementById('data-voice').innerHTML = height;
        //myElements[i].style.minHeight = height+'px';
        //myElements[i].style.opacity = 0.008*height;
    }
    //alert(n);
    n++;
    setTimeout(loop, 100);
}