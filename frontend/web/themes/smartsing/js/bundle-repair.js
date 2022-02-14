const _slideUp = (target, duration = 500) => {
    target.style.transitionProperty = 'height, margin, padding';
    target.style.transitionDuration = duration + 'ms';
    target.style.boxSizing = 'border-box';
    target.style.height = target.offsetHeight + 'px';
    target.offsetHeight;
    target.style.overflow = 'hidden';
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    window.setTimeout(() => {
        target.style.display = 'none';
        target.style.removeProperty('height');
        target.style.removeProperty('padding-top');
        target.style.removeProperty('padding-bottom');
        target.style.removeProperty('margin-top');
        target.style.removeProperty('margin-bottom');
        target.style.removeProperty('overflow');
        target.style.removeProperty('transition-duration');
        target.style.removeProperty('transition-property');
    }, duration);
};

const _slideDown = (target, duration = 500, disp = 'block') => {
    target.style.removeProperty('display');
    let display = window.getComputedStyle(target).display;

    if (display === 'none') { display = disp; }

    target.style.display = display;
    const height = target.offsetHeight;
    target.style.overflow = 'hidden';
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    target.offsetHeight;
    target.style.boxSizing = 'border-box';
    target.style.transitionProperty = 'height, margin, padding';
    target.style.transitionDuration = duration + 'ms';
    target.style.height = height + 'px';
    target.style.removeProperty('padding-top');
    target.style.removeProperty('padding-bottom');
    target.style.removeProperty('margin-top');
    target.style.removeProperty('margin-bottom');
    window.setTimeout(() => {
        target.style.removeProperty('height');
        target.style.removeProperty('overflow');
        target.style.removeProperty('transition-duration');
        target.style.removeProperty('transition-property');
    }, duration);
};

const _slideToggle = (target, duration = 500, disp = 'block') => {
    if (window.getComputedStyle(target).display === 'none') {
        return _slideDown(target, duration, disp);
    } else {
        return _slideUp(target, duration);
    }
};


function _hwPlayer() {

    const fixedPwPlayer = document.querySelector('.js-fixed-pw-player');
    const max600 = window.matchMedia('(max-width: 600px)');
    let test_w = $(window).width();
    let test_h = $(window).height();

    let $objScroll = $('#present-info-content');
    let curVarScroll = 1;
    let scrollTop = 270;

    //if (max600.matches) {
    if (test_w < test_h) {
        scrollTop = 160;
        curVarScroll = 2;
        $objScroll = $(window);
    }

    if (test_w > 800) {
        $('.hw-player__params').show();
    }

    if (fixedPwPlayer) {

        /* clear scroll function for both */
        $(window).scroll(function() {});
        $('#present-info-content').scroll(function() {});

        /* new scroll */
        $objScroll.scroll(function() {

            const fixedClass = 'slides-control-player-fixed';
            const $player_control_height = $('#player-control-height');
            const $player_control = $('#player-control');

            let currentScroll;
            if (curVarScroll == 2) {
                currentScroll = $(document).scrollTop();
            } else {
                currentScroll = $objScroll.scrollTop();
            }

            //console.log('currentScroll = ', currentScroll);

            if (currentScroll > scrollTop) {
                $player_control.addClass(fixedClass);
                $player_control_height.css({ 'margin-bottom': '160px' }).show();
            } else {
                $player_control.removeClass(fixedClass);
                $player_control_height.css({ 'margin-bottom': 0 }).hide();
            }
        });
    }
}

function _showHideVolumeParam(target)
{
    //const target = e.target;
    const params = target.closest('.js-hw-player').querySelector('.js-hw-params');

    _slideToggle(params, 250, 'flex');

    target.classList.toggle('_active');
    target.innerText = target.classList.contains('_active') ? target.dataset.titleHidden : target.dataset.title;

}


