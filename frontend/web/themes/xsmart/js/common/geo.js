let $geo_country = $('#geo-country-field');
let $geo_region = $('#geo-region-field');
let $geo_city = $('#geo-city-field');
let $select_all_geo_ok1 = $('#select-all-geo-ok1');
let $select_all_geo_ok2 = $('#select-all-geo-ok2');

/**
 * @param country_id
 * @param trigger_finish_choose
 */
function geoRegionsListForCountry(country_id, trigger_finish_choose=false)
{
    if (country_id == 0) {
        $geo_region.selectize()[0].selectize.destroy();
        $geo_region.empty();
        $geo_region.append('<option value="" data-placeholder="true">' + $geo_region.data('placeholder-any') + '</option>');
        $geo_region.selectize();

        $geo_city.selectize()[0].selectize.destroy();
        $geo_city.empty();
        $geo_city.append('<option value="" data-placeholder="true">' + $geo_city.data('placeholder-any') + '</option>');
        $geo_city.selectize();
        return;
    }

    $geo_region.selectize()[0].selectize.destroy();
    $geo_region.empty();
    $geo_region.append('<option value="" data-placeholder="true">' + $geo_region.data('placeholder-loading') + '</option>');
    $geo_region.selectize();

    $geo_city.selectize()[0].selectize.destroy();
    $geo_city.empty();
    $geo_city.append('<option value="" data-placeholder="true">' + $geo_city.data('placeholder-loading') + '</option>');
    $geo_city.selectize();

    $.ajax({
        type: 'get',
        url: '/site/get-geo-regions-list-for-country',
        data: { country_id: country_id },
        dataType: 'json'
    }).done(function (response) {

        if ("status" in response && response.status && "data" in response) {

            $geo_region.selectize()[0].selectize.destroy();
            $geo_region.empty();
            $geo_region.append('<option value="" data-placeholder="true">' + $geo_region.data('placeholder-ready') + '</option>');
            $geo_region.selectize();

            if (response.data.length > 0) {
                $geo_region.selectize()[0].selectize.destroy();
                $geo_region.empty();
                $geo_region.append('<option value="0">' + $geo_region.data('placeholder-any') + '</option>');
                let saved_val = $geo_region.data('saved-val');
                for (var i = 0; i < response.data.length; i++) {
                    let selected = "";
                    if (typeof saved_val != 'undefined') {
                        if (saved_val == response.data[i].region_id) { selected = 'selected="selected"'; }
                    }
                    $geo_region.append('<option value="' + response.data[i].region_id + '" ' + selected + '>' + response.data[i].region_name + '</option>');
                }

                $geo_city.selectize()[0].selectize.destroy();
                $geo_city.empty();
                $geo_city.append('<option value="" data-placeholder="true">' + $geo_city.data('placeholder-select') + '</option>');
                $geo_city.selectize();

                $geo_region.selectize();

                if ($geo_region.val() != 0) {
                    geoCitiesListForRegion($geo_region.val(), trigger_finish_choose);
                } else {
                    if (trigger_finish_choose) {
                        triggerFinishChoose();
                    }
                }
            } else {
                $geo_region.selectize()[0].selectize.destroy();
                $geo_region.empty();
                $geo_region.append('<option value="0" data-placeholder="true">' + $geo_region.data('placeholder-any') + '</option>');
                $geo_region.selectize();
                geoCitiesListForCountry(country_id, trigger_finish_choose);
            }

        } else {
            console.log(response);
            //prettyAlert($translate_text_messages.attr('data-msg-15'));
        }

    });
}

/**
 * @param region_id
 * @param trigger_finish_choose
 */
