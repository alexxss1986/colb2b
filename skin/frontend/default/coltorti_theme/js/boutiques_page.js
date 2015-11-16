jQuery(document).ready(function() {
    jQuery(document).on("mouseenter",".div_boutiques_dispari .contenuto",function(){
        jQuery(this).parent().parent().css("background-color","#1e2125");
        /*l1=jQuery(window).width();
         l2=jQuery(this).parent().parent().find(".contenuto").width();
         l3=jQuery(this).parent().parent().find(".div_esterno").width();
         ltot=(l3-l2);
         left=((l1-l2)/2)-ltot;*/
        jQuery(this).parent().parent().find(".immagine").css("display","none");
        jQuery(this).parent().parent().find(".contenuto").css("text-align","center");
        /*jQuery(this).parent().parent().find(".contenuto").css("position","relative");
         jQuery(this).parent().parent().find(".contenuto").animate({"left":left+"px"},"slow");*/
    });
    jQuery(document).on("mouseleave",".div_boutiques_dispari .contenuto",function(){
        /*l1=jQuery(window).width();
         l2=jQuery(this).parent().parent().find(".contenuto").width();
         l3=jQuery(this).parent().parent().find(".immagine").width();
         left_base=-((l1-(l3*2))-l2);*/
        jQuery(this).parent().parent().css("background-color","");
        jQuery(this).parent().parent().find(".contenuto").css("text-align","right");
        /*jQuery(this).parent().parent().find(".contenuto").animate({"left":left_base+"px"},1000);*/
        elemento=jQuery(this).parent().parent().find(".immagine");
        elemento.css("display","block");

    });


    jQuery(document).on("mouseenter",".div_boutiques_pari .contenuto",function(){
        jQuery(this).parent().parent().css("background-color","#1e2125");
        /*l1=jQuery(window).width();
         l2=jQuery(this).parent().parent().find(".contenuto").width();
         l3=jQuery(this).parent().parent().find(".div_esterno").width();
         ltot=(l3-l2);
         left=((l1-l2)/2)-ltot;*/
        jQuery(this).parent().parent().find(".immagine").css("visibility","hidden");
        jQuery(this).parent().parent().find(".contenuto").css("text-align","center");
        /*jQuery(this).parent().parent().find(".contenuto").css("position","relative");
         jQuery(this).parent().parent().find(".contenuto").animate({"right":left+"px"},"slow");*/
    });
    jQuery(document).on("mouseleave",".div_boutiques_pari .contenuto",function(){
        /*l1=jQuery(window).width();
         l2=jQuery(this).parent().parent().find(".contenuto").width();
         l3=jQuery(this).parent().parent().find(".immagine").width();
         left_base=-((l1-(l3*2))-l2);*/
        jQuery(this).parent().parent().css("background-color","");
        jQuery(this).parent().parent().find(".contenuto").css("text-align","left");
        /*jQuery(this).parent().parent().find(".contenuto").animate({"right":left_base+"px"},1000);*/
        elemento=jQuery(this).parent().parent().find(".immagine");
        elemento.css("visibility","visible");

    });


    adattaTitolo();
    jQuery(window).resize(function () {
        adattaTitolo();
    });


});

function adattaTitolo() {
    altezza = jQuery(window).height();
    alt = altezza - 350;
    jQuery(".titolo_boutiques").css("height", alt + "px");

    jQuery(".div_boutiques_dispari").each(function(){
        if (jQuery(this).attr("id")!="sbn") {
            l1 = jQuery(window).width();
            l2 = jQuery(this).find(".contenuto").width();
            l3 = jQuery(this).find(".immagine").width();
            left_base = -((l1 - (l3 * 2)) - l2);
            jQuery(this).find(".contenuto").css("left", left_base + "px");
        }
    });


    jQuery(".div_boutiques_pari").each(function(){
        if (jQuery(this).attr("id")!="sbn") {
            l1 = jQuery(window).width();
            l2 = jQuery(this).find(".contenuto").width();
            l3 = jQuery(this).find(".immagine").width();
            left_base = -((l1 - (l3 * 2)) - l2);
            jQuery(this).find(".contenuto").css("right", left_base + "px");
        }
    });
}