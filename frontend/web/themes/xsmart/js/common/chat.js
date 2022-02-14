const UPDATE_MESSAGE_INTERVAL = 30; //seconds
const SET_AS_READ_AFTER = 1; //seconds
const SET_NOTIF_AS_READ_AFTER = 2; //seconds

const $chat_popup = $('#chat');
const $trigger_open_chat = $('#trigger-open-chat');
const $chat_users = $('#chat-users');
const $chat_messages = $('#chat-messages');
const $message_for_opponent = $('#message-for-opponent');
const $button_send_message = $('#button-send-message');
const $chat_search = $('#chat-search');
const $btn_open_notify = $('#btn-open-notify');
const $notify_list_popup = $('#notify-list-popup');
const $notify_list_container = $('#notify-list-container');

let ChatWebSocket;

let current_opponent_user_id;
let store_before_search_current_opponent_user_id;
let clear_tmt_get_chat_mes_var;
let clear_tmt_set_as_read_var;
let clear_tmt_set_notif_as_read_var;
let scroll_position_for = [];

let beeper = $(document.createElement('audio')).hide().appendTo('body')[0];
let soundFile = '/assets/xsmart-min/sounds/icq.wav';

// --------------------------- notif ---------------------------
/**
 *
 */
function getNotificationMessages()
{
    if (USER_TYPE != USER_TYPES['TYPE_STUDENT']) {
        return;
    }

    $.ajax({
        type: 'get',
        url: '/user/get-notification-messages',
        dataType: 'json'
    }).done(function (response) {

        if ("status" in response && "data" in response && response.status) {

            /**/
            redrawNotifications(response.data);

            /**/
            testHasNewNotifications(response.data.total_count_new_notifications);


        } else {
            console.log(response);
            //prettyAlert($translate_text_messages.attr('data-msg-15'));
        }

    });
}

/**
 *
 */
function redrawNotifications(data)
{
    $notify_list_container.html(data.notifications)
}

/**
 *
 */
function testHasNewNotifications(total_count_new_notifications=0)
{
    if ($btn_open_notify.length) {

        if (total_count_new_notifications > 0) {
            $btn_open_notify.addClass('_has-new');
        } else {
            $btn_open_notify.removeClass('_has-new');
        }

        $btn_open_notify.attr('data-total_count_new_notifications', total_count_new_notifications);
    }
}

function setNotificationsAsRead()
{
    /**/
    clearTimeout(clear_tmt_set_notif_as_read_var);

    /**/
    let $list_notify = $notify_list_container.find('.notify');
    if ($list_notify.length == 0) {
        return;
    }

    /**/
    clear_tmt_set_notif_as_read_var = setTimeout(function() {
        $.ajax({
            type: 'get',
            url: '/user/set-notifications-as-read',
            data: {
                sender_user_id: current_opponent_user_id
            },
            dataType: 'json'
        }).done(function (response) {

            if ("status" in response && "data" in response && response.status) {

                let i = 1;
                let l = $list_notify.length;
                $list_notify.each(function() {
                    let $self = $(this);
                    setTimeout(function() {
                        $self.fadeOut(1000, function() {
                            $self.remove();
                            l--;
                            $btn_open_notify.attr('data-total_count_new_notifications', l);
                            if (l <= 0) {
                                $btn_open_notify.removeClass('_has-new');
                            }
                        });
                    }, i*800);
                    i++;
                });
                setTimeout(function() {
                    $('.light-overlay').removeClass('_visible');
                    $notify_list_popup.removeClass('_visible');
                    $btn_open_notify.removeClass('_has-new').removeClass('_active');
                }, 800*i);


            } else {
                console.log(response);
                //prettyAlert($translate_text_messages.attr('data-msg-15'));
            }

        });
    }, SET_NOTIF_AS_READ_AFTER * 1000)
}


// --------------------------- chat ---------------------------
/**
 *
 */
