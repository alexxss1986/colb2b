jQuery(window).load(function () {

    jQuery(".main-container").css("display", "block");
    jQuery(".footer-container").css("display", "block");

    $width = jQuery(window).width(); // Width of the screen
    altezzaWindow = jQuery(window).height(); // Height of the screen

    jQuery("body").css("overflow", "auto").delay(600);
    jQuery(".menu-link").css("display", "block");

    adatta();
    caricaSlider(altezzaWindow);
    if(window.location.hash) {
        var target_offset = jQuery("#yps").offset();
        var target_top = target_offset.top;


        jQuery('html').animate({scrollTop: target_top}, 1);
        jQuery('body').animate({scrollTop: target_top}, 1);
    }


    jQuery(window).resize(function () {
        adatta();
        caricaSliderResize();
    });


    altezza=jQuery("#video_campaign_figure").height();
    jQuery("#video_campaignfw_figure").css("height",altezza+"px");
    jQuery("#video_campaignfw_figure").css("width","100%");
    jQuery("#video_campaignfw_figure video").css("height",altezza+"px");


});

jQuery( window ).scroll(function() {
    if (jQuery(document).scrollTop()<=0){
        jQuery( ".scroll_down").css("opacity","1");
    }
    else {
        jQuery( ".scroll_down").css("opacity","0");
    }

});



function caricaSliderResize() {
    height = jQuery(window).height();
    altezza = height - 170;
    jQuery(".bx-wrapper").css("height", altezza + "px");
    jQuery(".bx-viewport").css("height", altezza + "px");
    jQuery(".bxslider li").css("height", altezza + "px");
    jQuery(".bxslider li").find("img").css("height", altezza + "px");

    larghezza = (1920 * altezza) / 960;



    if (jQuery(window).width()>1400) {
        larghTesto = [0,150, 874, 518, 745, 815];
        altTesto = [0,80, 164, 164, 164, 164];
    }
    else {
        larghTesto = [0,10, 600, 421, 524, 498];
        altTesto = [0,150, 164, 164, 164, 164];
    }

    i = 0;
    jQuery(".bxslider li").each(function () {
        alt = altTesto[i];
        marginTop = -(((altezza - alt) / 2) + alt);
        jQuery(this).find(".content-slider-bx").css("margin-top", marginTop + "px");

        larg = larghTesto[i];
        marginLeft = (larghezza - larg) / 2;
        jQuery(this).find(".content-slider-bx").css("margin-left", marginLeft + "px");
        i = i + 1;
    });


    jQuery(".div_slider").css("width", larghezza + "px");

    padding = (altezza - 62) / 2;
    jQuery(".bx-pager").css("padding-top", padding + "px");
}

function caricaSlider(height) {
    altezza = height - 170;
    jQuery(".bx-wrapper").css("height", altezza + "px");
    jQuery(".bx-viewport").css("height", altezza + "px");
    jQuery(".bxslider li").css("height", altezza + "px");
    jQuery(".bxslider li").find("img").css("height", altezza + "px");

    larghezza = jQuery(".img_slider").width();

    if (larghezza>(jQuery(window).width()-176)){
        larghezza=jQuery(window).width()-176;
        altezza=960*larghezza/1920;
        jQuery(".bx-wrapper").css("height", altezza + "px");
        jQuery(".bx-viewport").css("height", altezza + "px");
        jQuery(".bxslider li").css("height", altezza + "px");
        jQuery(".bxslider li").find("img").css("height", altezza + "px");
    }

    if (jQuery(window).width()>1400) {
        larghTesto = [-150, 1300,1000,700,682];
        altTesto = [80, 80,80,315,35];
    }
    else if (jQuery(window).width()>940 || jQuery(window).width()<=1005 ) {
        larghTesto = [10, 700,700,500,346];
        altTesto = [150, 150,150,209,35];
    }
    else {
        larghTesto = [10, 800,800,500,346];
        altTesto = [150, 150,150,209,35];
    }

    i = 0;
    jQuery(".bxslider li").each(function () {
        if (i==2){
            alt = altTesto[i];
            //alt = jQuery(this).find(".content-slider-bx").height();
            marginTop = -(((altezza + alt) / 2));
            jQuery(this).find(".content-slider-bx").css("margin-top", marginTop + "px");
        }
        else {
            alt = altTesto[i];
            //alt = jQuery(this).find(".content-slider-bx").height();
            marginTop = -(((altezza - alt) / 2) + alt);
            jQuery(this).find(".content-slider-bx").css("margin-top", marginTop + "px");
        }

        larg = larghTesto[i];
        marginLeft = (larghezza - larg) / 2;
        jQuery(this).find(".content-slider-bx").css("margin-left", marginLeft + "px");
        i = i + 1;
    });

    jQuery('.bxslider').bxSlider({
        auto: true,
        autoControls: false,
        mode: 'fade',
        useCSS: false,
        easing: 'easeInOut',
        speed: 4000,
        pause: 4000,
        autoHover: true,
        captions: false,
        adaptiveHeight: true
    });


    jQuery(".div_slider").css("width", larghezza + "px");

    padding = (altezza - 62) / 2;
    jQuery(".bx-pager").css("padding-top", padding + "px");
}


