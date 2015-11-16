jQuery(document).ready(function() {
    jQuery(document).on("mouseenter",".div_designer_dispari .contenuto",function(){
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
    jQuery(document).on("mouseleave",".div_designer_dispari .contenuto",function(){
        jQuery(this).parent().parent().css("background-color","");
        jQuery(this).parent().parent().find(".contenuto").css("text-align","left");
        /*jQuery(this).parent().parent().find(".contenuto").animate({"left":"0"},1000);*/
        elemento=jQuery(this).parent().parent().find(".immagine");
        elemento.css("display","block");

    });


    jQuery(document).on("mouseenter",".div_designer_pari .contenuto",function(){
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
    jQuery(document).on("mouseleave",".div_designer_pari .contenuto",function(){
        jQuery(this).parent().parent().css("background-color","");
        jQuery(this).parent().parent().find(".contenuto").css("text-align","right");
        /*jQuery(this).parent().parent().find(".contenuto").animate({"right":"0"},1000);*/
        elemento=jQuery(this).parent().parent().find(".immagine");
        elemento.css("visibility","visible");

    });

    jQuery(document).ready(function() {
        adattaTitolo();
        jQuery(window).resize(function () {
            adattaTitolo();
        });
    });

    function adattaTitolo() {
        altezza = jQuery(window).height();
        alt = altezza - 125;
        jQuery(".titolo_designer").css("height", alt + "px");
    }

    jQuery('.button_secondary').click(function() {
        var targetOffset = jQuery("#adidas-raf-simons").offset().top;
        jQuery('html,body').animate({scrollTop: targetOffset}, 1000);
    });
});