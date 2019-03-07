var div = document.getElementById('message-box');
div.scrollTop = div.scrollHeight;
var ws; //定义全局变量ws
var error = false;
$(function () {
    $("#online_ervice").on('click', function () {
        connect();
        localStorage.setItem("ichat_pop", "pop");
        get_message(GUUID, GROUP_ID);
        $("#container").css('display', 'block');
        $(".layui-fixbar").css('display', 'none');
        div.scrollTop = div.scrollHeight;
    });

    $("#ichat_close").on('click', function () {
        localStorage.removeItem('ichat_pop');
        $("#container").css('display', 'none');
        $(".layui-fixbar").css('display', 'block');
        closeichat_pop();
    });

    var ichat_pop = localStorage.getItem('ichat_pop');
    if (ichat_pop) {
        connect();
        get_message(GUUID, GROUP_ID);
        $("#container").css('display', 'block');
        $(".layui-fixbar").css('display', 'none');
        div.scrollTop = div.scrollHeight;
    } else {
        $("#container").css('display', 'none');
        $(".layui-fixbar").css('display', 'block');
    }

    setTimeout(function () {
        var div = document.getElementById('message-box');
        div.scrollTop = div.scrollHeight;
    }, 1000);

    $('#addsmile').on("click", function (e) {
        $('.smile').toggleClass('open');
        $(document).one("click", function () {
            $('.smile').toggleClass('open');
        });
        e.stopPropagation();
        return false;
    });

    $('.send').on('click', function (e) {
        if (error) {
            layer.alert('通讯失败,无法发送内容!', {
                icon: 2
            });
            return false;
        }
        var content = $("#content").val();
        if (content == '' || content.length == 0) {
            layer.alert('请输入内容...', {
                icon: 2
            });
            return false;
        }
        var reg = new RegExp('<', "g");
        var msg2 = content.replace(reg, '&lt;');
        var reg2 = new RegExp('>', "g");
        msg2 = msg2.replace(reg2, '&gt;');
        msg2 = msg2.replace(/\r\n/g, "<br>");
        msg2 = msg2.replace(/\n/g, "<br>");
        //var admin_id = localStorage.getItem('online_service_admin_id');
        var message = '{"type":"say","secret_key":"' + SECRET_KEY + '","uuid":"' + GUUID + '","content":"' + msg2 + '","group_id":"' + GROUP_ID + '","user_id":"' + USER_ID + '","admin_id":"' + ADMIN_ID + '","file_name":""}';
        if (ws.readyState == 3) {
            layer.alert('WebSocket服务未启动，无法发送消息！', {
                icon: 2
            });
            $("#content").val("");
            return false;
        }
        ws.send(message);
        $("#content").val("");
        var div = document.getElementById('message-box');
        div.scrollTop = div.scrollHeight;
        error = false;
    });

    $('#content').bind('keypress', function (event) {
        if (event.keyCode == "13") {
            if (error) {
                layer.alert('通讯失败,无法发送内容!', {
                    icon: 2
                });
                return false;
            }
            var content = $("#content").val();
            if (content == '' || content.length == 0) {
                layer.alert('请输入内容...', {
                    icon: 2
                });
                return false;
            }
            var reg = new RegExp('<', "g");
            var msg2 = content.replace(reg, '&lt;');
            var reg2 = new RegExp('>', "g");
            msg2 = msg2.replace(reg2, '&gt;');
            msg2 = msg2.replace(/\r\n/g, "<br>");
            msg2 = msg2.replace(/\n/g, "<br>");
            //var admin_id = localStorage.getItem('online_service_admin_id');
            var message = '{"type":"say","secret_key":"' + SECRET_KEY + '","uuid":"' + GUUID + '","content":"' + msg2 + '","group_id":"' + GROUP_ID + '","user_id":"' + USER_ID + '","admin_id":"' + ADMIN_ID + '","file_name":""}';
            if (ws.readyState == 3) {
                layer.alert('WebSocket服务未启动，无法发送消息！', {
                    icon: 2
                });
                $("#content").val("");
                return false;
            }
            ws.send(message);
            $("#content").val("");
            var div = document.getElementById('message-box');
            div.scrollTop = div.scrollHeight;
            error = false;
        }
    });

    $('#clickUpload').on('click', function (e) {
        document.getElementById("uploadFile").click();
    });

    // 连接服务端
    function connect() {
        // 创建websocket
        if (isHTTPS) {
            //https协议 使用下面代码
            ws = new WebSocket("wss://" + document.domain + "/service");
        } else {
            //http协议 使用下面代码
            ws = new WebSocket("ws://" + document.domain + ":" + PORT);
        }
        ws.onopen = onopen;
        ws.onmessage = onmessage;
        ws.onclose = function () {
            //connect();
        };
        ws.onerror = function () {
        };
    }

    // 连接建立时
    function onopen() {
        var screen_size = window.screen.width + '*' + window.screen.height;
        var device_info = detectOS();
        // 发送消息
        var message = '{"type":"connect","secret_key":"' + SECRET_KEY + '","uuid":"' + GUUID + '","group_id":"' + GROUP_ID + '","screen_size":"' + screen_size + '","device_info":"' + device_info + '","user_agent":"' + USER_AGENT + '","user_id":"' + USER_ID + '","admin_id":"' + ADMIN_ID + '","join_ip":"' + JOIN_IP + '"}';
        if (ws && ws.readyState == 1) {
            //将本机GUUID缓存作为游客的唯一标示
            localStorage.setItem("cache_uuid", GUUID);
            ws.send(message);
        } else {
            error = true;
        }
    }

    function closeichat_pop() {
        var message = '{"type":"close","uuid":"' + GUUID + '","user_id":"' + USER_ID + '","admin_id":"' + ADMIN_ID + '"}';
        if (ws && ws.readyState == 1) {
            ws.send(message);
        }
    }

    // 服务端发来消息时
    function onmessage(e) {
        var data = eval("(" + e.data + ")");
        switch (data['type']) {
            // 服务端ping客户端
            case 'ping':
                ws.send('{"type":"pong"}');
                break;
            case 'say_ok':
                //发送成功后执行的动作
                sendMsg(data['time'], data['content'], 1);
                break;
            //收到客服上线的管理员id
            case 'airing':
                break;
            case 'unairing':
                break;
            case 'send_ok':
                payAudio();
                sendMsg(data['time'], data['content'], 2);
                break;
            case 'error':
                if (data['code'] == '0') {
                    error = true;
                    localStorage.removeItem('ichat_pop');
                    $(".layui-layer-shade").css('opacity', '0');
                    $("#container").css('display', 'none');
                    $(".layui-fixbar").css('display', 'block');
                }
                layer.alert(data['msg'], {
                    icon: 2
                });
                break;
        }
    }
});

