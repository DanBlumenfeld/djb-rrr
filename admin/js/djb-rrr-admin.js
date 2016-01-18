(function ($) {
    'use strict';

    $(function () {

        //Add handler to show/hide route type-specific data
        $("#djb_rrr_route_type_val").change(function () {
            var str = "";
            $("#djb_rrr_route_type_val option:selected").each(function () {
                str += $(this).data("route-type");
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