function adatta(height) {
    if (jQuery(window).width()>770){
        jQuery(".blocco5 #block3.desktop").css("display","block");
        jQuery(".blocco5 #block3.mobile").css("display","none");
    }
    else {
        jQuery(".blocco5 #block3.desktop").css("display","none");
        jQuery(".blocco5 #block3.mobile").css("display","block");
    }

//blocco designer
    altezzaDesigner = jQuery(".blocco1 #block11 .designers").height();
    altezzaBlocco2 = jQuery(".blocco1 #block12").height();
    margine = (altezzaBlocco2 - altezzaDesigner) / 2;
    jQuery(".blocco1 #block11 .designers").css("margin-top", margine + "px");


    //blocco donna
    larghezzaTot = jQuery(window).width();
    if (jQuery(window).width()>940) {
        larghezzaTot = Math.floor(larghezzaTot / 5);
        jQuery(".blocco4 #block41").css("height", larghezzaTot);
    }
    else {
        larghezzaTot = Math.floor(larghezzaTot / 2);
        jQuery(".blocco4 #block41").css("height", larghezzaTot);
    }
    jQuery(".blocco4 #block42").css("width", larghezzaTot);
    jQuery(".blocco4 #block43").css("width", larghezzaTot);
    jQuery(".blocco4 #block44").css("width", larghezzaTot);

    jQuery(".blocco4 figure").css("width", larghezzaTot);
    altezzaTesto = jQuery(".blocco4 #block41 .testo").height();
    margine = (larghezzaTot - (altezzaTesto + 20)) / 2;
    jQuery(".blocco4 #block41 .testo").css("margin-top", margine + "px");
    lunghezzaTesto = jQuery(".blocco4 #block41 .testo").width();
    lunghezzaB = jQuery(".blocco4 #block41").width();
    margine = (lunghezzaB - (lunghezzaTesto + 20)) / 2;
    jQuery(".blocco4 #block41 .testo").css("margin-left", margine + "px");

    alt1 = jQuery(".blocco4 figure figcaption").height();
    alt2 = jQuery(".blocco4 figure figcaption p").height();
    margine = (alt1 - alt2) / 2;
    jQuery(".blocco4 figure figcaption p").css("margin-top", margine + "px");

    //blocco uomo
    larghezzaTot = jQuery(window).width();
    if (jQuery(window).width()>940) {
        larghezzaTot = Math.floor(larghezzaTot / 5);
        jQuery(".blocco5 #block53").css("height", larghezzaTot);
    }
    else {
        larghezzaTot = Math.floor(larghezzaTot / 2);
        jQuery(".blocco5 #block53_mobile").css("height", larghezzaTot);
    }

    jQuery(".blocco5 #block51").css("width", larghezzaTot);
    jQuery(".blocco5 #block52").css("width", larghezzaTot);
    jQuery(".blocco5 #block54").css("width", larghezzaTot);
    jQuery(".blocco5 figure").css("width", larghezzaTot);

    alt1 = jQuery(".blocco5 figure figcaption").height();
    alt2 = jQuery(".blocco5 figure figcaption p").height();
    margine = (alt1 - alt2) / 2;
    jQuery(".blocco5 figure figcaption p").css("margin-top", margine + "px");

    if (jQuery(window).width()>940) {
        altezzaTesto = jQuery(".blocco5 #block53.desktop .testo").height();
        margine = (larghezzaTot - (altezzaTesto + 20)) / 2;
        jQuery(".blocco5 #block53.desktop .testo").css("margin-top", margine + "px");
        lunghezzaTesto = jQuery(".blocco5 #block53.desktop .testo").width();
        lunghezzaB = jQuery(".blocco5 #block53.desktop").width();
        margine = (lunghezzaB - (lunghezzaTesto + 20)) / 2;
        jQuery(".blocco5 #block53.desktop .testo").css("margin-left", margine + "px");
    }
    else {
        altezzaTesto = jQuery(".blocco5 #block53_mobile.mobile .testo").height();
        margine = (larghezzaTot - (altezzaTesto + 20)) / 2;
        jQuery(".blocco5 #block53_mobile.mobile .testo").css("margin-top", margine + "px");
        lunghezzaTesto = jQuery(".blocco5 #block53_mobile.mobile .testo").width();
        lunghezzaB = jQuery(".blocco5 #block53_mobile.mobile").width();
        margine = (lunghezzaB - (lunghezzaTesto + 20)) / 2;
        jQuery(".blocco5 #block53_mobile.mobile .testo").css("margin-left", margine + "px");
    }


    //blocco boutique
    altezzaC = jQuery("#img_coltorti").height();
    jQuery(".blocco2 #block21").css("height", altezzaC);
    jQuery(".blocco2 #block23").css("height", altezzaC);
    altezzaTesto = jQuery(".blocco2 #block21 .boutique").height();
    margine = (altezzaC - (altezzaTesto + 22)) / 2;
    jQuery(".blocco2 #block21 .boutique").css("margin-top", margine + "px");
    lunghezzaTesto = jQuery(".blocco2 #block21 .boutique").width();
    lunghezzaB = jQuery(".blocco2 #block21").width();
    margine = (lunghezzaB - (lunghezzaTesto + 22)) / 2;
    jQuery(".blocco2 #block21 .boutique").css("margin-left", margine + "px");
    altezzaImg = altezzaC - 200;
    jQuery(".blocco2 #block23 .product_home img").css("height", altezzaImg + "px");
    altezzaProduct = jQuery(".blocco2 #block23 .product_home").height();
    margine = (altezzaC - altezzaProduct) / 2;
    jQuery(".blocco2 #block23 .product_home h5").css("margin-bottom", margine + "px");


    //blocco filosofia
    altezzaB = jQuery("#img_boutique").height();
    jQuery(".blocco3 #block31").css("height", altezzaB);
    jQuery(".blocco3 #block32").css("height", altezzaB);
    altezzaTesto = jQuery(".blocco3 #block32 .coltorti").height();
    margine = (altezzaB - (altezzaTesto + 20)) / 2;
    jQuery(".blocco3 #block32 .coltorti").css("margin-top", margine + "px");
    lunghezzaTesto = jQuery(".blocco3 #block32 .coltorti .sottotitolo").width();
    lunghezzaB = jQuery(".blocco3 #block32").width();
    margine = (lunghezzaB - (lunghezzaTesto + 20)) / 2;
    jQuery(".blocco3 #block32 .coltorti").css("margin-left", margine + "px");
    altezzaTesto = jQuery(".blocco3 #block31 .filosofia").height();
    margine = (altezzaC - (altezzaTesto + 20)) / 2;
    jQuery(".blocco3 #block31 .filosofia").css("margin-top", margine + "px");
    lunghezzaTesto = jQuery(".blocco3 #block31 .filosofia").width();
    lunghezzaB = jQuery(".blocco3 #block31").width();
    margine = (lunghezzaB - (lunghezzaTesto + 20)) / 2;
    jQuery(".blocco3 #block31 .filosofia").css("margin-left", margine + "px");


    //blocco yps
    altezzaC = jQuery("#img_yps_donna").height();
    jQuery(".blocco8 #block81").css("height", altezzaC);
    jQuery(".blocco8 #block82 .block_uomo").css("height", altezzaC);
    jQuery(".blocco8 #block83 .block_donna").css("height", altezzaC);
    altezzaTesto = jQuery(".blocco8 #block81 .yps").height();
    margine = (altezzaC - (altezzaTesto + 20)) / 2;
    jQuery(".blocco8 #block81 .yps").css("margin-top", margine + "px");
    lunghezzaTesto = jQuery(".blocco8 #block81 .yps").width();
    lunghezzaB = jQuery(".blocco8 #block81").width();
    margine = (lunghezzaB - (lunghezzaTesto + 20)) / 2;
    jQuery(".blocco8 #block81 .yps").css("margin-left", margine + "px");

    jQuery(".blocco8 #block82 .block_uomo").css("display", "block");
    jQuery(".blocco8 #block83 .block_donna").css("display", "block");
    alte1 = jQuery(".blocco8 #block82 .block_uomo .stile").height();
    alte2 = jQuery(".blocco8 #block82 .block_uomo .occasione ").height();
    alte3 = jQuery(".blocco8 #block82 .block_uomo .bottone ").height();
    altezza = (altezzaC - (alte2 + alte1 + alte3 + 20 + 50)) / 2;
    jQuery(".blocco8 #block82 .block_uomo .stile").css("margin-top", altezza + "px");
    width1 = jQuery(".blocco8 #block82 .block_uomo .stile .valori .big").width();
    width2 = jQuery(".blocco8 #block82 .block_uomo .occasione .valori .big").width();
    jQuery(".blocco8 #block82 .block_uomo .stile .valori .stile_valore").css("width", (width1 + 32) + "px");
    jQuery(".blocco8 #block82 .block_uomo .occasione .valori .stile_valore").css("width", (width2 + 32) + "px");
    larghezza = jQuery(".blocco8 #block82 .block_uomo").width();
    padding = (larghezza - (((width1 + 32) * 3) + 20)) / 2;
    jQuery(".blocco8 #block82 .block_uomo .stile .valori").css("padding-left", padding + "px");
    padding2 = (larghezza - (((width2 + 32) * 3) + 20)) / 2;
    jQuery(".blocco8 #block82 .block_uomo .occasione .valori").css("padding-left", padding2 + "px");

    alte1 = jQuery(".blocco8 #block83 .block_donna .stile").height();
    alte2 = jQuery(".blocco8 #block83 .block_donna .occasione ").height();
    alte3 = jQuery(".blocco8 #block83 .block_donna .bottone ").height();
    altezza = (altezzaC - (alte2 + alte1 + alte3 + 20 + 50)) / 2;
    jQuery(".blocco8 #block83 .block_donna .stile").css("margin-top", altezza + "px");
    width1 = jQuery(".blocco8 #block83 .block_donna .stile .valori .big").width();
    width2 = jQuery(".blocco8 #block83 .block_donna .occasione .valori .big").width();
    jQuery(".blocco8 #block83 .block_donna .stile .valori .stile_valore").css("width", (width1 + 32) + "px");
    jQuery(".blocco8 #block83 .block_donna .occasione .valori .stile_valore").css("width", (width2 + 32) + "px");
    larghezza = jQuery(".blocco8 #block83 .block_donna").width();
    padding = (larghezza - (((width1 + 32) * 3) + 20)) / 2;
    jQuery(".blocco8 #block83 .block_donna .stile .valori").css("padding-left", padding + "px");
    padding2 = (larghezza - (((width2 + 32) * 3) + 20)) / 2;
    jQuery(".blocco8 #block83 .block_donna .occasione .valori").css("padding-left", padding2 + "px");

    alt1 = jQuery(".blocco8 figure figcaption").height();
    alt2 = jQuery(".blocco8 figure figcaption .crea_stile").height();
    margine = (alt1 - alt2) / 2;
    jQuery(".blocco8 figure figcaption .crea_stile").css("margin-top", margine + "px");

    jQuery(".blocco8 #block82 .block_uomo").css("display", "none");
    jQuery(".blocco8 #block83 .block_donna").css("display", "none");
}