function geoCitiesListForRegion(region_id, trigger_finish_choose=false)
{
    if (region_id == 0) {
        $geo_city.selectize()[0].selectize.destroy();
        $geo_city.empty();
        $geo_city.append('<option value="" data-placeholder="true">' + $geo_city.data('placeholder-any') + '</option>');
        $geo_city.selectize();
        return;
    }

    $geo_city.selectize()[0].selectize.destroy();
    $geo_city.empty();
    $geo_city.append('<option value="" data-placeholder="true">' + $geo_city.data('placeholder-loading') + '</option>');
    $geo_city.selectize();

    $.ajax({
        type: 'get',
        url: '/site/get-geo-cities-list-for-region',
        data: { region_id: region_id },
        dataType: 'json'
    }).done(function (response) {

        if ("status" in response && response.status && "data" in response) {

            $geo_city.selectize()[0].selectize.destroy();
            $geo_city.empty();
            $geo_city.append('<option value="" data-placeholder="true">' + $geo_city.data('placeholder-ready') + '</option>');
            $geo_city.append('<option value="0">' + $geo_city.data('placeholder-any') + '</option>');
            $geo_city.selectize();

            $geo_city.selectize()[0].selectize.destroy();
            let saved_val = $geo_city.data('saved-val');
            for (var i = 0; i < response.data.length; i++) {
                let selected = "";
                if (typeof saved_val != 'undefined') {
                    if (saved_val == response.data[i].city_id) { selected = 'selected="selected"'; }
                }
                $geo_city.append('<option value="' + response.data[i].city_id + '" ' + selected + '>' + response.data[i].city_name + '</option>');
            }

            $geo_city.selectize();

            if (trigger_finish_choose) {
                triggerFinishChoose();
            }

        } else {
            console.log(response);
            //prettyAlert($translate_text_messages.attr('data-msg-15'));
        }

    });
}

/**
 * @param country_id
 * @param trigger_finish_choose
 */
function geoCitiesListForCountry(country_id, trigger_finish_choose=false)
{
    if (country_id == 0) {
        $geo_region.selectize()[0].selectize.destroy();
        $geo_region.empty();
        $geo_region.append('<option value="" data-placeholder="true">' + $geo_region.data('placeholder-any') + '</option>');
        $geo_region.selectize();

        $geo_city.selectize()[0].selectize.destroy();
        $geo_city.empty();
        $geo_city.append('<option value="" data-placeholder="true">' + $geo_city.data('placeholder-any') + '</option>');
        $geo_city.selectize();
        return;
    }

    $geo_city.selectize()[0].selectize.destroy();
    $geo_city.empty();
    $geo_city.append('<option value="" data-placeholder="true">' + $geo_city.data('placeholder-loading') + '</option>');
    $geo_city.selectize();

    $.ajax({
        type: 'get',
        url: '/site/get-geo-cities-list-for-country',
        data: { country_id: country_id },
        dataType: 'json'
    }).done(function (response) {

        if ("status" in response && response.status && "data" in response) {

            $geo_city.selectize()[0].selectize.destroy();
            $geo_city.empty();
            $geo_city.append('<option value="" data-placeholder="true">' + $geo_city.data('placeholder-ready') + '</option>');
            $geo_city.append('<option value="0">' + $geo_city.data('placeholder-any') + '</option>');
            $geo_city.selectize();

            $geo_city.selectize()[0].selectize.destroy();
            let saved_val = $geo_city.data('saved-val');
            for (var i = 0; i < response.data.length; i++) {
                let selected = "";
                if (typeof saved_val != 'undefined') {
                    if (saved_val == response.data[i].city_id) { selected = 'selected="selected"'; }
                }
                $geo_city.append('<option value="' + response.data[i].city_id + '" ' + selected + '>' + response.data[i].city_name + '</option>');
            }

            $geo_city.selectize();

            if (trigger_finish_choose) {
                triggerFinishChoose();
            }

        } else {
            console.log(response);
            //prettyAlert($translate_text_messages.attr('data-msg-15'));
        }

    });
}

/**
 *
 */
function triggerFinishChoose()
{
    if ($select_all_geo_ok1.length && $select_all_geo_ok2.length) {
        $select_all_geo_ok1.val(
            $(`#${$geo_country.attr('id')} option:selected`).text() + ', ' +
            $(`#${$geo_region.attr('id')} option:selected`).text() + ', ' +
            $(`#${$geo_city.attr('id')} option:selected`).text()
        );
        /*
        $select_all_geo_ok1[0].click();
        setTimeout(function () {
            $select_all_geo_ok2[0].click();
        }, 100);
        */
    }
}

/**
 *
 */
$(document).ready(function() {

    try {

        /**/
        if ($geo_country.length && $geo_country.val() > 0) {
            geoRegionsListForCountry($geo_country.val(), true);
        }

        /**/
        document.querySelector('#geo-country-field').onchange = function () {
            geoRegionsListForCountry($(this).val());
        };

        /**/
        document.querySelector('#geo-region-field').onchange = function() {
            geoCitiesListForRegion($(this).val());
        };

    } catch(e) { }

});