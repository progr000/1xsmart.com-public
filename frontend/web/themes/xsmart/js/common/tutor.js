const $reviews_container = $('#reviews-container');
const $review_popup = $('#review-popup');

/**
 *
 */
function reloadReviewsContent(content)
{
    $reviews_container.html(content);
    if (typeof anyJsCarouselInit == 'function') { anyJsCarouselInit(); }
    if (typeof ratingInit == 'function') { ratingInit(); }

    let $data = $reviews_container.find('.js-tutor-data-rating-reviews').first();
    if ($data.length)
    {
        $('#tutor-main-rating').html($data.data('rating'));
        $('#tutor-main-reviews').html($data.data('reviews-count'));
    }
}

/**
 * @param teacher_user_id
 */
function tutorReviews(teacher_user_id)
{
    $.ajax({
        type: 'get',
        url: '/site/tutor-reviews',
        data: { teacher_user_id: teacher_user_id },
        dataType: 'html'
    }).done(function (response) {

        reloadReviewsContent(response);

    });
}

/**
 *
 */
$(document).ready(function() {

    console.log("previous url is: ", document.referrer);

    /**/
    //tutorReviews($reviews_container.data('teacher_user_id'));

    /**/
    $(document).on('change', '#timezone-select', function() {
        $('#form-change-timezone')[0].submit();
    });

    /**/
    $(document).on('click', '.js-booking-btn', function () {
        let $payment_params_button = $('#payment-params-button');
        $payment_params_button.attr('data-timestamp-gmt', $(this).data('timestamp-gmt'));
        $payment_params_button.attr('data-date-gmt', $(this).data('date-gmt'));

        let $booking_selected_date = $('#booking-selected-date');
         $booking_selected_date.html($(this).data('print-date') + ' ' + $booking_selected_date.data('user_timezone'));
    });

    /**/
    $(document).on('click', '.js-open-review-modal', function () {

        $('.active-rating-wrap').removeClass('has-error');
        $('#review-textarea').removeClass('has-error');

        let $self = $(this);
        $.ajax({
            type: 'get',
            url: '/user/check-open-review',
            data: { teacher_user_id: $(this).data('teacher_user_id') },
            dataType: 'json'
        }).done(function (response) {
            if ("status" in response && response.status) {
                $review_popup.addClass('_opened');
                $('#js-submit-review')[0].disabled = false;
            } else if ("data" in response) {
                prettyAlert($self.data(response.data));
                $('#js-submit-review')[0].disabled = true;
            }
        });
    });

    /**/
    $(document).on('submit', '#review-form', function() {

        const $a = $('.active-rating-wrap');
        const $i = $('.input-wrap');
        const $s = $('#js-submit-review');
        const $t = $('#review-textarea');
        const $r = $('#review-rating');
        $a.removeClass('has-error');
        $i.removeClass('has-error');
        $t.removeClass('has-error');
        $s[0].disabled = false;
        let error = false;

        //alert($('#review-rating').val());
        let rating = parseInt($r.val());
        if (rating == 0) {
            $a.addClass('has-error');
            error = true;
        }
        let txt = $.trim($t.val());
        if (txt == '') {
            $t.addClass('has-error');
            error = true;
        }
        if (error) {
            return false;
        }

        $s[0].disabled = true;
        $.ajax({
            type: 'post',
            url: '/user/send-review',
            data: {
                teacher_user_id: $reviews_container.data('teacher_user_id'),
                review_text: txt,
                review_rating: rating
            },
            dataType: 'html'
        }).done(function (response) {

            if ($.trim(response) != '') {

                reloadReviewsContent(response)

            } else {
                console.log('something wrong');
            }
            $r.val("0");
            $('.active-rating__item').removeClass('is-selected');
            $('.active-rating-text').removeClass('_visible');
            $('.js-rating-text').html('');
            $t.val('');
            $s[0].disabled = true;
            $review_popup.removeClass('_opened');

        });

        return false;

    });

    /**/
    //if ($(document).find('.tutor-schedule-show-second-week').length) {
    let $slider_scroll_info = $('#slider-scroll-info');
    if ($slider_scroll_info.length) {

        //let d_now = new Date();
        //let current_week_day = d_now.getDay();
        //if (current_week_day == 0) { current_week_day = 7; }
        //let slide_on = current_week_day - 1;
        //console.log(current_week_day);
        let slide_on_count = parseInt($slider_scroll_info.data('slide_on_count'));
        const slider = document.querySelector('.js-schedule-carousel');
        const flkty = Flickity.data(slider);
        //console.log(Flickity);
        //flkty.select(7);
        flkty.select(slide_on_count);

        /*
        let $test = $(document).find('.js-schedule-next').first();
        if ($test.length) {
            //alert(1);
            $test[0].click();
        }
        */
    }
});