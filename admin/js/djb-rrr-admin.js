(function ($) {
    'use strict';

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


