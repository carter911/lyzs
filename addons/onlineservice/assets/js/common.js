function getimg(obj) {
    var text = $(obj).attr('src');
    var img = new Image();
    img.src = $(obj).attr('src');
    var nWidth = img.width;
    var nHeight = img.height;
    var rate = nWidth / nHeight;
    var maxwidth = window.innerWidth;
    var maxheight = window.innerHeight;
    var size;
    var widths, heights;
    if (nWidth < maxwidth && nHeight < maxheight) {
        widths = nWidth - 30;
        heights = nHeight - 30;
    }
    else //原图片宽高比例 大于 图片框宽高比例,则以框的宽为标准缩放，反之以框的高为标准缩放
    {
        if (maxwidth / maxheight <= nWidth / nHeight) //原图片宽高比例 大于 图片框宽高比例
        {
            widths = maxwidth - 30;   //以框的宽度为标准
            heights = maxwidth * (nHeight / nWidth) - 30;
        }
        else {   //原图片宽高比例 小于 图片框宽高比例
            widths = maxheight * (nWidth / nHeight) - 30;
            heights = maxheight - 30;   //以框的高度为标准
        }
    }
    size = [widths + 'px', heights + 'px'];
    layer.open({
        type: 1,
        title: false,
        closeBtn: 1,
        area: size,
        skin: 'layui-layer-nobg', //没有背景色
        shadeClose: true,
        content: "<img src='" + text + "' style='width:100%;height:100%;'>"
    });
}

function contentUrl(content) {
    content = content.replace(/^[a-z]+[.]{1}[a-z\d\-]+[.]{1}[a-z\d]*[\/]*[A-Za-z\d]*[\/]*[A-Za-z\d]*[\/]*[A-Za-z\d]*[\/]*[A-Za-z\d]/g, function (i) {
        return 'http://' + i;
    });
    content = content.replace(/^(https?|http|ftp|file):\/\/[-A-Za-z0-9+&@#/%?=~_|!:,.;]+[-A-Za-z0-9+&@#/%=~_|]/g, function (i) {
        var a = i.replace('http://', '');
        return '<a href="' + i + '" target="_blank">' + a + '</a>';
    });
    return content;
}

function pad(num, n) {
    var len = num.toString().length;
    while (len < n) {
        num = "0" + num;
        len++;
    }
    return num;
}

function payAudio() {
    //播放声音的动作
    audioElementHovertree = document.createElement('audio');
    audioElementHovertree.setAttribute('src', "/assets/addons/onlineservice/voice/default.mp3");
    audioElementHovertree.setAttribute('autoplay', 'autoplay');
}


function grin(tag) {
    var myField;
    tag = ' ' + tag + ' ';
    if (document.getElementById('content') && document.getElementById('content').type == 'text') {
        myField = document.getElementById('content');
    } else {
        return false;
    }
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = tag;
        myField.focus();
    }
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        var cursorPos = endPos;
        myField.value = myField.value.substring(0, startPos)
            + tag
            + myField.value.substring(endPos, myField.value.length);
        cursorPos += tag.length;
        myField.focus();
        myField.selectionStart = cursorPos;
        myField.selectionEnd = cursorPos;
    }
    else {
        myField.value += tag;
        myField.focus();
    }
}


function detectOS() {
    var sUserAgent = navigator.userAgent;
    var isWin = (navigator.platform == "Win32") || (navigator.platform == "Windows");
    var isMac = (navigator.platform == "Mac68K") || (navigator.platform == "MacPPC") || (navigator.platform == "Macintosh") || (navigator.platform == "MacIntel");
    if (isMac) return "Mac";
    var isUnix = (navigator.platform == "X11") && !isWin && !isMac;
    if (isUnix) return "Unix";
    var isLinux = (String(navigator.platform).indexOf("Linux") > -1);
    if (isLinux) return "Linux";
    if (isWin) {
        var isWin2K = sUserAgent.indexOf("Windows NT 5.0") > -1 || sUserAgent.indexOf("Windows 2000") > -1;
        if (isWin2K) return "Win2000";
        var isWinXP = sUserAgent.indexOf("Windows NT 5.1") > -1 || sUserAgent.indexOf("Windows XP") > -1;
        if (isWinXP) return "WinXP";
        var isWin2003 = sUserAgent.indexOf("Windows NT 5.2") > -1 || sUserAgent.indexOf("Windows 2003") > -1;
        if (isWin2003) return "Win2003";
        var isWin2003 = sUserAgent.indexOf("Windows NT 6.0") > -1 || sUserAgent.indexOf("Windows Vista") > -1;
        if (isWin2003) return "WinVista";
        var isWin2003 = sUserAgent.indexOf("Windows NT 6.1") > -1 || sUserAgent.indexOf("Windows 7") > -1;
        if (isWin2003) return "Win7";
    }
    return "None";
}