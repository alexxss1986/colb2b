window.onload = function () {

    view_direct_links();
    view_hide_price();

    $('forbidden_action').addEventListener(
        'change',
        function () {
            view_cms_pages();
        },
        false
    );

    $('allow_direct_links').addEventListener(
        'change',
        function () {
            view_direct_links();
        },
        false
    );

    $('hide_price').addEventListener(
        'change',
        function () {
            view_hide_price();
        },
        false
    );

};

function view_cms_pages() {
    box = $('forbidden_action');
    var sel_text = box.selectedIndex >= 0 ? box.options[box.selectedIndex].innerHTML : undefined;
    if ('Redirect to CMS page' != sel_text) {
        $('cms_page').up(1).hide();
    } else {
        $('cms_page').up(1).show();
    }
}

function view_direct_links() {
    var sel_text = parseInt($('allow_direct_links').value);
    if (sel_text == 0) {
        $('forbidden_action').up(1).show();
        view_cms_pages();
    }
    else {
        $('forbidden_action').up(1).hide();
        $('cms_page').up(1).hide();
    }

}
function view_hide_price() {
    var sel_text = parseInt($('hide_price').value);
    if (sel_text == 1) {
        $('price_on_product_view').up(1).show();
        $('price_on_product_list').up(1).show();
    }
    else {
        $('price_on_product_view').up(1).hide();
        $('price_on_product_list').up(1).hide();
    }

}