function getChatMessages()
{
    /**/
    clearTimeout(clear_tmt_get_chat_mes_var);

    /**/
    $.ajax({
        type: 'get',
        url: '/user/get-chat-messages',
        dataType: 'json'
    }).done(function (response) {

        if ("status" in response && "data" in response && response.status) {

            /**/
            redrawChat(response.data);

            /**/
            testHasNew(response.data.total_count_new_opponents, response.data.total_count_new_messages);

            /**/
            if (current_opponent_user_id) {
                changeOpponentChat(current_opponent_user_id);
            }

            /**/
            testAnybodySelected();

        } else {
            console.log(response);
            //prettyAlert($translate_text_messages.attr('data-msg-15'));
        }

    });

    /* перезапрос сообщений каждые 30 сек */
    //clear_tmt_get_chat_mes_var = setTimeout(function(){ getChatMessages(); }, UPDATE_MESSAGE_INTERVAL * 1000);
}

/**
 * @param data
 */
function redrawChat(data)
{
    $chat_users.html(data.chat_users);
    $chat_messages.html(data.chat_messages);

    /**/
    $chat_messages.find('.js-messages-stack-my').each(function() {
        $(this).scroll(function () {
            //console.log($(this)[0].scrollTop);
            scroll_position_for[$(this).attr('data-opponent_user_id')] = $(this)[0].scrollTop;
        });
    });

    //if (typeof initChatUsersList == 'function') { initChatUsersList(); }
}

/**
 *
 */
function testAnybodySelected()
{
    /**/
    let $test_anybody_here = $chat_users.find('.js-user-chat-link-my._current');
    //console.log($test_anybody_here.length);
    if ($test_anybody_here.length) {
        $message_for_opponent[0].disabled = false;
        $button_send_message[0].disabled = false;
    } else {
        $message_for_opponent[0].disabled = true;
        $button_send_message[0].disabled = true;
    }
}


let total_count_new_messages_prev = window.localStorage.getItem("total_count_new_messages_prev");
total_count_new_messages_prev = (total_count_new_messages_prev == null) ? 0 : parseInt(total_count_new_messages_prev);
let total_count_new_opponents_prev = window.localStorage.getItem("total_count_new_messages_prev");
total_count_new_opponents_prev = (total_count_new_opponents_prev == null) ? 0 : parseInt(total_count_new_opponents_prev);
/**
 *
 */
function testHasNew(total_count_new_opponents=0, total_count_new_messages=0)
{
    let $js_open_chat = $(document).find('.js-open-chat').first();

    if ($js_open_chat.length) {
        let $test_new = $chat_users.find('._has-new');
        if ($test_new.length) {
            //if (!$js_open_chat.hasClass('_has-new')) {
            $js_open_chat.addClass('_has-new');
        } else {
            $js_open_chat.removeClass('_has-new');
        }


        //let total_count_new_opponents_prev = $js_open_chat.attr('data-total_count_new_opponents');
        console.log('total_count_new_messages = ', total_count_new_messages);
        console.log('total_count_new_messages_prev = ', total_count_new_messages_prev);
        if (total_count_new_messages > total_count_new_messages_prev) {
            let play = beeper.canPlayType && beeper.canPlayType('audio/wav; codecs="1"');
            play && play != '' && play != 'no' && $(beeper).html('<source src="' + soundFile + '" type="audio/wav">')[0].play();
        }

        total_count_new_messages_prev = total_count_new_messages;
        window.localStorage.setItem("total_count_new_messages_prev", total_count_new_messages);
        total_count_new_opponents_prev = total_count_new_opponents;
        window.localStorage.setItem("total_count_new_opponents_prev", total_count_new_opponents);
        $js_open_chat.attr('data-total_count_new_opponents', total_count_new_opponents);
        $js_open_chat.attr('data-total_count_new_messages', total_count_new_messages);

    }
}

/**
 * @param opponent_user_id
 */
