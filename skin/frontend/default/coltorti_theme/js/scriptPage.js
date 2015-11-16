jQuery(document).ready(function() {
    jQuery("#header-account").css("display", "block");
    //if (jQuery(window).width()>770) {



    // sposto il carrello e l'account sui menu in alto alla pagina
    jQuery("#header-cart").appendTo("#menu2");
    jQuery("#header-account").appendTo("#menu3");

    // menu men women brands alto
    jQuery(".menu-link").click(function () {
        if (jQuery("#header-nav").hasClass("open")) {
            // chiudi menu alto
            jQuery('#man_link').css("text-decoration", "none");
            jQuery('#women_link').css("text-decoration", "none");
            jQuery('#brands_link').css("text-decoration", "none");
            jQuery("#menu_man").removeClass("slidedown").addClass("slideup");
            jQuery("#menu_women").removeClass("slidedown").addClass("slideup");
            jQuery("#menu_designer").removeClass("slidedown").addClass("slideup");

            // chiudo menu search
            jQuery("#search_menu").fadeOut(1000, 0);
            jQuery("#search_menu").css("display", "none");
            jQuery("#search-nav").removeClass("open");

            chiudiCarrello();

            chiudiAccount();

            jQuery('.main-container').fadeTo(1000, 0.08);
            jQuery('.footer-container').fadeTo(1000, 0.08);
            jQuery("#menu").fadeTo(1000, 1);

        }
        else {


            jQuery('.main-container').fadeTo(1000, 1);
            jQuery('.footer-container').fadeTo(1000, 1);
            jQuery("#menu").fadeOut(1000, 0);
            setTimeout(function () {
                jQuery("#menu").css("display", "none");
            }, 1000);

        }
    });

    // search
    jQuery(".menu-search").click(function () {
        if (jQuery("#search-nav").hasClass("open")) {
            // chiudi menu alto
            jQuery('#man_link').css("text-decoration", "none");
            jQuery('#women_link').css("text-decoration", "none");
            jQuery('#brands_link').css("text-decoration", "none");
            jQuery("#menu_man").removeClass("slidedown").addClass("slideup");
            jQuery("#menu_women").removeClass("slidedown").addClass("slideup");
            jQuery("#menu_designer").removeClass("slidedown").addClass("slideup");
            // chiudo menu search
            jQuery("#menu").fadeOut(1000, 0);
            jQuery("#menu").css("display", "none");
            jQuery("#header-nav").removeClass("open");

            chiudiCarrello();

            chiudiAccount();

            jQuery('.main-container').fadeTo(1000, 0.08);
            jQuery('.footer-container').fadeTo(1000, 0.08);
            jQuery("#search_menu").fadeTo(1000, 1);

        }
        else {


            jQuery('.main-container').fadeTo(1000, 1);
            jQuery('.footer-container').fadeTo(1000, 1);
            jQuery("#search_menu").fadeOut(1000, 0);
            setTimeout(function () {
                jQuery("#search_menu").css("display", "none");
            }, 1000);

        }
    });

    // menu carrello destra
    jQuery("#cart_btn").click(function () {
        if (jQuery("#cart_btn").hasClass("open")) {
            chiudiCarrello();
        }
        else {
            apriCarrello();
        }
    });

    jQuery(".main-container").click(function () {
        if (jQuery("#header-nav").hasClass("open")) {

        }
        else {
            chiudiCarrello();
            chiudiAccount();
        }

    });

    jQuery(".close").click(function () {
        chiudiCarrello()

    });


    // menu account destra
    jQuery("#account_btn").click(function () {
        if (jQuery("#account_btn").hasClass("open")) {
            chiudiAccount();
        }
        else {
            apriAccount();
        }
    });

    jQuery('#header-nav-mobile-a').sidr();

    jQuery(".newsletter_in .tipo_footer").click(function () {
        if (jQuery(this).hasClass("selected")){
            jQuery(".newsletter_in .tipo_footer").removeClass("selected");
            jQuery("#genere").val("");
        }
        else {
            jQuery(".newsletter_in .tipo_footer").removeClass("selected");
            valore = jQuery(this).html();
            valore = valore.toLowerCase();
            if (valore=="donna"){
                valore=2;
            }
            else if (valore=="uomo"){
                valore=1;
            }
            jQuery("#genere").val(valore);
            jQuery(this).addClass("selected");
        }
    });


    jQuery( "#btn_search" ).click(function() {
        // Set the effect type
        var effect = 'slide';

        // Set the options for the effect type chosen
        var options = { direction: 'right' };

        // Set the duration (default: 400 milliseconds)
        var duration = 500;

        jQuery("#search").toggle(effect, options, duration);
    });


});

