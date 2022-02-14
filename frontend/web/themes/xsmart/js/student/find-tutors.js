let $showed_filter = $('#showed-filter');
let $hidden_filter = $('#hidden-filter');
let $find_tutor_filters = $('#find-tutor-filters');
let $find_tutor_results = $('#find-tutor-results');
let $find_tutor_progress = $('#find-tutor-progress');
let $main_search_field = $('#main-search-field');
let URL;
let reSearch = false;

/**
 * @param {int} page
 */
function execFindTutor(page=1)
{
    /**/
    $showed_filter.addClass('_disabled');
    $hidden_filter.addClass('_disabled');
    $find_tutor_results.html('').hide();
    $find_tutor_progress.show();

    //document.querySelectorAll('.search-field').forEach(item2 => {
    //    //console.log(item2.classList);
    //    if (typeof item2.name != 'undefined') {
    //        console.log(item2.name + ' = ' + $(item2).val());
    //        //if (item2.id == 'geo-full-location-field') {
    //        //    console.log(item2.name + "['dataset'] = " + item2.dataset.ids);
    //        //}
    //
    //    }
    //});

    $main_search_field.removeClass('has-error');

    /**/
    let inp_obj = {};
    $find_tutor_filters.find('.search-field').each(function () {
        let $el = $(this);
        if (typeof $el.attr('name') != 'undefined') {
            if ($el.hasClass('checkbox-select') && $el.is(':checked')) {
                inp_obj[$el.attr('name')] = $el.data('value');
            } else if (!$el.hasClass('checkbox-select')) {
                inp_obj[$el.attr('name')] = $el.val();
            }
        }
        //if ($(this).hasClass('js-request-select')) {
        //    inp_obj[$el.attr('name') + '_text'] = $(this).find('option:selected').first().text();
        //}
    });

    /* не будем искать без дисциплины */
    //console.log(typeof inp_obj['TutorSearch[discipline_id]']);
    if (inp_obj['TutorSearch[discipline_id]'] == '') {
        $main_search_field.addClass('has-error');
        $showed_filter.removeClass('_disabled');
        $hidden_filter.removeClass('_disabled');
        $find_tutor_progress.hide();
        return false;
    }

    inp_obj['page'] = page;
    let tmp  = '?';
    for (let key in inp_obj) {
        //if (key == 'TutorSearch[country_id]' && inp_obj[key] <= 0) {
        //    inp_obj[key] = '';
        //}
        //alert(key + '=' + inp_obj[key]);
        tmp += key + '=' + inp_obj[key] + '&';
    }
    //alert(URL + tmp);
    history.pushState({}, '', URL + tmp);

    /**/
    $.ajax({
        type: 'post',
        url: '/site/find-tutors-request', //?sort=price_lowest',
        data: inp_obj,
        dataType: 'html'
    }).done(function (response) {

        $showed_filter.removeClass('_disabled');
        $hidden_filter.removeClass('_disabled');

        $find_tutor_results.html(response).show();
        $find_tutor_progress.hide();

        if (typeof initGLightbox == 'function') { initGLightbox(); }
        if (typeof wrapHidden == 'function') { wrapHidden(); }
        reSearch = false;
    });
}

/**
 *
 */
