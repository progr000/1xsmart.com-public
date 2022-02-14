var timeoutOnResize;

async function onResizePage()
{
    await loadMusicXml($('#music-xml-data').data('music-xml')).then(() => {
        playOneForCalibrate(true);
    });
}

$(document).ready(function() {
    /**/
    initAudioContexts();
    loadMusicXml($('#music-xml-data').data('music-xml'));

    /**/
    //$(window).on('resize', function(e) {
    //    saveCurrentPreset();
    //    clearTimeout(timeoutOnResize);
    //    timeoutOnResize = setTimeout(function() {
    //
    //        onResizePage();
    //
    //    }, 400);
    //});
});