jQuery(window).load(function() {
    // posizione footer (25 altezza breadcrumb)
    adattaMain();

    if (jQuery(document).scrollTop()<=0){
        jQuery( ".scroll_down").css("opacity","1");
    }
    else {
        jQuery( ".scroll_down").css("opacity","0");
    }

    jQuery(window).resize(function () {
        adattaMain();
    });

    width=jQuery(".newsletter_in").width();
    width2=jQuery(window).width();
    margine=(width2-width)/2;
    jQuery(".newsletter_in").css("margin-left",margine+"px");

    apriMenuSinistra();
    apriMenuAlto();
    apriCerca();
});

jQuery( window ).scroll(function() {
    if (jQuery(document).scrollTop()<=0){
        jQuery( ".scroll_down").css("opacity","1");
    }
    else {
        jQuery( ".scroll_down").css("opacity","0");
    }

    width=jQuery(".newsletter_in").width();
    width2=jQuery(window).width();
    margine=(width2-width)/2;
    jQuery(".newsletter_in").css("margin-left",margine+"px");
});

function apriAccount() {
    // chiudo menu alto
    jQuery('#man_link').css("text-decoration", "none");
    jQuery('#women_link').css("text-decoration", "none");
    jQuery('#brands_link').css("text-decoration", "none");
    jQuery("#menu_man").removeClass("slidedown").addClass("slideup");
    jQuery("#menu_women").removeClass("slidedown").addClass("slideup");
    jQuery("#menu_designer").removeClass("slidedown").addClass("slideup");

    // chiudo menu sinistra
    jQuery("#menu").fadeOut(1000, 0);
    jQuery("#menu").css("display", "none");
    jQuery("#header-nav").removeClass("open");

    // chiudo menu search
    jQuery("#search_menu").fadeOut(1000, 0);
    jQuery("#search_menu").css("display", "none");
    jQuery("#search-nav").removeClass("open");


    chiudiCarrello();

    jQuery("body").find(".push").css("left", "-20%");
    jQuery('#menu3').css("right", "0px");
    jQuery("#account_btn").addClass("open");
    jQuery('.main-container').css("opacity", "0.08");
}

function chiudiAccount() {
    jQuery("#account_btn").removeClass("open");
    jQuery('#menu3').css("right", "-20%");
    jQuery("body").find(".push").css("left", "0");
    jQuery('.main-container').css("opacity", "1");
}

function apriCarrello() {
    // chiudo menu alto
    jQuery('#man_link').css("text-decoration", "none");
    jQuery('#women_link').css("text-decoration", "none");
    jQuery('#brands_link').css("text-decoration", "none");
    jQuery("#menu_man").removeClass("slidedown").addClass("slideup");
    jQuery("#menu_women").removeClass("slidedown").addClass("slideup");
    jQuery("#menu_designer").removeClass("slidedown").addClass("slideup");

    // chiudo menu sinistra
    jQuery("#menu").fadeOut(1000, 0);
    jQuery("#menu").css("display", "none");
    jQuery("#header-nav").removeClass("open");

    // chiudo menu search
    jQuery("#search_menu").fadeOut(1000, 0);
    jQuery("#search_menu").css("display", "none");
    jQuery("#search-nav").removeClass("open");


    chiudiAccount();

    jQuery("body").find(".push").css("left", "-25%");
    jQuery('#menu2').css("right", "0px");
    jQuery("#cart_btn").addClass("open");
    jQuery('.main-container').css("opacity", "0.08");
}