function _formats () {
    Array.from(document.querySelectorAll('.js-flag')).forEach((self) => {
        self.innerHTML = self.innerText === 'Да' ? '<span class="included"></span>' : '<span class="not-included"></span>';
    });

    const setEqualOuterHeight = (columns) => {
        let tallestcolumn = 0;
        columns.css('height', 'auto');
        columns.each(
            function () {
                const currentHeight = $(this).outerHeight();
                if (currentHeight > tallestcolumn) {
                    tallestcolumn = currentHeight;
                }
            }
        );
        columns.outerHeight(tallestcolumn);
    };

    const $formatValues = $('.js-format-values > div');
    const $formatTitles = $('.js-format-title');

    $formatValues.each(function () {
        const $this = $(this);
        const index = $this.index();
        const eqClass = 'eqh-' + index;
        $this.addClass(eqClass);

        const $title = $formatTitles.eq(index);
        $title.addClass(eqClass);
        $formatValues.eq($this.index()).addClass(eqClass);

        setEqualOuterHeight($('.' + eqClass));
    });

    Array.from(document.querySelectorAll('.js-formats-slider')).forEach((self) => {
        const fSlider = self;
        const $fSlider = $(fSlider);

        $fSlider.slick({
            dots: false,
            arrows: true,
            speed: 650,
            rows: 0,
            infinite: false,
            adaptiveHeight: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            useTransform: true,
            useCss: true,
            prevArrow: $(fSlider).parents('.slider-wrap').find('.slider-nav__item--prev').first(),
            nextArrow: $(fSlider).parents('.slider-wrap').find('.slider-nav__item--next').first(),
            responsive: [
                {
                    breakpoint: 1271,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }

                },
                // {
                //     breakpoint: 1401,
                //     settings:{
                //         slidesToShow: 2,
                //         slidesToScroll: 1
                //     }
                //
                // },
                {
                    breakpoint: 1201,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1
                    }

                },
                {
                    breakpoint: 841,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }

                },
                {
                    breakpoint: 681,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }

                },
                {
                    breakpoint: 601,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }

                },
                {
                    breakpoint: 481,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }

                },
                {
                    breakpoint: 401,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }

                }
            ]
        });
    });
}

/**
 *
 */
function repairHeightLeftMenuWhenHidden()
{
    let $hamburger = $(document).find('.hamburger-btn').first();
    if (!$hamburger.length) {
        return;
    }
    if ($hamburger.is(':visible')) {
        return;
    }

    let min_h = 0;
    let $test = $(document).find('.user-menu-holder').first();
    if ($test.hasClass('_left')) {
        const items = document.querySelectorAll('.js-user-menu-item');
        items.forEach(function (item) {
            if (parseInt(item.offsetHeight) < min_h) { min_h = parseInt(item.offsetHeight); }
        });
        if (min_h == 0) { min_h = 22; }

        items.forEach(function (item) {
            item.style.height = `${min_h}px`;
        });
    }
}

/**
 *
 */
function initJsTabs() {
    //document.querySelectorAll('.js-tabs-item').forEach(item => {
    //    item.addEventListener('click', () => {
    //        const tabs = item.closest('.js-tabs');
    //        const tabsItems = tabs.querySelectorAll('.js-tabs-item');
    //        const content = item.closest('.tabs-wrap').querySelector('.tabs-content');
    //        const box = content.querySelectorAll('.box')[Array.from(tabsItems).indexOf(item)];
    //        if (!item.classList.contains('_current')) {
    //            tabs.querySelector('.js-tabs-item._current').classList.remove('_current');
    //            content.querySelector('.box._visible').classList.remove('_visible');
    //            item.classList.add('_current');
    //            box.classList.add('_visible');
    //        }
    //    });
    //});

    document.querySelectorAll('.js-inline-tabs-item').forEach(item => {
        item.addEventListener('click', () => {
            const box = document.querySelector(`#${item.dataset.boxId}`);
            if (box) {
                item.closest('.present__info').querySelector('.box._visible').classList.remove('_visible');
                item.closest('.present__info').querySelector('.inline-tabs-item._current').classList.remove('_current');
                box.classList.add('_visible');
                item.classList.add('_current');
            }
        });
    });
}

function triggerAfterOpenModal(modalId)
{
    modalId = modalId.replaceAll('-', '_');
    let funct = `modal_opened_${modalId}`;
    if (typeof window[funct] == 'function') {
        window[funct]();
    }
}

function triggerAfterCloseModal(modalId)
{
    modalId = modalId.replaceAll('-', '_');
    let funct = `modal_closed_${modalId}`;
    if (typeof window[funct] == 'function') {
        window[funct]();
    }
}