function changeOpponentChat(opponent_user_id)
{
    /**/
    clearTimeout(clear_tmt_set_as_read_var);

    /**/
    let prev_opponent_user_id = current_opponent_user_id;
    current_opponent_user_id = opponent_user_id;

    $('.js-user-chat-link-my').removeClass('_current');
    $('.js-messages-stack-my').removeClass('_opened');

    let $us = $(`.opponent-${current_opponent_user_id}`);
    let $mes = $(`.messages-opponent-${current_opponent_user_id}`);

    if ($us.length && $mes.length) {
        $us.addClass('_current');
        $mes.addClass('_opened');
        $mes.html($mes.html().replaceAll('{opponent_name}', $us.data('opponent_name')));
    }

    /* scroll to end or stored position */
    let $scrollTo = $mes.find('.scroll-to-bottom').first();
    if (typeof scroll_position_for[current_opponent_user_id] != 'undefined') {
        $mes.scrollTop(scroll_position_for[current_opponent_user_id]);
    }
    else if (prev_opponent_user_id != current_opponent_user_id) {
        if ($scrollTo.offset() !== undefined && $mes.offset() !== undefined) {
            $mes.animate({scrollTop: $scrollTo.offset().top - $mes.offset().top + $mes.scrollTop()}, 1000);
        }
    }
    if ($us.hasClass('_has-new')) {
        if ($scrollTo.offset() !== undefined && $mes.offset() !== undefined) {
            $mes.animate({scrollTop: $scrollTo.offset().top - $mes.offset().top + $mes.scrollTop()}, 1000);
        }
    }

    testAnybodySelected();

    /* set as read */
    clear_tmt_set_as_read_var = setTimeout(function() {
        //console.log($us.hasClass('_has-new'));

        if ($us.hasClass('_has-new') && $chat_popup.hasClass('_opened')) {

            console.log('set chat as read');
            $.ajax({
                type: 'get',
                url: '/user/set-chat-as-read',
                data: {
                    sender_user_id: current_opponent_user_id
                },
                dataType: 'json'
            }).done(function (response) {

                if ("status" in response && "data" in response && response.status) {

                    /**/
                    $us.removeClass('_has-new');
                    let count_read = 0;
                    $mes.find('.chat__message--opponent').each(function() {
                        if ($(this).hasClass('unread')) {
                            $(this).removeClass('unread');
                            count_read++;
                        }
                    });
                    total_count_new_messages_prev -= count_read;
                    window.localStorage.setItem("total_count_new_messages_prev", total_count_new_messages_prev);
                    total_count_new_opponents_prev--;
                    window.localStorage.setItem("total_count_new_opponents_prev", total_count_new_opponents_prev);

                    /**/
                    testHasNew(total_count_new_opponents_prev, total_count_new_messages_prev);

                } else {
                    console.log(response);
                    //prettyAlert($translate_text_messages.attr('data-msg-15'));
                }

            });

        }
    }, SET_AS_READ_AFTER * 1000);
}

/**
 *
 */
function sendMessageForOpponent()
{
    /**/
    if ($message_for_opponent[0].disabled) {
        return false;
    }

    /**/
    let msg_text = $.trim($message_for_opponent.val());

    /**/
    $message_for_opponent.removeClass('error');
    if (msg_text == '') {
        $message_for_opponent.addClass('error');
        return false;
    }

    /**/
    $.ajax({
        type: 'post',
        url: '/user/send-chat-message',
        data: {
            receiver_user_id: current_opponent_user_id,
            msg_text: msg_text
        },
        dataType: 'json'
    }).done(function (response) {

        if ("status" in response && "data" in response && response.status) {

            /**/
            let $us = $(`.opponent-${current_opponent_user_id}`);
            let $mes = $(`.messages-opponent-${current_opponent_user_id}`);

            /**/
            if ($us.length && $mes.length) {
                $us.addClass('_current');
                $mes.remove();
                let mes_new_html = response.data.opponent_messages.replaceAll('{opponent_name}', $us.data('opponent_name'));
                let $mes_new = $(mes_new_html);
                $chat_messages.append($mes_new);
                $mes_new.addClass('_opened');

                $mes_new.scroll(function () {
                    scroll_position_for[$mes_new.attr('data-opponent_user_id')] = $mes_new[0].scrollTop;
                });

                /**/
                let $scrollTo = $mes_new.find('.scroll-to-bottom').first();
                $mes_new.scrollTop(
                    $scrollTo.offset().top - $mes_new.offset().top + $mes_new.scrollTop()
                );

                if (ChatWebSocket) {
                    ChatWebSocket.send(JSON.stringify(
                        {
                            chat: {
                                user_id: current_opponent_user_id
                            }
                        }
                    ));
                }
            }

            /**/
            $message_for_opponent.val('');

        } else {
            console.log(response);
            //prettyAlert($translate_text_messages.attr('data-msg-15'));
        }

    });

    return false;
}