$(document).ready(function() {

    //URL = window.location.href;
    URL = '/find-tutors';
    if (_LANG_URL != 'en') {
        URL = _LANG_URL + URL;
    }

    /**/
    $(document).on('click', '.append-for-history-back', function() {
        if (history.pushState) {
            let baseUrl = window.location.href;
            let newUrl = `${baseUrl}&back_from_tutor=${$(this).data('user_id')}#tutor_${$(this).data('user_id')}`;
            //console.log(newUrl);
            history.pushState({}, '', newUrl);
            return true;
            //history.pushState(null, null, newUrl);
        }
    });

    /**/
    try {

        /**/
        //document.querySelectorAll('.search-field').forEach(item => {
        //    item.addEventListener('change', event => {
        //        if (!(item.classList.contains('geo-select') || (item.classList.contains('checkbox-select')))) {
        //
        //            if (!reSearch) {
        //                execFindTutor(1);
        //            }
        //
        //        }
        //    });
        //});

        /**/
        let old_val;
        document.querySelectorAll('.search-field').forEach(item => {
            if (!(item.classList.contains('geo-select') || (item.classList.contains('checkbox-select')))) {
                item.onclick = function() {
                    //console.log(item.value);
                    old_val = item.value;
                };
                item.onchange = function () {
                    if (!reSearch && item.value != old_val) {
                        execFindTutor(1);
                    }
                };
            }
        });

        /**/
        let searchParams = new URLSearchParams(window.location.search);
        //console.log(searchParams);
        let show_hidden_filter = false;
        searchParams.forEach(function(value, key) {
            if (key == 'TutorSearch[discipline_id]') {
                reSearch = true;
                if (value > 0) {
                    //$('#discipline-field').val(value);
                    let select_ = $('#discipline-field')[0].selectize;
                    select_.setValue(value);
                }
            } else if (key == 'TutorSearch[user_can_teach_children]') {
                reSearch = true;
                let select_ = $('#user_can_teach_children-field')[0].selectize;
                select_.setValue(value);
            } else if (key == 'TutorSearch[country_id]') {
                reSearch = true;
                //$('#geo-country-field').val(value);
                //$geo_country.attr('data-saved-val', value);
                let select_ = $geo_country[0].selectize;
                select_.setValue(value);
            } else if (key == 'TutorSearch[region_id]') {
                reSearch = true;
                if (value > 0) {
                    //$('#geo-country-field').val(value);
                    $('#geo-region-field').attr('data-saved-val', value);
                    let select_ = $('#geo-region-field')[0].selectize;
                    select_.addOption({value: value, text: `opt-${value}`});
                    select_.setValue(value);
                }
            } else if (key == 'TutorSearch[city_id]') {
                reSearch = true;
                if (value > 0) {
                    $('#geo-city-field').attr('data-saved-val', value);
                    //$('#geo-country-field').val(value);
                    let select_ = $('#geo-city-field')[0].selectize;
                    select_.addOption({value: value, text: `opt-${value}`});
                    select_.setValue(value);
                }
            //} else if (key == 'TutorSearch[geo]') {
            //    reSearch = true;
            //    $('#geo-full-location-field').val(value);
            //} else if (key == 'TutorSearch[price]') {
            //    reSearch = true;
            //    $('#price-field').val(value);
            } else if (key.indexOf('TutorSearch') >= 0) {
                reSearch = true;
                //console.log(key);
                let $field = $(`[name='${key}']`);
                //alert($field.length);
                if ($field.length) {
                    //alert($field.attr('type'));
                    if ($field.attr('type') == 'checkbox') {
                        $field.prop('checked', true);
                        show_hidden_filter = true;
                    } else {
                        $field.val(value);
                    }
                    if (key == 'TutorSearch[price]' && value != 0) {
                        show_hidden_filter = true;
                    }
                }
            }
            //console.log(key + ' = ' + value);
        });
        if (show_hidden_filter) {
            $hidden_filter.removeClass('hidden-content');
        }
        if (reSearch) {
            const event = document.createEvent('Event');
            event.initEvent('change', true, true);
            document.querySelector('#discipline-field').dispatchEvent(event);
            document.querySelector('#price-field').dispatchEvent(event);
            if (!searchParams.has('back_from_tutor')) {
                execFindTutor(1);
            } else {
                reSearch = false;
            }
        }

        /**/
        $(document).on('click', '.own-pager', function() {
            let $pg = $(this);
            if ($pg.hasClass('disabled') || $pg.hasClass('pages__item--current')) {
                return false;
            }
            execFindTutor(parseInt($(this).children().first().data('page')) + 1);
        });

        /**/
        $(document).on('click', '.own-pager a', function() {
            let $pg = $(this).parent();
            if ($pg.hasClass('disabled') || $pg.hasClass('pages__item--current')) {
                return false;
            }
            execFindTutor(parseInt($(this).data('page')) + 1);
        });


    } catch(e) { console.log(e); }

});