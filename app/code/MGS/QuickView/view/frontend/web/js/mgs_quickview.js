define([
    'jquery',
    'magnificPopup'
], function ($, magnificPopup) {
    "use strict";
    return {
        displayContent: function (prodUrl) {
            if (!prodUrl.length) {
                return false;
            }
            var url = QUICKVIEW_BASE_URL + 'mgs_quickview/index/updatecart';
            $.magnificPopup.open({
                items: {
                    src: prodUrl
                },
                type: 'iframe',
                removalDelay: 300,
                mainClass: 'mfp-fade',
                closeOnBgClick: true,
                preloader: true,
                tLoading: '',
                callbacks: {
                    open: function () {
                        $('.mfp-preloader').css('display', 'block');
                    },
                    beforeClose: function () {
                        $('[data-block="minicart"]').trigger('contentLoading');
                        $.ajax({
                            url: url,
                            method: "POST"
                        });
                    },
                    close: function () {
                        $('.mfp-preloader').css('display', 'none');
                    }
                }
            });
        }
    };
});