/**
 * @returns {boolean}
 */
function startChatOpponentSearch()
{
    let search_text = $.trim($chat_search.val().toLowerCase());
    if (search_text) {

        /* на время поиска остановим автообновление чата, иначе он будет сбрасывать поиск каздые 30 сек */
        clearTimeout(clear_tmt_get_chat_mes_var);

        /* сохраним выбранного о поиска оппонента */
        if (current_opponent_user_id) {
            store_before_search_current_opponent_user_id = current_opponent_user_id;
            current_opponent_user_id = null;
        }

        /* при поиске снимем подсветку с выбранного до поиска оппонента и спрячем его переписку */
        $('.js-user-chat-link-my').removeClass('_current').removeClass('_hidden');
        $('.js-messages-stack-my').removeClass('_opened');

        /* спрячем всех кто не удовлетворяет поиску */
        $chat_users.find('.js-user-chat-link-my').each(function() {
            let name = $(this).data('opponent_name');
            name = name.toLowerCase();
            if (name.indexOf(search_text) < 0) {
                $(this).addClass('_hidden');
            }
        });

    } else {

        /* покажем всех оппонентов при сбросе поиска */
        $('.js-user-chat-link-my').removeClass('_hidden');

        /* восстановим выбранного оппонента */
        if (store_before_search_current_opponent_user_id) {
            if (!current_opponent_user_id) {
                //current_opponent_user_id = store_before_search_current_opponent_user_id;
                changeOpponentChat(store_before_search_current_opponent_user_id);
            }
        }

        /* перезапустим автообновление чата после сброса поиска */
        //clear_tmt_get_chat_mes_var = setTimeout(function(){ getChatMessages(); }, UPDATE_MESSAGE_INTERVAL * 1000);

    }

    testAnybodySelected();

    return false;
}

/**
 * @returns {boolean}
 */
