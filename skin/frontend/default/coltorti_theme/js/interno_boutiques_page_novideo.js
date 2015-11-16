jQuery(window).load(function () {
    adatta();
    jQuery(window).resize(function () {
        adatta();
    });
});

function adatta() {
    // blocco iniziale
    altezzaWindows=jQuery(window).height();
    altezzaBlocco=altezzaWindows-200;
    larghezzaWindows=jQuery(window).width();
    larghezzaBlocco=larghezzaWindows-120;
    jQuery("#container").css("height", altezzaBlocco+"px");
    jQuery("#container").css("width", larghezzaBlocco+"px");

    //blocco contatta la boutique
    altezzaC = jQuery(".contenuto_boutique_interno .blocco1 .immagine img").height();
    jQuery(".contenuto_boutique_interno .blocco1 .testo").css("height", altezzaC);
    jQuery(".contenuto_boutique_interno .blocco1 .vuoto").css("height", altezzaC);
    altezzaTesto = jQuery(".contenuto_boutique_interno .blocco1 .testo").height();
    margine=(altezzaTesto-37)/2;
    jQuery(".contenuto_boutique_interno .blocco1 .testo #shop").css("margin-top", margine+"px");

    //blocco immagine
    altezzaC = jQuery(".contenuto_boutique_interno .blocco2 .immagine img").height();
    jQuery(".contenuto_boutique_interno .blocco2 .vuoto").css("height", altezzaC);

    //blocco dati
    altezzaC = jQuery(".contenuto_boutique_interno .blocco3 .immagine img").height();
    jQuery(".contenuto_boutique_interno .blocco3 .testo").css("height", altezzaC);
    jQuery(".contenuto_boutique_interno .blocco3 .vuoto").css("height", altezzaC);
    altezzaTesto=jQuery(".contenuto_boutique_interno .blocco3 .testo .dati").height();
    margine=(altezzaC-altezzaTesto)/2;
    jQuery(".contenuto_boutique_interno .blocco3 .testo .dati").css("margin-top", margine+"px");
    larghezzaTesto=jQuery(".contenuto_boutique_interno .blocco3 .testo .dati").width();
    larghezzaDiv=jQuery(".contenuto_boutique_interno .blocco3 .testo").width();
    margine=(larghezzaDiv-larghezzaTesto)/2;
    jQuery(".contenuto_boutique_interno .blocco3 .testo .dati").css("margin-left", margine+"px");

//blocco vetrina
    altezza1 = jQuery(".contenuto_boutique_interno .blocco6 .block1 .colonna3 img").height();
    altezza2 = jQuery(".contenuto_boutique_interno .blocco6 .block1 .colonna2 .riga2 img").height();
    altezza3 = altezza1-altezza2;
    jQuery(".contenuto_boutique_interno .blocco6 .block1 .colonna2 .riga1").css("height", altezza3+"px");


}