function get_message(uuid, group_id) {
    $.ajax({
        url: REQUEST_MSG_LIST,
        type: 'get',
        data: {
            uuid: uuid,
            group_id: group_id,
            page: 1,
            admin_id: ADMIN_ID
        },
        dataType: 'json',
        success: function (ret) {
            insertContent(uuid, group_id, ret);
        },
        error: function () {
            layer.alert("网络错误，请稍后再试！");
        }
    });
}

//渲染内容

function insertContent(uuid, group_id, data) {
    var speck_nickname = data.msg;
    data = data.data;
    var showtime = "";
    var curentdata = new Date();
    var time = curentdata.toLocaleDateString();
    $("#message-box").html('');
    if (uuid != '' && data.total > 0) {
        data.data.reverse(); //内容反转
    } else {
        data.data = data;
    }
    var htmls = '';
    if (data.data.length > 0 && data.total > 10) {
        htmls = '<div class="downUp"><a data-page="2" id="loading-page">点击加载更多</a></div>';
        $("#speck").css('display', 'unset').text('与 ' + speck_nickname + ' 交流中');
    } else {
        if (data.total == 0) {
            if (data.data.code == 1) {
                //如果是第一次用户，弹窗客户提醒
                htmls = '<div class="service-info"><span>' + data.service_name + ' 为您服务</span></div>';
            } else {
                htmls = '<div class="service-info"><span>' + data.service_name + ' 当前不在线</span></div>';
            }
        } else {
            htmls = '';
            $("#speck").css('display', 'unset').text('与 ' + speck_nickname + ' 交流中');
        }
    }
    for (var i = 0; i < data.data.length; i++) {
        if (getdata_puttime) {
            if ((data.data[i].createtime - getdata_puttime) > 120) {
                var myDate = new Date(data.data[i].createtime * 1000);
                var puttime = myDate.toLocaleDateString();
                if (puttime == time) {
                    showtime = myDate.getHours() + ":" + pad(myDate.getMinutes(), 2);

                } else {
                    showtime = myDate.getFullYear() + "-" + (myDate.getMonth() + 1) + "-" + myDate.getDate() + " " + myDate.getHours() + ":" + pad(myDate.getMinutes(), 2);
                }
            } else {
                showtime = "";
            }
        } else {
            var myDate = new Date(data.data[i].createtime * 1000);
            var puttime = myDate.toLocaleDateString();
            if (puttime == time) {
                showtime = myDate.getHours() + ":" + pad(myDate.getMinutes(), 2);
            } else {
                showtime = myDate.getFullYear() + "-" + (myDate.getMonth() + 1) + "-" + myDate.getDate() + " " + myDate.getHours() + ":" + pad(myDate.getMinutes(), 2);
            }
        }
        var getdata_puttime = data.data[i].createtime;
        if (showtime) {
            htmls += '<div class="conversation-start"><span>' + showtime + '</span></div>';
        }
        var self = 'you';
        if (data.data[i].source_status == 1) {
            self = 'me';
        }
        var content = data.data[i].content.text;
        content = contentUrl(content);
        htmls += '<div class="bubble ' + self + '">' + content + '</div>';
    }
    $("#message-box").append(htmls);
    var div = document.getElementById('message-box');
    div.scrollTop = div.scrollHeight;
    $("#loading-page").on('click', function () {
        var page = $(this).data('page');
        $("#loading-page").css('margin-left', '0').addClass('loading').text('');
        $.ajax({
            url: REQUEST_MSG_LIST,
            type: "get",
            data: {
                page: page,
                uuid: uuid,
                group_id: group_id,
                admin_id: ADMIN_ID
            },
            dataType: 'json',
            beforeSend: function () {
                //$(".loading").append('<div class="loading-box"><div></div><div></div><div></div></div>');
            },
            error: function (request) {
            },
            success: function (ret) {
                var list = ret.data;
                $("#loading-page").removeClass('loading').css('margin-left', '-40px').text('点击加载更多');
                var htmls = '';
                list.data.reverse();
                for (var i = 0; i < list.data.length; i++) {

                    if (getdata_puttime) {
                        if ((list.data[i].createtime - getdata_puttime) > 60) {
                            var myDate = new Date(list.data[i].createtime * 1000);
                            var puttime = myDate.toLocaleDateString();
                            if (puttime == time) {
                                showtime = myDate.getHours() + ":" + pad(myDate.getMinutes(), 2);

                            } else {
                                showtime = myDate.getFullYear() + "-" + (myDate.getMonth() + 1) + "-" + myDate.getDate() + " " + myDate.getHours() + ":" + pad(myDate.getMinutes(), 2);
                            }
                        } else {
                            showtime = "";
                        }
                    } else {
                        var myDate = new Date(list.data[i].createtime * 1000);
                        var puttime = myDate.toLocaleDateString();
                        if (puttime == time) {
                            showtime = myDate.getHours() + ":" + pad(myDate.getMinutes(), 2);
                        } else {
                            showtime = myDate.getFullYear() + "-" + (myDate.getMonth() + 1) + "-" + myDate.getDate() + " " + myDate.getHours() + ":" + pad(myDate.getMinutes(), 2);
                        }
                    }
                    var getdata_puttime = list.data[i].createtime;
                    if (showtime) {
                        htmls += '<div class="conversation-start"><span>' + showtime + '</span></div>';
                    }

                    var self = 'you';
                    if (list.data[i].source_status == 1) {
                        self = 'me';
                    }
                    var content = list.data[i].content.text;
                    content = contentUrl(content);
                    htmls += '<div class="bubble ' + self + '">' + content + '</div>';
                }
                $(".downUp").after(htmls);
                if (list.data.length > 0 && list.data.length == list.per_page) {
                    $("#loading-page").data('page', list.current_page + 1);
                } else {
                    $(".downUp").hide();
                }
            }
        });
    });
}