function startTextSearch()
{
    let search_text = $.trim($chat_search.val().toLowerCase());
    if (search_text) {

        /* на время поиска остановим автообновление чата, иначе он будет сбрасывать поиск каздые 30 сек */
        clearTimeout(clear_tmt_get_chat_mes_var);

        /* сохраним выбранного о поиска оппонента */
        if (current_opponent_user_id) {
            store_before_search_current_opponent_user_id = current_opponent_user_id;
            current_opponent_user_id = null;
        }

        /* при поиске снимем подсветку с выбранного до поиска оппонента и спрячем его переписку */
        $('.js-user-chat-link-my').removeClass('_current').removeClass('_hidden');
        $('.js-messages-stack-my').removeClass('_opened');
        $('.js-chat-message-text').removeClass('_found');
        $('.js-chat-message').removeClass('_hidden');

        /* соберем массив оппонентов у которых в переписке найден искомый текст */
        let found_users = [];
        let i = 0;
        $chat_messages.find('.js-chat-message-text').each(function() {
            let $self = $(this);
            let msg_text = $self.attr('data-original_msg_text');
            msg_text = msg_text.toLowerCase();
            let $mes = $self.parent().parent();
            let opponent_user_id = $mes.data('opponent_user_id');

            if (msg_text.indexOf(search_text) >= 0) {
                found_users[i] = opponent_user_id;
                i++;
                $self.addClass('_found');
                $mes.addClass('_opened');
                console.log($self.offset().top - $mes.offset().top + $mes.scrollTop());
                //scroll_position_for[opponent_user_id] = $self.offset().top - $mes.offset().top + $mes.scrollTop();
                $mes.scrollTop($self.offset().top - $mes.offset().top + $mes.scrollTop());
                setTimeout(function() {$mes.removeClass('_opened')}, 30);
            } else {
                //$self.parent().addClass('_hidden');
            }
        });

        /* так же добавим к этому массивутех опонентов чье имя удовлетворяет поиску */
        $chat_users.find('.js-user-chat-link-my').each(function() {
            let name = $(this).data('opponent_name');
            name = name.toLowerCase();
            if (name.indexOf(search_text) >= 0) {
                found_users[i] = $(this).data('opponent_user_id');;
                i++;
            }
        });

        /* скроем всех, и затем покажем только оппонентов из собранного выще массива */
        //console.log(found_users);
        $('.js-user-chat-link-my').addClass('_hidden');
        let first_found;
        jQuery.each(found_users, function (key, val) {
            if (!first_found) { first_found = val; }
            $(`.js-user-chat-link-my.opponent-${val}`).removeClass('_hidden');
        });

        /* откроем чат первого найденного оппонента */
        if (first_found) {
            //current_opponent_user_id = first_found;
            setTimeout(function() { changeOpponentChat(first_found); }, 40);
        }

    } else {

        /* покажем всех оппонентов при сбросе поиска */
        $('.js-user-chat-link-my').removeClass('_hidden');
        $('.js-chat-message-text').removeClass('_found');
        $('.js-chat-message').removeClass('_hidden');

        /* восстановим выбранного оппонента */
        if (store_before_search_current_opponent_user_id) {
            if (!current_opponent_user_id) {
                //current_opponent_user_id = store_before_search_current_opponent_user_id;
                changeOpponentChat(store_before_search_current_opponent_user_id);
            }
        }

        /* перезапустим автообновление чата после сброса поиска */
        //clear_tmt_get_chat_mes_var = setTimeout(function(){ getChatMessages(); }, UPDATE_MESSAGE_INTERVAL * 1000);

    }

    return false;
}

/**
 *  **
 *  **
 */
