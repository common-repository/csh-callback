jQuery(document).ready(function($) {  

    $(".cshcb-trash").click(function(e) {
        var ask_delete = confirm('Do you want delete all callback information of this client?');
        if (ask_delete == true) {
            //ajax
            var tr_parent = $(this).parents('tr');
            tr_parent.hide();
            var request_id = tr_parent.attr('callback-id');
            var data = {
                'action': 'delete_callback',
                'request_id': request_id
            };
            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                //
            });
        }
    });

    $(document).on('click', ".cshcb-not-call", function() {
        var ask_called= confirm('Change status to Called?');
        if (ask_called == true) {
            $(this).css('color', '#6262e0');
            $(this).removeClass('cshcb-not-call');
            $(this).addClass('cshcb-called');
            //ajax
            var tr_parent = $(this).parents('tr');
            var request_id = tr_parent.attr('callback-id');
            var data = {
                'action': 'change_to_called',
                'request_id': request_id
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                //
            });
        }       
    });

    $(document).on('click', ".cshcb-called", function() {
        var ask_not_call = confirm('Change status to Not Called?');
        if (ask_not_call == true) {
            $(this).css('color', '#ff0000');
            $(this).removeClass('cshcb-called');
            $(this).addClass('cshcb-not-call');
            //ajax
            var tr_parent = $(this).parents('tr');
            var request_id = tr_parent.attr('callback-id');
            var data = {
                'action': 'change_to_notcall',
                'request_id': request_id
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                //
            });
        }    
    });

});