let IS_DEBUG = true;
let STORE_JS_LOGS = true;
let logBufferLength = 10000;
let storeLogContainer = '';

/*** ++ старое переопределение консоль-лог */
//if (!IS_DEBUG) {
//    if(!window.console) window.console = {};
//    var methods = ["log", "debug", "warn", "info", "error", "trace"];
//    for(var i=0;i<methods.length;i++){
//        console[methods[i]] = function(){};
//    }
//}
/*** -- старое переопределение консоль-лог */

/*** ++ переопределение консоль-лог */
console.getLine = function(console_log_type='log') {
    function getError()
    {
        try { throw new Error() }
        catch(err) { return err }
    }
    let full = getError().stack;
    let file = '';
    let file_name = '';
    let line = '';

    /* попытка вытащить полный путь файла где произошел вызов логирования */
    try {
        let reg = new RegExp('Error.*console\.' + console_log_type + '', 'gis');
        file = full
            .replace(reg, '')
            .replace(/\(.*\)\s/, '')
            .match(/\(.*\)\s/)[0]
            .replace(/[\(|\)]/gi, '')
            .replace(/http:\/\/|https:\/\//, '');

    } catch (e) {

        /* если не удалась, то попробуем по другой схеме вытащить этот полный путь файла */
        try {
            let reg = new RegExp('Error.*console\.' + console_log_type + '', 'gis');
            file = full
                .replace(reg, '')
                .replace(/\(.*\)\s/, '')
            //.match(/at.*at/)[0];
            let tmp = file.split('at');
            let l = tmp.length;
            if (l > 0) {
                file = tmp[l - 1];
            }
            file = file.match(/\(.*\)/)[0];

        } catch (e2) {
            //alert('fail get full path');
        }

    }

    /* попытка вытащить уже само имя файла и номер строки где был вызов */
    try {
        file_name = file;
        let tmp = file.split('/');
        let l = tmp.length;
        if (l > 0) {
            file_name = tmp[l - 1];
        }
        line = parseInt(file_name.replace(/[\(|\)]/g, '').match(/:(\d+)(:\d+)?[\n]?$/gi)[0].substr(1));
    } catch (e3) {
        //alert('fail get name and line');
    }

    return {
        full: full,
        file: file,
        name: file_name.replace(/\s*/gi, ''),
        line: line
    };
};
backupconsolelog = console.log.bind(console);
console.log = function() {
    let { file, name, line } = console.getLine('log');
    let array = [].slice.call(arguments, 0);
    array.unshift(`[At line <${line}> in file <${name}>]::`);
    collectLogs(array);
    if (IS_DEBUG) {
        backupconsolelog.apply(this, array);
    }
};
backupconsoleinfo = console.info.bind(console);
console.info = function() {
    let { file, name, line } = console.getLine('info');
    let array = [].slice.call(arguments, 0);
    array.unshift(`[At line <${line}> in file <${name}>]::`);
    collectLogs(array);
    if (IS_DEBUG) {
        backupconsoleinfo.apply(this, array);
    }
};
backupconsoledebug = console.debug.bind(console);
console.debug = function() {
    let { file, name, line } = console.getLine('debug');
    let array = [].slice.call(arguments, 0);
    array.unshift(`[At line <${line}> in file <${name}>]::`);
    collectLogs(array);
    if (IS_DEBUG) {
        backupconsoledebug.apply(this, array);
    }
};
backupconsolewarn = console.warn.bind(console);
console.warn = function() {
    let { file, name, line } = console.getLine('warn');
    let array = [].slice.call(arguments, 0);
    array.unshift(`[At line <${line}> in file <${name}>]::`);
    collectLogs(array);
    if (IS_DEBUG) {
        backupconsolewarn.apply(this, array);
    }
};
backupconsoleerror = console.error.bind(console);
console.error = function() {
    let { file, name, line } = console.getLine('error');
    let array = [].slice.call(arguments, 0);
    array.unshift(`[At line <${line}> in file <${name}>]::`);
    collectLogs(array);
    if (IS_DEBUG) {
        backupconsoleerror.apply(this, array);
    }
};

/**
 * @param arg
 * @returns {*}
 */
function collectLogs(arg)
{
    if (!STORE_JS_LOGS) {
        return void(0);
    }
    // в этой функции собираем логи в переменную-контейнер для последующей отправки на сервер
    // в этой функции нельзя использовать ни в коем случае console.log()
    // и ее собратьев а также функции в которыхприсутствует эти функции,
    // т.к. это будет бесконечная рекурсия
    //let concat_args = '';
    let current_date = new Date();
    let day = current_date.getDate();
    if (day < 10) { day = '0' + day; }
    let month = current_date.getMonth() + 1;
    if (month < 10) { month = '0' + month; }
    let year = current_date.getFullYear();
    let hour = current_date.getHours();
    if (hour < 10) { hour = '0' + hour; }
    let min = current_date.getMinutes();
    if (min < 10) { min = '0' + min; }
    let sec = current_date.getSeconds();
    if (sec < 10) { sec = '0' + sec; }

    let concat_args = `${day}/${month}/${year} @ ${hour}:${min}:${sec} ===>>>"`;

    for (var i=0; i<arg.length; i++) {
        try {
            let tp = (typeof arg[i]);
            if (tp == 'string' || tp == 'integer' || tp == 'int' || tp == 'number') {
                concat_args += " " + arg[i];
            } else {
                concat_args += " " + JSON.stringify(arg[i]);
            }
        } catch (e) {}
    }
    storeLogContainer += concat_args + "\n";

    if (storeLogContainer.length > logBufferLength) {
        sendStoreLogToServer();
        //storeLogContainer = '';
    }
}

/**
 *
 */
function sendStoreLogToServer()
{
    if (!STORE_JS_LOGS) {
        return void(0);
    }
    // эта функия отправляет яваскриптовые консоль-логи логи на сервер
    // в этой функции нельзя использовать ни в коем случае console.log()
    // и ее собратьев а также функции в которыхприсутствует эти функции,
    // т.к. это будет бесконечная рекурсия
    if (typeof ($.ajax) == 'function') {
        if (storeLogContainer.length > 0) {
            //алерт заменить на аякс функцию отправки данных на сервер
            $.ajax({
                type: 'post',
                url: '/site/store-js-console-log',
                is_logger: true,
                data: {
                    logs: storeLogContainer
                },
                dataType: 'json'
            }).done(function (response) {
                storeLogContainer = '';
            }).fail(function (response) {
                storeLogContainer = '';
            });
        }
        storeLogContainer = '';
    }
}

/**
 *
 */
window.addEventListener('beforeunload', function(event) {
    sendStoreLogToServer();
});

/**
 *
 */
window.addEventListener('unload', function(event) {
    sendStoreLogToServer();
});

//console.log('test log', 1, 2);
//console.info('test info');
//console.debug('test debug');
//console.warn('test warn');
//console.error('test error');
/*** -- переопределение консоль-лог */