//来消息时 渲染内容

function sendMsg(time, content, source) {
    var htmls = '';
    if (time) {
        htmls = '<div class="conversation-start"><span>' + time + '</span></div>';
    }
    var self = source == 1 ? 'me' : 'you';
    htmls += '<div class="bubble ' + self + '"><span>' + contentUrl(content) + '</span></div>';
    $("#message-box").append(htmls);
    setTimeout(function () {
        var div = document.getElementById('message-box');
        div.scrollTop = div.scrollHeight;
    }, 500);
}

function handleFiles(files) {
    // 如果文件对象的length属性为0，就是没文件
    if (files.length === 0) {
        layer.alert('没选择文件!', {
            icon: 2
        });
        return false;
    }
    var fileObj = document.getElementById("uploadFile").files[0];
    var formFile = new FormData();
    formFile.append("action", "UploadPath");
    formFile.append("file", fileObj);
    $.ajax({
        url: UPLOAD_FILE,
        type: "POST",
        data: formFile,
        dataType: "json",
        processData: false,
        //用于对data参数进行序列化处理 这里必须false
        contentType: false,
        success: function (data) {
            if (data.code == 0) {
                //var admin_id = localStorage.getItem('online_service_admin_id');
                var message = '{"type":"say","secret_key":"' + SECRET_KEY + '","uuid":"' + GUUID + '","content":"' + data.url + '","group_id":"' + GROUP_ID + '","user_id":"' + USER_ID + '","admin_id":"' + ADMIN_ID + '","file_name":"' + data.file_name + '"}';
                if (ws.readyState == 3) {
                    layer.alert('WebSocket服务未启动，无法发送消息！', {
                        icon: 2
                    });
                    $("#content").val("");
                    return false;
                }
                ws.send(message);
            } else {
                layer.alert(data.msg, {
                    icon: 2
                });
                return false;
            }
            //$("#imgWait").hide();
        },
        error: function () {
            //$("#imgWait").hide();
        }
    });
}