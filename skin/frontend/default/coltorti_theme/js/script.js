jQuery("document").ready(function() {
    if (jQuery(window).width()>770) {
        jQuery('.visibile').bind('inview', function (event, visible) {
            if (visible) {
                jQuery(this).stop().animate({opacity: 1, marginTop: "0px"},400).transition({scale: 1}, 1000);
            }
        });
        jQuery('.visibile2').bind('inview', function (event, visible) {
            if (visible) {
                jQuery(this).stop().animate({opacity: 1, marginTop: "-100px"},400).transition({scale: 1}, 1000);
            }
        });
        jQuery('.visibile3').bind('inview', function (event, visible) {
            if (visible) {
                jQuery(this).stop().animate({opacity: 1, marginTop: "60px"},400).transition({scale: 1}, 1000);
            }
        });
        jQuery('.visibile4').bind('inview', function (event, visible) {
            if (visible) {
                jQuery(this).stop().animate({opacity: 1, marginTop: "120px"},400).transition({scale: 1}, 1000);
            }
        });
    }

    jQuery(window).resize(function () {
        if (jQuery(window).width()>770) {
            jQuery('.visibile').bind('inview', function (event, visible) {
                if (visible) {
                    jQuery(this).stop().animate({opacity: 1, marginTop: "0px"},400).transition({scale: 1}, 1000);
                }
            });
            jQuery('.visibile2').bind('inview', function (event, visible) {
                if (visible) {
                    jQuery(this).stop().animate({opacity: 1, marginTop: "-100px"},400).transition({scale: 1}, 1000);
                }
            });
            jQuery('.visibile3').bind('inview', function (event, visible) {
                if (visible) {
                    jQuery(this).stop().animate({opacity: 1, marginTop: "60px"},400).transition({scale: 1}, 1000);
                }
            });
            jQuery('.visibile4').bind('inview', function (event, visible) {
                if (visible) {
                    jQuery(this).stop().animate({opacity: 1, marginTop: "120px"},400).transition({scale: 1}, 1000);
                }
            });
        }
        else {
            jQuery('.visibile').bind('inview', function (event, visible) {
                if (visible) {
                    jQuery(this).stop();
                }
            });
            jQuery('.visibile2').bind('inview', function (event, visible) {
                if (visible) {
                    jQuery(this).stop();
                }
            });
            jQuery('.visibile3').bind('inview', function (event, visible) {
                if (visible) {
                    jQuery(this).stop();
                }
            });
            jQuery('.visibile4').bind('inview', function (event, visible) {
                if (visible) {
                    jQuery(this).stop();
                }
            });
        }
    });
});