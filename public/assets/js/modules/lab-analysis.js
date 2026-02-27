/**
 * Laboratory Analysis JavaScript Module
 * Handles common functionality for kiln performance and stock quality analysis
 */

(function($) {
    'use strict';

    $(function() {
        initializeAutoSubmit();
    });

    /**
     * Auto-submit form when date inputs change
     */
    function initializeAutoSubmit() {
        $('input[type="date"]').on('change', function() {
            const $form = $(this).closest('form');
            if ($form.length) {
                $form.submit();
            } else if ($(this).attr('form')) {
                // Handle cases where form is associated via 'form' attribute
                document.getElementById($(this).attr('form')).submit();
            }
        });
    }

})(jQuery);
