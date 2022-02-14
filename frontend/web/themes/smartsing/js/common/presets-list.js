/** ***** **/
$(document).ready(function() {

    /**/
    $(document).on('change', '#presets-list-search-sort1', function() {
        let $this = $(this);
        let $sel = $this.find('option:selected').first();
        $('#sort-data1').val($sel.data('sort-val'));
        $('#submit-filter-presets-list1').trigger('click');
    });

    /**/
    $(document).on('change', '#presets-list-search-sort2', function() {
        let $this = $(this);
        let $sel = $this.find('option:selected').first();
        $('#sort-data2').val($sel.data('sort-val'));
        $('#submit-filter-presets-list2').trigger('click');
    });

    /**/
    $(document).on('click', '.js-preset-remove', function() {

        let $this = $(this);

        prettyConfirm(
            function () {
                window.location.href = $this.data('href');
            },
            function () {

            },
            "Вы уверены что хотите удалить пресет."
        );

        return false;

    });
});