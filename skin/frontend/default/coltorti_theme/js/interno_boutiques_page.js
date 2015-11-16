jQuery(document).ready(function() {
    // IE detect
    function iedetect(v) {

        var r = RegExp('msie' + (!isNaN(v) ? ('\\s' + v) : ''), 'i');
        return r.test(navigator.userAgent);

    }

    // For mobile screens, just show an image called 'poster.jpg'. Mobile
    // screens don't support autoplaying videos, or for IE.
    if(screen.width < 800 || iedetect(8) || iedetect(7) || 'ontouchstart' in window) {

        (adjSize = function() { // Create function called adjSize

            $width = jQuery(window).width(); // Width of the screen
            $height = jQuery(window).height(); // Height of the screen

            $height=$height-195;
            // Resize image accordingly
            jQuery('#container').css({
                'background-image' : 'url(poster.jpg)',
                'background-size' : 'cover',
                'width' : $width+'px',
                'height' : $height+'px'
            });

            // Hide video
            jQuery('video').hide();

        })(); // Run instantly

        // Run on resize too
        jQuery(window).resize(adjSize);
    }
    else {

        // Wait until the video meta data has loaded
        jQuery('#video_boutiques').on('loadedmetadata', function() {

            var $width, $height, // Width and height of screen
                $vidwidth = this.videoWidth, // Width of video (actual width)
                $vidheight = this.videoHeight, // Height of video (actual height)
                $aspectRatio = $vidwidth / $vidheight; // The ratio the video's height and width are in

            (adjSize = function() { // Create function called adjSize

                $width = jQuery(window).width(); // Width of the screen
                $height = jQuery(window).height(); // Height of the screen

                $height=$height-195;
                $boxRatio = $width / $height; // The ratio the screen is in

                $adjRatio = $aspectRatio / $boxRatio; // The ratio of the video divided by the screen size

                // Set the container to be the width and height of the screen
                jQuery('#container').css({
                    'width' : $width+'px',
                    'height' : $height+'px',
                    'padding-left' : '60px',
                    'padding-right' : '60px'
                });

                $width=$width-120;
                $altezzaVideo=jQuery("video").height();
                $marginTop=-($altezzaVideo-$height);
                if($boxRatio < $aspectRatio) { // If the screen ratio is less than the aspect ratio..
                    // Set the width of the video to the screen size multiplied by $adjRatio
                    $vid = jQuery('#container video').css({'width' : $width*$adjRatio+'px','margin-top':$marginTop+'px'});
                } else {
                    // Else just set the video to the width of the screen/container
                    $vid = jQuery('#container video').css({'width' : $width+'px','margin-top':$marginTop+'px'});
                }

            })(); // Run function immediately

            // Run function also on window resize.
            jQuery(window).resize(adjSize);

        });
    }


});

jQuery(window).load(function () {
    adatta();
    jQuery(window).resize(function () {
        adatta();
    });
});

function adatta() {
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