jQuery(document).ready(function(){
    video = document.getElementById('video_campaign');
    flag2=false;
    flag=false;


    jQuery('.effect-bubba #fig_video_campaign').on('click',function(){
        video = document.getElementById('video_campaign');
        if (video.paused === false) {
            video.pause();
            jQuery('#stop_campaign').hide();
            jQuery('#play_campaign').show();
        } else {
            video.play();
            jQuery('#stop_campaign').show();
            jQuery('#play_campaign').hide();
        }
    });

    jQuery('.effect-bubba #fig_video_campaignfw').on('click',function(){
        video = document.getElementById('video_campaignfw');
        if (video.paused === false) {
            video.pause();
            jQuery('#stop_campaignfw').hide();
            jQuery('#play_campaignfw').show();
        } else {
            video.play();
            jQuery('#stop_campaignfw').show();
            jQuery('#play_campaignfw').hide();
        }
    });

    jQuery('.effect-bubba #fig_video_ancona').on('click',function(){
        video = document.getElementById('video_ancona');
        if (video.paused === false) {
            video.pause();
            jQuery('#stop_ancona').hide();
            jQuery('#play_ancona').show();
        } else {
            video.play();
            jQuery('#stop_ancona').show();
            jQuery('#play_ancona').hide();
        }

    });


    jQuery('#video_campaign').on('ended',function(){
        jQuery('#stop_campaign').hide();
        jQuery('#play_campaign').show();
        jQuery('#video_campaign').load();
        flag2=true;
    });

    jQuery('#video_campaignfw').on('ended',function(){
        jQuery('#stop_campaignfw').hide();
        jQuery('#play_campaignfw').show();
        jQuery('#video_campaignfw').load();
        flag=true;
    });

    jQuery('#video_ancona').on('ended',function(){
        jQuery('#stop_ancona').hide();
        jQuery('#play_ancona').show();
        jQuery('#video_ancona').load();
    });


    jQuery(".blocco8 #block82 .block_uomo .stile .value").click(function () {
        if (jQuery(this).hasClass("selected")){
            jQuery(".blocco8 #block82 .block_uomo .stile .value").removeClass("selected");
            jQuery("#stile_uomo").val("");
        }
        else {
            jQuery(".blocco8 #block82 .block_uomo .stile .value").removeClass("selected");
            valore = jQuery(this).html();
            valore = valore.toLowerCase();
            jQuery("#stile_uomo").val(valore);
            jQuery(this).addClass("selected");
        }
    });

    jQuery(".blocco8 #block82 .block_uomo .occasione .value").click(function () {
        if (jQuery(this).hasClass("selected")){
            jQuery(".blocco8 #block82 .block_uomo .occasione .value").removeClass("selected");
            jQuery("#occasione_uomo").val("");
        }
        else {
            jQuery(".blocco8 #block82 .block_uomo .occasione .value").removeClass("selected");
            valore = jQuery(this).html();
            valore = valore.toLowerCase();
            if (valore == "free time") {
                valore = "tempo-libero";
            }
            jQuery("#occasione_uomo").val(valore);
            jQuery(this).addClass("selected");
        }
    });


    jQuery(".blocco8 #block83 .block_donna .stile .value").click(function () {
        if (jQuery(this).hasClass("selected")){
            jQuery(".blocco8 #block83 .block_donna .stile .value").removeClass("selected");
            jQuery("#stile_donna").val("");
        }
        else {
            jQuery(".blocco8 #block83 .block_donna .stile .value").removeClass("selected");
            valore = jQuery(this).html();
            valore = valore.toLowerCase();
            jQuery("#stile_donna").val(valore);
            jQuery(this).addClass("selected");
        }
    });


    jQuery(".blocco8 #block83 .block_donna .occasione .value").click(function () {
        if (jQuery(this).hasClass("selected")){
            jQuery(".blocco8 #block83 .block_donna .occasione .value").removeClass("selected");
            jQuery("#occasione_donna").val("");
        }
        else {
            jQuery(".blocco8 #block83 .block_donna .occasione .value").removeClass("selected");
            valore = jQuery(this).html();
            valore = valore.toLowerCase();
            if (valore == "free time") {
                valore = "tempo-libero";
            }
            else if (valore == "office wear") {
                valore = "officewear";
            }
            jQuery("#occasione_donna").val(valore);
            jQuery(this).addClass("selected");
        }
    });


    jQuery(".blocco8 #block82 figure").click(function () {
        if (jQuery(".blocco8 #block83 .block_donna").is(":visible")) {
            jQuery(".blocco8 #block83 .block_donna").fadeOut("slow");
            setTimeout(function () {
                jQuery(".blocco8 #block83 figure").fadeIn("slow");
            }, 700);
        }
        else {
            jQuery(".blocco8 #block83 figure").fadeOut("slow");
            setTimeout(function () {
                jQuery(".blocco8 #block83 .block_donna").fadeIn("slow");
            }, 700);
        }
    });


    jQuery(".blocco8 #block83 figure").click(function () {

        if (jQuery(".blocco8 #block82 .block_uomo").is(":visible")) {
            jQuery(".blocco8 #block82 .block_uomo").fadeOut("slow");
            setTimeout(function () {
                jQuery(".blocco8 #block82 figure").fadeIn("slow");
            }, 700);
        }
        else {
            jQuery(".blocco8 #block82 figure").fadeOut("slow");
            setTimeout(function () {
                jQuery(".blocco8 #block82 .block_uomo").fadeIn("slow");
            }, 700);
        }
    });



    jQuery(".blocco8 #block82 .close").click(function () {
        jQuery(".blocco8 #block82 .block_uomo").fadeOut("slow");
        setTimeout(function () {
            jQuery(".blocco8 #block82 figure").fadeIn("slow");
        }, 700);
    });


    jQuery(".blocco8 #block83 .close").click(function () {
        jQuery(".blocco8 #block83 .block_donna").fadeOut("slow");
        setTimeout(function () {
            jQuery(".blocco8 #block83 figure").fadeIn("slow");
        }, 700);
    });


    jQuery(document).on("mouseenter",".postImage",function(){
        jQuery(this).parent().find(".postContentWrapper .postTitle h2 span").css('background-color','#C6DDED');
        jQuery(this).parent().find(".postContentWrapper .postTitle h2 span").css('padding','3px 5px');
     });
    jQuery(document).on("mouseleave",".postImage",function(){
        jQuery(this).parent().find('.postContentWrapper .postTitle h2 span').css('background-color','#FFF');
        jQuery(this).parent().find(".postContentWrapper .postTitle h2 span").css('padding','3px 5px');
     });

    jQuery(document).on("mouseenter",".effect-bubba",function(){
        jQuery(this).parent().find(".testo span").css('background-color','#C6DDED');
        jQuery(this).parent().find(".testo span").css('padding','3px 5px');
    });
    jQuery(document).on("mouseleave",".effect-bubba",function(){
        jQuery(this).parent().find('.testo span').css('background-color','#FFF');
        jQuery(this).parent().find(".testo span").css('padding','3px 5px');
    });


});