function chiudiCarrello() {
    jQuery("#cart_btn").removeClass("open");
    jQuery('#menu2').css("right", "-25%");
    jQuery("body").find(".push").css("left", "0");
    jQuery('.main-container').css("opacity", "1");
}


function adattaMain() {
    altezzaPagina=jQuery(window).height();
    altezzaHeader=jQuery("#header").height();
    altezzaMain=jQuery(".main-container").height();
    altezzaFooter=jQuery(".footer-container").height();
    if (altezzaPagina>(altezzaMain+altezzaFooter+altezzaHeader+25)){
        differenza=altezzaPagina-(altezzaHeader+altezzaFooter+25);
        jQuery(".main-container").css("height",differenza+"px");
    }


    altezza = jQuery(window).height();
    alt = altezza - 350;
    jQuery("#cart-sidebar").css("height", alt + "px");

    alt2=altezza-280;
    jQuery("#designer_man").css("height",alt2+"px");
    jQuery("#designer_women").css("height",alt2+"px");


    jQuery("#cart-sidebar").mCustomScrollbar();
    jQuery("#designer_man").mCustomScrollbar();
    jQuery("#designer_women").mCustomScrollbar();
    jQuery("#menu_designer_scroll").mCustomScrollbar();
    if (jQuery(".mCSB_container").hasClass("mCS_no_scrollbar_y")) {
        jQuery(".mCustomScrollBox").css("width", "100%");
    }
    else {
        jQuery(".mCustomScrollBox").css("width", "112%");
    }




    background=jQuery(document).height();
    background2=jQuery(document).height()-100;
    jQuery("#background").css("height",background+"px");
    jQuery("#background2").css("height",background2+"px");
}

function disableMenu() {

    jQuery('.main-container').fadeTo(1000, 1);
    jQuery('.footer-container').fadeTo(1000, 1);
    jQuery("#menu").fadeOut(1000, 0);
    jQuery("#header-nav").removeClass("open");
    setTimeout(function () {
        jQuery("#menu").css("display", "none");
    }, 1000);

}



function apriMenuSinistra(){
    jQuery('#header-nav').click(function () {
        jQuery(this).toggleClass('open');
    });

}

function apriCerca(){
    jQuery('#search-nav').click(function () {
        jQuery(this).toggleClass('open');
    });

}


