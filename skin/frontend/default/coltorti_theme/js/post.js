jQuery(document).on("mouseenter",".blocco1 img",function(){
    jQuery(this).parent().parent().find(".product-name span").css('background-color','#C6DDED');
    jQuery(this).parent().parent().find(".product-name span").css('padding-left','5px');
    jQuery(this).parent().parent().find(".product-name span").css('padding-right','5px');
    jQuery(this).parent().parent().find(".product-name span").css('padding-top','3px');
    jQuery(this).parent().parent().find(".product-name span").css('padding-bottom','3px');

});
jQuery(document).on("mouseleave",".blocco1 img",function(){
    jQuery(this).parent().parent().find('.product-name span').css('background-color','#FFF');
    jQuery(this).parent().parent().find(".product-name span").css('padding','0px');
});




jQuery(window).load(function () {
    adatta();
    jQuery(window).resize(function () {
        adatta();
    });
});

function adatta() {

    altezza = jQuery(".titolo_articolo_interno .immagine").height();
    jQuery(".titolo_articolo_interno .testo").css("height", altezza + "px");

    altezzaTitolo = jQuery(".titolo_articolo_interno .testo h1").height();
    altezzaSottoTitolo = jQuery(".titolo_articolo_interno .testo p").height();
    alt=(altezza-(altezzaTitolo+altezzaSottoTitolo+20))/2
    jQuery(".titolo_articolo_interno .testo h1").css("margin-top", alt + "px");

    //blocco vetrina
    altezza1 = jQuery(".contenuto_articolo_interno .blocco2 .block1 .colonna3 #img_coltorti").height();
    altezza2 = jQuery(".contenuto_articolo_interno .blocco2 .block1 .colonna2 .riga2 #img_coltorti").height();
    altezza3 = altezza1-altezza2;
    jQuery(".contenuto_articolo_interno .blocco2 .block1 .colonna2 .riga1").css("height", altezza3+"px");

    //blocco prodotto
    altezza1 = jQuery(".contenuto_articolo_interno .blocco1 .colonna1 .riga1").height();
    altezza2 = jQuery(".contenuto_articolo_interno .blocco1 .colonna1 .riga1 .product").height();
    margine = (altezza1-altezza2)/2;
    jQuery(".contenuto_articolo_interno .blocco1 .colonna1 .riga1 .product").css("margin-top", margine+"px");


    // blocco iniziale
    /*altezza1 = jQuery(".contenuto_articolo_interno .blocco1 .block1 .colonna2").height();
     altezza2 = jQuery(".contenuto_articolo_interno .blocco1 .block1 .colonna1 .riga2 img").height();
     alt=altezza1-altezza2-100;
     jQuery(".contenuto_articolo_interno .blocco1 .block1 .colonna1 .riga1").css("height",alt+"px");
     imgAlt=alt-95;
     jQuery(".contenuto_articolo_interno .blocco1 .block1 .colonna1 .riga1 img").css("height",imgAlt+"px");

     altezza2 = jQuery(".contenuto_articolo_interno .blocco1 .block1 .colonna3 .riga2 img").height();
     alt=altezza1-altezza2-100;
     jQuery(".contenuto_articolo_interno .blocco1 .block1 .colonna3 .riga1").css("height",alt+"px");
     imgAlt=alt-95;
     jQuery(".contenuto_articolo_interno .blocco1 .block1 .colonna3 .riga1 img").css("height",imgAlt+"px");*/


}