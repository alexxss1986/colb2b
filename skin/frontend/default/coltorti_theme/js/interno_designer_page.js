jQuery(window).load(function () {
    adatta();
    jQuery(window).resize(function () {
        adatta();
    });
});

function adatta() {
    // blocco iniziale
    altezzaWindows=jQuery(window).height();
    altezzaBlocco=altezzaWindows-125;
    jQuery(".titolo_scopri").css("height", altezzaBlocco+"px");

    // blocco sfilata
    altezzaB = jQuery(".blocco1 .immagine").height();
    jQuery(".blocco1 .testo").css("height", altezzaB);

    larghezza=jQuery("#lista_bou").width();
    larghezza=parseInt(larghezza)+1;
    jQuery("#lista_bou").css("float","none");
    jQuery("#lista_bou").css("width",larghezza+"px");

}