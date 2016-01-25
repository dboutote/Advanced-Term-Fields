( function ($) {
    'use strict';

    $('#the-list').on('click', '.editinline', function () {
        var tag_id = $(this).parents('tr').attr('id');
        var meta_value = $('td.' + i10n_WPTTIcons.custom_column + ' i', '#' + tag_id).attr('data-' + i10n_WPTTIcons.data_type);

        $(':input[name="' + i10n_WPTTIcons.meta_key + '"]', '.inline-edit-row').val(meta_value);
    });
})(jQuery);