<!DOCTYPE html>
<html lang="ru" class="no-js">
<head>
    <meta name="viewport" content="width=device-width,user-scalable=1,initial-scale=1.0,minimum-scale=1.0" />
    <meta property="og:title" content="Виртуальное пианино – Играть на пианино онлайн | Smart Sing" />
    <meta property="og:description" content="Виртуальное пианино для преподавателей музыки и студентов. Визуализируйте ноты, интервалы, аккорды и гаммы и играйте на пианино с помощью компьютерной клавиатуры." />
    <meta property="og:site_name" content="Smart Sing" />


    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="description" content="Виртуальное пианино для преподавателей музыки и студентов. Визуализируйте ноты, интервалы, аккорды и гаммы и играйте на пианино с помощью компьютерной клавиатуры." />
    <title>Виртуальное пианино – Играть на пианино онлайн | Smart Sing</title>

    <link href="css/googleapis.css" rel="stylesheet">
    <link rel="stylesheet" href="css/screen.min.css">
    <link rel="icon" type="image/png" href="img/favicon.png" />

    <script src="js/jquery.min.js"></script>
    <script data-off-src="js/core.js"></script>
    <script data-off-src="js/keepalive.js" defer="defer"></script>
    <script data-off-src="js/bootstrap.min.js"></script>
    <script defer data-off-src="js/opentip-native-excanvas.min.js"></script>
    <script defer data-off-src="js/app.min.js"></script>
    <style>
        .wrapper {
            padding-top: 0px;
        }
        .black-key b {
            color: #fff;
            font-weight: 300;
        }
        .white-key b {
            color: #000;
            font-weight: 300;
        }
        .container {
            margin-left: auto;
            margin-right: 0;
        }
    </style>
</head>
<body class="ru-RU">

