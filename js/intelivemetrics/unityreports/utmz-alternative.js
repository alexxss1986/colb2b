/**
 * This script provides an alternative method for creating an _utmz cookie
 * when switching to universal analytics
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */
var Qs = function () {
    // This function is anonymous, is executed immediately and 
    // the return value is assigned to QueryString!
    var query_string = {};
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        // If first entry with this name
        if (typeof query_string[pair[0]] === "undefined") {
            query_string[pair[0]] = pair[1];
            // If second entry with this name
        } else if (typeof query_string[pair[0]] === "string") {
            var arr = [query_string[pair[0]], pair[1]];
            query_string[pair[0]] = arr;
            // If third or later entry with this name
        } else {
            query_string[pair[0]].push(pair[1]);
        }
    }
    return query_string;
}();

//utm_campaign=app_listing&utm_medium=referral&utm_source=ga_partner_gallery
var Utmza = function (domain) {
    var c = Qs.utm_campaign || null,
            s = Qs.utm_source || null,
            m = Qs.utm_medium || null,
            j = [],
            expires = new Date(),
            cookie = '__utmza'
            ;
    domain = domain || window.location.hostname;
    
    if (c) j.push('c=' + c);
    if (s) j.push('s=' + s);
    if (m) j.push('m=' + m);
    
    if(j.length > 0){
        //create top level cookie
        expires.setTime(expires.getTime() + 1000*60*60*24*365); // (1 years)
        document.cookie = cookie + "=" + j.join('|') + "; expires=" + expires.toGMTString() + "; domain=" + domain + "; path=/";
    }
}();
