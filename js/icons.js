( function ($) {
    'use strict';

    $('#the-list').on('click', '.editinline', function () {
        var tr_id = $(this).parents('tr').attr('id');
        var meta_value = $('td.' + i10n_ATMFIcons.custom_column_name + ' i', '#' + tr_id).attr('data-' + i10n_ATMFIcons.data_type);

        $(':input[name="' + i10n_ATMFIcons.meta_key + '"]', '.inline-edit-row').val(meta_value);
    });

    $('.icon-utils #term_icon').on('change', function () {
        var dashicon_chosen = $(this).val();
        $('#wp-tt-icon-meta-wrap i.term-icon').removeClass(function (index, css) {
            return (css.match(/\bdashicons-\S+/g) || []).join(' ');
        }).attr('data-icon', dashicon_chosen).addClass(dashicon_chosen);
    });

    $('.icon-img', '#wp-tt-icon-meta-wrap').on('click', function () {
        $('#wp-tt-icon-meta-wrap').find('.dashicons-picker').trigger('click');
    });

})(jQuery);