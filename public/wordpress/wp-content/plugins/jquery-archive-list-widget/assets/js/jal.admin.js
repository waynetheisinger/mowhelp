/*global jQuery:false */
"use strict";

jQuery(function () {
    jQuery("body").on("change", ".js-jawl-type", function (e) {
        var excludeList = jQuery(this).parent().siblings(".jawl-exclude");

        if (jQuery(this).val() === "post") {
            excludeList.show();
        } else {
            excludeList.hide();
            jQuery(".jawl-exclude input[type='checkbox']").prop('checked', false);
        }

        jQuery(this).on("click", "li.jaw_years a.jaw_years, li.jaw_months a.jaw_months", function (e) {
            var elements = jQuery(this).siblings("ul").children("li");

            if (elements.length) {
                e.preventDefault();
                jqueryArchiveListAnimate(this, elements, options);
            }
        });
    });
});