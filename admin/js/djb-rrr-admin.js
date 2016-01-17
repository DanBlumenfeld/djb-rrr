(function ($) {
    'use strict';

    /**
    * All of the code for your admin-facing JavaScript source
    * should reside in this file.
    *
    * Note: It has been assumed you will write jQuery code here, so the
    * $ function reference has been prepared for usage within the scope
    * of this function.
    *
    * This enables you to define handlers, for when the DOM is ready:
    *
    * $(function() {
    *
    * });
    *
    * When the window is loaded:
    *
    * $( window ).load(function() {
    *
    * });
    *
    * ...and/or other possibilities.
    *
    * Ideally, it is not considered best practise to attach more than a
    * single DOM-ready or window-load handler for a particular page.
    * Although scripts in the WordPress core, Plugins and Themes may be
    * practising this, we should strive to set a better example in our own work.
    */
    $(function () {

        $("#djb_rrr_route_type_val").change(function () {
            //alert("Handler for .change() called.");
            var str = "";
            $("#djb_rrr_route_type_val option:selected").each(function () {
                str += $(this).data("route-type");
                // alert(str);
            });

            $(".hideable_route_type_data").each(function () {
                if ($(this).data("route-type") == str) {
                    $(this).show();
                }
                else {
                    $(this).hide();
                }
            });
        });
    });



})(jQuery);