<div class="fix__content">
    <div class="wrapper">
        <div class="content">
            <div class="container">
                <div class="row flex__row">


                    <div class="content__right col-9-m col-">
                        <div id="system-message-container">
                        </div>
                        <article class="default__content item-page " itemscope itemtype="https://schema.org/Article">
                            <meta itemprop="inLanguage" content="ru" />

                            <!--
                            <div class="page-header default__page__header">
                                <h1 class="h1 default__page__title" itemprop="name">
                                    Виртуальное пианино </h1>
                            </div>
                            -->

                            <div itemprop="articleBody">
                                <div class="default__page__content">
                                    <link rel="stylesheet" href="css/piano.min.css">
                                    <div id="piano">
                                        <div class="btn-group-piano">
                                            <button class="mark"></button>
                                            <button class="hideNotes"></button>
                                            <button class="hideKeyNotes"></button>
                                            <button class="playAll hidden"></button>
                                            <button class="clear btn-reset hidden"></button>
                                        </div>
                                        <div class="piano-wrapper">
                                            <ul class="piano" style="font-size:90%">
                                                <li class="key">
                                                    <span class="white-key" data-key="90" data-note="1c"><i></i><b>Z</b></span>
                                                    <span class="black-key" data-key="83" data-note="1cis"><i></i><b>S</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="88" data-note="1d"><i></i><b>X</b></span>
                                                    <span class="black-key" data-key="68" data-note="1dis"><i></i><b>D</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="67" data-note="1e"><i></i><b>C</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="81" data-note="1f"><i></i><b>Q</b></span>
                                                    <span class="black-key" data-key="50" data-note="1fis"><i></i><b>2</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="87" data-note="1g"><i></i><b>W</b></span>
                                                    <span class="black-key" data-key="51" data-note="1gis"><i></i><b>3</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="69" data-note="2a"><i></i><b>E</b></span>
                                                    <span class="black-key" data-key="52" data-note="2ais"><i></i><b>4</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="82" data-note="2b"><i></i><b>R</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="84" data-note="2c"><i></i><b>T</b></span>
                                                    <span class="black-key" data-key="54" data-note="2cis"><i></i><b>6</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="89" data-note="2d"><i></i><b>Y</b></span>
                                                    <span class="black-key" data-key="55" data-note="2dis"><i></i><b>7</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="85" data-note="2e"><i></i><b>U</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="73" data-note="2f"><i></i><b>I</b></span>
                                                    <span class="black-key" data-key="57" data-note="2fis"><i></i><b>9</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="79" data-note="2g"><i></i><b>O</b></span>
                                                    <span class="black-key" data-key="48" data-note="2gis"><i></i><b>0</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="80" data-note="3a"><i></i><b>P</b></span>
                                                    <span class="black-key" data-key="187" data-additional-key="189" data-note="3ais"><i></i><b>-</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="219" data-note="3b"><i></i><b>[</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="221" data-note="3c"><i></i><b>]</b></span>
                                                    <span class="black-key" data-key="70" data-note="3cis"><i></i><b>F</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="86" data-note="3d"><i></i><b>V</b></span>
                                                    <span class="black-key" data-key="71" data-note="3dis"><i></i><b>G</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="66" data-note="3e"><i></i><b>B</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="78" data-note="3f"><i></i><b>N</b></span>
                                                    <span class="black-key" data-key="74" data-note="3fis"><i></i><b>J</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="77" data-note="3g"><i></i><b>M</b></span>
                                                    <span class="black-key" data-key="75" data-note="3gis"><i></i><b>K</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="188" data-note="4a"><i></i><b>&lt;</b></span>
                                                    <span class="black-key" data-key="76" data-note="4ais"><i></i><b>L</b></span>
                                                </li>
                                                <li class="key">
                                                    <span class="white-key" data-key="190" data-note="4b"><i></i><b>&gt;</b></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <script src="js/howler.core.min.js"></script>
                                    <script>
                                        // PIANO TRANSLATION PART
                                        var PIANO_TRANSLATION = {
                                            url: 'pianino?',
                                            buttonNames: {
                                                markNotes: 'Отметить',
                                                showHideNotes: {
                                                    show: 'Показать названия нот',
                                                    hide: 'Скрыть названия нот'
                                                },
                                                showHideKeyNotes: {
                                                    show: 'Показать клавиши вызова',
                                                    hide: 'Скрыть клавиши вызова'
                                                },
                                                playNotes: 'Играть',
                                                reset: 'Очистить'
                                            },
                                            noteNames: {
                                                c: 'До',
                                                cis:
                                                '<span style="font-size:90%">До<span class="piano-accidental">♯</span></span>' +
                                                '<span style="font-size:90%">Ре<span class="piano-accidental">♭</span></span>',
                                                d: 'Ре',
                                                dis:
                                                '<span style="font-size:90%">Ре<span class="piano-accidental">♯</span></span>' +
                                                '<span style="font-size:90%">Ми<span class="piano-accidental">♭</span></span>',
                                                e: 'Ми',
                                                f: 'Фа',
                                                fis:
                                                '<span style="font-size:90%">Фа<span class="piano-accidental">♯</span></span>' +
                                                '<span style="font-size:80%">Соль<span class="piano-accidental">♭</span></span>',
                                                g: 'Соль',
                                                gis:
                                                '<span style="font-size:80%">Соль<span class="piano-accidental">♯</span></span>' +
                                                '<span style="font-size:90%">Ля<span class="piano-accidental">♭</span></span>',
                                                a: 'Ля',
                                                ais:
                                                '<span style="font-size:90%">Ля<span class="piano-accidental">♯</span></span>' +
                                                '<span style="font-size:90%">Си<span class="piano-accidental">♭</span></span>',
                                                b: 'Си',
                                            },
                                            keys: {
                                                '1c': [90],
                                                '1cis': [83],
                                                '1d': [88],
                                                '1dis': [68],
                                                '1e': [67],
                                                '1f': [81],
                                                '1fis': [50],
                                                '1g': [87],
                                                '1gis': [51],
                                                '2a': [69],
                                                '2ais': [52],
                                                '2b': [82],
                                                '2c': [84],
                                                '2cis': [54],
                                                '2d': [89],
                                                '2dis': [55],
                                                '2e': [85],
                                                '2f': [73],
                                                '2fis': [57],
                                                '2g': [79],
                                                '2gis': [48],
                                                '3a': [80],
                                                '3ais': [187, 189],
                                                '3b': [219],
                                                '3c': [221],
                                                '3cis': [70],
                                                '3d': [86],
                                                '3dis': [71],
                                                '3e': [66],
                                                '3f': [78],
                                                '3fis': [74],
                                                '3g': [77],
                                                '3gis': [75],
                                                '4a': [188],
                                                '4ais': [76],
                                                '4b': [190]
                                            }
                                        }
                                    </script>
                                    <script src="js/piano.min.js"></script>

                                </div>
                            </div>

                        </article>
                        <div class="after__content">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>