$(document).ready(function() {

    /**/
    getChatMessages();

    /**/
    getNotificationMessages();

    /**/
    $(document).on('click', '#btn-open-notify', function() {

        let $list_notify = $notify_list_container.find('.notify');
        if ($list_notify.length == 0) {
            $('.light-overlay').removeClass('_visible');
            $notify_list_popup.removeClass('_visible');
            $btn_open_notify.removeClass('_has-new').removeClass('_active');
            return;
        }

        if ($notify_list_popup.hasClass('_visible')) {
            setNotificationsAsRead();
        }
    });

    /**/
    $(document).on('click', '.js-open-chat', function() {
        if (!current_opponent_user_id && $chat_popup.hasClass('_opened')) {
            let $test = $chat_users.find('.js-user-chat-link-my').first();
            if ($test.length) {
                changeOpponentChat($test.data('opponent_user_id'));
                testAnybodySelected();
            }
        }
        if (current_opponent_user_id && $chat_popup.hasClass('_opened')) {
            changeOpponentChat(current_opponent_user_id);
        }
    });

    /**/
    $(document).on('click', '.js-user-chat-link-my', function() {
        //let $self = $(this);
        //if ($(this).hasClass('_current')) {
        //    $self.parent().addClass('_opened');
        //} else {
        //    $self.parent().removeClass('_opened');
        //}
        $chat_users.toggleClass('_opened');
        changeOpponentChat($(this).data('opponent_user_id'));
    });

    /**/
    $(document).on('keydown', '#message-for-opponent', function() {
        $(this).removeClass('error');
    });

    /**/
    $(document).on('click', '.js-open-chat-with', function() {
        let $self = $(this);

        $.ajax({
            type: 'post',
            url: '/user/start-chat-with',
            data: {
                opponent_user_id: $self.data('opponent_user_id'),
                opponent_display_name: $self.data('opponent_display_name'),
                opponent_first_name: $self.data('opponent_first_name'),
                opponent_last_name: $self.data('opponent_last_name'),
                opponent_photo: $self.data('opponent_photo'),
                opponent_type: $self.data('opponent_type')
            },
            dataType: 'json'
        }).done(function (response) {

            if ("status" in response && "data" in response && response.status) {

                /**/
                redrawChat(response.data);

                /**/
                current_opponent_user_id = $self.data('opponent_user_id');
                changeOpponentChat(current_opponent_user_id);

                /**/
                testAnybodySelected();

            } else {
                console.log(response);
                //prettyAlert($translate_text_messages.attr('data-msg-15'));
            }

        });

        $trigger_open_chat[0].click();
    });

    /**/
    //$(document).on('keydown keyup change', '#chat-search', function() {
    //    //startTextSearch();
    //    startChatOpponentSearch();
    //});
    //$(document).on('submit', '.chat__search-form', function() {
    //    return false;
    //});

    /**/
    $(document).on('submit', '.chat__search-form', function() {
        return startTextSearch();
    });

    let alerted_about_lost_connection = false;
    ChatWebSocket = new Ws($chat_popup.data('wss-url'), 5); // второй параметр если 0 то реконекта не будет, иначе реконект после заданного кол-ва секунд
    ChatWebSocket.onopen = function() {
        //this.connected = true;
        alerted_about_lost_connection = false;
        console.log('Connected to ' + this._url);
    };
    ChatWebSocket.onmessage = function (message) {
        /**/
        console.info("api.endpointTextMessageReceived:: data=", message.data);
        let parsed = JSON.parse($.trim(message.data));
        /**/
        if ("chat" in parsed && "user_id" in parsed.chat) {
            let my_user_id = parseInt($chat_popup.data('my_user_id'));
            console.info('api.endpointTextMessageReceived::chat::for_user_id=', parsed.chat.user_id);
            if (parseInt(parsed.chat.user_id) == my_user_id) {
                /* обновим чат, потому что точно знаем что кто-то отправил этому юзеру сообщение */
                //clear_tmt_get_chat_mes_var = setTimeout(function(){ getChatMessages(); }, UPDATE_MESSAGE_INTERVAL * 1000);
                getChatMessages();
            }
        }
        if ("notification" in parsed && "user_id" in parsed.notification) {
            let my_user_id = parseInt($chat_popup.data('my_user_id'));
            console.info('api.endpointTextMessageReceived::notification::for_user_id=', parsed.notification.user_id);
            if (parseInt(parsed.notification.user_id) == my_user_id) {
                /* обновим нотифы, потому что точно знаем что этому юзеру пришел новый нотиф */
                //clear_tmt_get_chat_mes_var = setTimeout(function(){ getChatMessages(); }, UPDATE_MESSAGE_INTERVAL * 1000);
                getNotificationMessages();
            }
        }
    };
    ChatWebSocket.onerror = function(error) {
        if (!alerted_about_lost_connection) {
            //prettyAlert($translate_text_messages.attr('data-msg-16'));
            alerted_about_lost_connection = true;
        }
    };
    ChatWebSocket.send = function(message) {
        if (this.getState() == this.State.CONNECTING) {
            setTimeout(function () {
                this.send(message);
            }.bind(this), 1000);
        } else {
            if (this.connected) {
                //console.log('Sent: ' + message);
                this._ws.send(message);
            } else {
                //prettyAlert($translate_text_messages.attr('data-msg-17'));
                console.log("Socket connection error!");
            }
        }
    }
});