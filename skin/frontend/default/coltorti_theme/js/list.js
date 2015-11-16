jQuery(window).load(function() {
    larghezza=jQuery(".pages ol").width();
    larghezza=larghezza+2;
    jQuery(".pages ol").css("float","none");
    jQuery(".pages ol").css("width",larghezza+"px");
});

jQuery(document).ready(function() {
    jQuery(document).on("mouseenter",".products-grid li",function(){
        jQuery(this).find(".product-name span").css('background-color','#C6DDED');
        jQuery(this).find(".product-name span").css('padding-left','5px');
        jQuery(this).find(".product-name span").css('padding-right','5px');
        jQuery(this).find(".product-name span").css('padding-top','3px');
        jQuery(this).find(".product-name span").css('padding-bottom','3px');
        jQuery(this).find('.divTaglia').css('visibility','visible');
    });
    jQuery(document).on("mouseleave",".products-grid li",function(){
        jQuery(this).find('.divTaglia').css('visibility','hidden');
        jQuery(this).find('.product-name span').css('background-color','#FFF');
        jQuery(this).find(".product-name span").css('padding','0px');
    });

    jQuery(document).on("mouseenter",".blocco_categoria .block-content ul li",function(){
        jQuery(this).find("span").css('background-color','#C6DDED');
    });
    jQuery(document).on("mouseleave",".blocco_categoria .block-content ul li",function(){
        jQuery(this).find('span').css('background-color','#FFF');
    });
    jQuery(document).on("mouseenter",".list_navigation li",function(){
        jQuery(this).find("span").css('background-color','#C6DDED');
    });
    jQuery(document).on("mouseleave",".list_navigation li",function(){
        jQuery(this).find('span').css('background-color','#FFF');
    });

    jQuery( ".filter-title" ).click(function() {
        if (jQuery( ".filter-title").hasClass("active")){
            jQuery( ".filter-title").removeClass("active");
            jQuery( ".filter-title.filter-status").html("+");
            jQuery( ".sectionContainer").css("visibility","hidden");
        }
        else {
            jQuery( ".filter-title").addClass("active");
            jQuery( ".filter-title.filter-status").html("-");
            jQuery( ".sectionContainer").css("visibility","visible");
        }
    });

    if (jQuery(window).width() <= 770) {
        jQuery(".clear_catalogo").remove();
    }
});