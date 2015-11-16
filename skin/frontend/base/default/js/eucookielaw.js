// Creare's 'Implied Consent' EU Cookie Law Banner v:2.2

function createDiv(){
    var bodytag = document.getElementsByTagName('body')[0];
    var div = document.createElement('div');
    div.setAttribute('id','cookie-law');
    div.innerHTML = '<p>'+privacyMessage+' <a href="'+privacyUrl+'" rel="nofollow" title="'+privacyLinkText+'">'+privacyLinkText+'</a><a href="javascript:void(0)" style="margin-left:30px;" onclick="chiudi()">Chiudi</a></p>';

    appendCSS();

    // bodytag.appendChild(div); // Adds the Cookie Law Banner just before the closing </body> tag
    // or

    bodytag.insertBefore(div,bodytag.firstChild); // Adds the Cookie Law Banner just after the opening <body>


    createCookie(window.cookieName,window.cookieValue, window.cookieDuration); // Create the cookie

    if (getFadeOutTime())
    {
        setTimeout("fadeOut()",getFadeOutTime());
    }
}

function appendCSS(){

    var head = document.getElementsByTagName('head')[0];
    //var css = '';
    var style = document.createElement('style');
    style.type = 'text/css';
    var cssText = css;
    if(style.styleSheet) { // IE does it this way
        style.styleSheet.cssText = cssText;
    } else { // everyone else does it this way
        style.appendChild(document.createTextNode(css));
    }
    head.appendChild(style);
}

function fadeOut()
{
    new Effect.Fade('cookie-law', { duration:1});
}

function getFadeOutTime()
{
    return (fadeOutTime*1000);
}

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    if(window.dropCookie) {
        document.cookie = name+"="+value+expires+"; path=/";
    }
}

function checkCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}

function chiudi(){
    document.getElementById('cookie-law').style.display='none';
}