( function ($) {
    'use strict';

    function clear_delete_link(parent) {
        $('.del-term-thumbnail', parent).remove();
    }

    function reset_thumbnail_id(parent) {
        $(':input[name="' + i10n_ATMFImages.meta_key + '"]', parent).val('');
    }

    function remove_selected_thumb(el, parent) {
        $('img[data-term-img=' + el.data('term-img') + ']', parent).remove();
    }

    function reset_set_link(parent) {
        var sl = $('.set-term-thumbnail', parent);
        sl.addClass('button').html(sl.attr('title'));
    }

    function build_thumbnail_html(image) {
        var img_html = '';
        if ('' !== image) {
            img_html = $('<img />').attr({
                'id': "term-img-" + image.id,
                'src': image.url,
                'class': 'term-feat-img',
                'data-term-img': image.id
            });
        }
        return img_html;
    }

    function build_delete_link_html(image, set_link) {
        var link_html = '';
        if ('' !== image) {
            link_html = $('<a />').attr({
                'href': '#',
                'class': 'del-term-thumbnail',
                'data-term-img': image.id
            }).text(set_link.data('delete'));
        }
        return link_html;
    }


    /**
     * Globals
     */
    var thumb_modal;
    var set_link;
    var set_link_parent;
    var img_html;
    var del_link;

    $('.set-term-thumbnail').on('click', function (e) {
        set_link = $(e.currentTarget);
        set_link_parent = set_link.closest('.inside');

        e.preventDefault();

        // Open the modal
        if (thumb_modal) {
            thumb_modal.open();
            return;
        }

        // Create the media frame.
        thumb_modal = wp.media.frames.thumb_modal = wp.media({
            title: set_link.data('choose'),
            library: {type: 'image'},
            button: {text: set_link.data('update')},
            multiple: false
        });



        // Picking an image
        thumb_modal.on('select', function () {

            // remove the existing delete link
            clear_delete_link(set_link_parent);

            // Get the image
            var image = thumb_modal.state().get('selection').first().toJSON();

            if ('' !== image) {

                // build the thumbnail image
                img_html = build_thumbnail_html(image);

                //build the delete link
                del_link = build_delete_link_html(image, set_link);

                // wrap the image in the set link
                set_link.removeClass('button').html(img_html).after(del_link);

                // set the meta value
                $(':input[name="' + i10n_ATMFImages.meta_key + '"]', set_link_parent).val(image.id);

            }
        });

        // Open the modal
        thumb_modal.open();
    });


    /**
     * Deleting the thumbnail from the add form
     */
    $('#termimagediv').on('click', '.del-term-thumbnail', function (e) {
        e.preventDefault();
        var $el = $(e.currentTarget);
        var $parent = $el.closest('.inside');

        reset_thumbnail_id($parent);
        remove_selected_thumb($el, $parent);
        reset_set_link($parent);
        clear_delete_link($parent);
    });


    /**
     * Deleting the thumbnail from the quick edit form
     */
    $('#the-list').on('click', '.del-term-thumbnail', function (e) {
        e.preventDefault();
        var $el = $(e.currentTarget);
        var $parent = $el.closest('.inside');

        reset_thumbnail_id($parent);
        remove_selected_thumb($el, $parent);
        reset_set_link($parent);
        clear_delete_link($parent);
    });



    /**
     * Quick edit
     *
     * Note: the quick-edit form clones elements on open, so we have to delete them when we open
     * another
     */
    $('#the-list').on('click', '.editinline', function (e) {
        e.preventDefault();
        var tr_id = $(e.currentTarget).parents('tr').attr('id');
        var target_img = $('td.' + i10n_ATMFImages.custom_column_name + ' img', '#' + tr_id);
        var img_id;
        var img_src;
        var sl = $('.set-term-thumbnail', '.inline-edit-row');
        var sl_parent = sl.closest('.inside');
        var dl;
        var image = '';

        // if there's an image
        if (target_img.length > 0) {
            img_id = target_img.data('id');
            img_src = target_img.attr('src');
            image = {id: img_id, url: img_src};

            // remove the delete link
            clear_delete_link(sl_parent);

            // build the thumbnail image
            img_html = build_thumbnail_html(image);

            //build the delete link
            dl = build_delete_link_html(image, sl);

            // wrap the image in the set link
            sl.removeClass('button').html(img_html).after(dl);
            
        } else {
            reset_set_link(sl_parent);
            clear_delete_link(sl_parent);
        }

        $(':input[name="' + i10n_ATMFImages.meta_key + '"]', '.inline-edit-row').val(img_id);
    });


})(jQuery);