function iedetect(v) {

    var r = RegExp('msie' + (!isNaN(v) ? ('\\s' + v) : ''), 'i');
    return r.test(navigator.userAgent);

}

function GetCookie(name) {
    var arg = name + "=";
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;
    while (i < clen) {
        var j = i + alen;
        if (document.cookie.substring(i, j) == arg)
            return "here";
        i = document.cookie.indexOf(" ", i) + 1;
        if (i == 0) break;
    }
    return null;
}


function controllaUomo(){
    flag=true;
    errore="";
    stile=jQuery("#stile_uomo").val();
    occasione=jQuery("#occasione_uomo").val();
    if (stile==""){
        errore+="Non hai selezionato lo stile!\n";
        flag=false;
    }
    if (occasione==""){
        errore+="Non hai selezionato l'occasione d'uso!\n";
        flag=false;
    }

    if (flag==false){
        alert(errore);
    }


    return flag;
}

function controllaDonna(){
    flag=true;
    errore="";
    stile=jQuery("#stile_donna").val();
    occasione=jQuery("#occasione_donna").val();
    if (stile==""){
        errore+="Non hai selezionato lo stile!\n";
        flag=false;
    }
    if (occasione==""){
        errore+="Non hai selezionato l'occasione d'uso!\n";
        flag=false;
    }

    if (flag==false){
        alert(errore);
    }


    return flag;
}