function apriMenuAlto(){
    jQuery('#man_link').mouseenter(function (event) {
        jQuery('#man_link').css("text-decoration", "underline");
    });
    jQuery('#man_link').mouseleave(function (event) {
        if (jQuery("#menu_man").hasClass("slideup")) {
            jQuery('#man_link').css("text-decoration", "none");
        }
    });
    jQuery('#women_link').mouseenter(function (event) {
        jQuery('#women_link').css("text-decoration", "underline");
    });
    jQuery('#women_link').mouseleave(function (event) {
        if (jQuery("#menu_women").hasClass("slideup")) {
            jQuery('#women_link').css("text-decoration", "none");
        }
    });
    jQuery('#brands_link').mouseenter(function (event) {
        jQuery('#brands_link').css("text-decoration", "underline");
    });
    jQuery('#brands_link').mouseleave(function (event) {
        if (jQuery("#menu_designer").hasClass("slideup")) {
            jQuery('#brands_link').css("text-decoration", "none");
        }
    });

    jQuery('#brands_link').click(function (event) {


        event.preventDefault();

        // chiudi menu sinistra
        jQuery('.main-container').fadeTo(1000, 1);
        jQuery("#menu").fadeOut(1000, 0);
        jQuery("#menu").css("display", "none");
        jQuery("#header-nav").removeClass("open");

        // chiudo menu search
        jQuery("#search_menu").fadeOut(1000, 0);
        jQuery("#search_menu").css("display", "none");
        jQuery("#search-nav").removeClass("open");

        // chiudi carrello
        jQuery("#cart_btn").removeClass("open");
        jQuery('#menu2').css("right", "-25%");
        jQuery("body").find(".push").css("left", "0");
        jQuery('#background2').css("display", "none");

        // chiudi account
        jQuery("#account_btn").removeClass("open");
        jQuery('#menu3').css("right", "-25%");
        jQuery("body").find(".push").css("left", "0");
        jQuery('.main-container').css("opacity", "1");

        flag = false;
        jQuery('#man_link').css("text-decoration", "none");
        jQuery('#women_link').css("text-decoration", "none");
        if (jQuery("#menu_man").hasClass("slidedown")) {
            jQuery("#menu_man").removeClass("slidedown").addClass("slideup");
            //jQuery("#menu_man").css("height","");
            flag = true;
        }
        if (jQuery("#menu_women").hasClass("slidedown")) {
            jQuery("#menu_women").removeClass("slidedown").addClass("slideup");
            //jQuery("#menu_women").css("height","");
            flag = true;
        }
        if (flag == true) {
            setTimeout(function () {
                if (jQuery("#menu_designer").hasClass("slideup")) {
                    jQuery('#brands_link').css("text-decoration", "underline");
                    height = jQuery(window).height();
                    alt = height-100;
                    jQuery("#menu_designer").css("height", alt + "px");
                    jQuery("#menu_designer_scroll").css("height", alt + "px");
                    jQuery("#menu_designer").removeClass("slideup").addClass("slidedown");
                }
                else {
                    jQuery('#brands_link').css("text-decoration", "none");
                    jQuery("#menu_designer").removeClass("slidedown").addClass("slideup");
                    //jQuery("#menu_designer").css("height","");
                }
            }, 600);
        }
        else {
            if (jQuery("#menu_designer").hasClass("slideup")) {
                height = jQuery(window).height();
                alt = height-100;
                jQuery("#menu_designer").css("height", alt + "px");
                jQuery("#menu_designer_scroll").css("height", alt + "px");
                jQuery('#brands_link').css("text-decoration", "underline");
                jQuery("#menu_designer").removeClass("slideup").addClass("slidedown");
            }
            else {
                jQuery('#brands_link').css("text-decoration", "none");
                jQuery("#menu_designer").removeClass("slidedown").addClass("slideup");
                // jQuery("#menu_designer").css("height","");
            }
        }

    });
    jQuery('#man_link').click(function (event) {
        event.preventDefault();

        // chiudi menu sinistra
        jQuery('.main-container').fadeTo(1000, 1);
        jQuery("#menu").fadeOut(1000, 0);
        jQuery("#menu").css("display", "none");
        jQuery("#header-nav").removeClass("open");

        // chiudo menu search
        jQuery("#search_menu").fadeOut(1000, 0);
        jQuery("#search_menu").css("display", "none");
        jQuery("#search-nav").removeClass("open");

        // chiudi carrello
        jQuery("#cart_btn").removeClass("open");
        jQuery('#menu2').css("right", "-25%");
        jQuery("body").find(".push").css("left", "0");
        jQuery('#background2').css("display", "none");

        // chiudi account
        jQuery("#account_btn").removeClass("open");
        jQuery('#menu3').css("right", "-25%");
        jQuery("body").find(".push").css("left", "0");
        jQuery('.main-container').css("opacity", "1");

        flag = false;
        jQuery('#brands_link').css("text-decoration", "none");
        jQuery('#women_link').css("text-decoration", "none");
        if (jQuery("#menu_designer").hasClass("slidedown")) {
            jQuery("#menu_designer").removeClass("slidedown").addClass("slideup");
            //jQuery("#menu_designer").css("height","");
            flag = true;
        }
        if (jQuery("#menu_women").hasClass("slidedown")) {
            jQuery("#menu_women").removeClass("slidedown").addClass("slideup");
            // jQuery("#menu_women").css("height","");
            flag = true;
        }
        if (flag == true) {
            setTimeout(function () {
                if (jQuery("#menu_man").hasClass("slideup")) {
                    jQuery('#man_link').css("text-decoration", "underline");
                    height = jQuery(window).height();
                    alt = height;
                    jQuery("#menu_man").css("height", alt + "px");
                    jQuery("#menu_man").removeClass("slideup").addClass("slidedown");
                }
                else {
                    jQuery('#man_link').css("text-decoration", "none");
                    jQuery("#menu_man").removeClass("slidedown").addClass("slideup");
                    //   jQuery("#menu_man").css("height","");

                }
            }, 600);
        }
        else {
            if (jQuery("#menu_man").hasClass("slideup")) {
                jQuery('#man_link').css("text-decoration", "underline");
                height = jQuery(window).height();
                alt = height;
                jQuery("#menu_man").css("height", alt + "px");
                jQuery("#menu_man").removeClass("slideup").addClass("slidedown");
            }
            else {
                jQuery('#man_link').css("text-decoration", "none");
                jQuery("#menu_man").removeClass("slidedown").addClass("slideup");
                // jQuery("#menu_man").css("height","");
            }
        }
    });
    jQuery('#women_link').click(function (event) {
        event.preventDefault();

        // chiudi menu sinistra
        jQuery('.main-container').fadeTo(1000, 1);
        jQuery("#menu").fadeOut(1000, 0);
        jQuery("#menu").css("display", "none");
        jQuery("#header-nav").removeClass("open");

        // chiudo menu search
        jQuery("#search_menu").fadeOut(1000, 0);
        jQuery("#search_menu").css("display", "none");
        jQuery("#search-nav").removeClass("open");

        // chiudi carrello
        jQuery("#cart_btn").removeClass("open");
        jQuery('#menu2').css("right", "-25%");
        jQuery("body").find(".push").css("left", "0");
        jQuery('#background2').css("display", "none");

        // chiudi account
        jQuery("#account_btn").removeClass("open");
        jQuery('#menu3').css("right", "-25%");
        jQuery("body").find(".push").css("left", "0");
        jQuery('.main-container').css("opacity", "1");

        jQuery('#man_link').css("text-decoration", "none");
        jQuery('#brands_link').css("text-decoration", "none");
        flag = false;
        if (jQuery("#menu_designer").hasClass("slidedown")) {
            jQuery("#menu_designer").removeClass("slidedown").addClass("slideup");
            //jQuery("#menu_designer").css("height","");
            flag = true;
        }
        if (jQuery("#menu_man").hasClass("slidedown")) {
            jQuery("#menu_man").removeClass("slidedown").addClass("slideup");
            // jQuery("#menu_man").css("height","");
            flag = true;
        }
        if (flag == true) {
            setTimeout(function () {
                if (jQuery("#menu_women").hasClass("slideup")) {
                    jQuery('#women_link').css("text-decoration", "underline");
                    height = jQuery(window).height();
                    alt = height;
                    jQuery("#menu_women").css("height", alt + "px");
                    jQuery("#menu_women").removeClass("slideup").addClass("slidedown");

                }
                else {
                    jQuery('#women_link').css("text-decoration", "none");
                    jQuery("#menu_women").removeClass("slidedown").addClass("slideup");
                    //  jQuery("#menu_women").css("height","");

                }
            }, 600);
        }
        else {
            if (jQuery("#menu_women").hasClass("slideup")) {
                jQuery('#women_link').css("text-decoration", "underline");
                height = jQuery(window).height();
                alt = height;
                jQuery("#menu_women").css("height", alt + "px");
                jQuery("#menu_women").removeClass("slideup").addClass("slidedown");
            }
            else {
                jQuery('#women_link').css("text-decoration", "none");
                jQuery("#menu_women").removeClass("slidedown").addClass("slideup");
                // jQuery("#menu_women").css("height","");
            }
        }

    });
}



