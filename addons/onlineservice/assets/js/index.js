var ws;
var error = false;
var uuid = "";
$(function () {
    setTimeout(function () {
        var message_box_h = $("#message-center").height();
        var people_top_h = $("#people-top").outerHeight();
        $("#people-people").css("height", message_box_h - people_top_h - 30)
    }, 1000);
    get_userlist();
    get_black_list();
    quickReply();
    connect();

    $(".people").delegate("li", "click", function () {
        $(this).siblings("li").removeClass("active");
        $(this).addClass("active");
        $(this).find(".tip").css("display", "none");
        var name = $(this).find(".name").text();
        $(".center").find(".name").text(name);
        uuid = $(this).attr("data-uuid");
        get_message(uuid, GROUP_ID);
        $(".container_s .right .contact").css("display", "block");
        $("#tab-tit").find("li").eq(0).addClass("layui-this").siblings().removeClass("layui-this");
        var id = $("#tab-tit").find("li").eq(0).attr("data-id");
        $("#con").find("#" + id).addClass("layui-show").siblings().removeClass("layui-show");
        get_settings(uuid)
    });

    $("#msg_content").bind("keypress", function (event) {
        if (event.keyCode == "13") {
            if (error) {
                layer.alert("通讯失败,无法发送内容!", {
                    icon: 2
                });
                return false
            }
            if (!uuid) {
                layer.alert("请选择访客发送信息!", {
                    icon: 2
                });
                return false
            }
            var content = $("#msg_content").val();
            if (content == "" || content.length == 0) {
                layer.alert("请输入内容...", {
                    icon: 2
                });
                return false
            }
            var reg = new RegExp("<", "g");
            var msg2 = content.replace(reg, "<");
            var reg2 = new RegExp(">", "g");
            msg2 = msg2.replace(reg2, ">");
            msg2 = msg2.replace(/\r\n/g, "<br>");
            msg2 = msg2.replace(/\n/g, "<br>");
            var message = '{"type":"say_user","uuid":"' + uuid + '","content":"' + msg2 + '","group_id":"' + GROUP_ID + '","admin_id":"' + ADMIN_ID + '","file_name":""}';
            if (ws.readyState == 3) {
                layer.alert("WebSocket服务未启动，无法发送消息！", {
                    icon: 2
                });
                $("#content").val("");
                return false
            }
            ws.send(message);
            $("#msg_content").val("");
            var div = document.getElementById("message_box");
            div.scrollTop = div.scrollHeight;
            error = false;
            event.returnValue = false;
            return false
        }
    });
    $("#addsmile").on("click", function (e) {
        $(".smile").toggleClass("open");
        $(document).one("click", function () {
            $(".smile").toggleClass("open")
        });
        e.stopPropagation();
        return false
    });
    $("#clickUpload").on("click", function (e) {
        document.getElementById("uploadFile").click()
    });
});

function connect() {
    if (isHTTPS) {
        ws = new WebSocket("wss://" + document.domain + "/service")
    } else {
        ws = new WebSocket("ws://" + document.domain + ":" + PORT)
    }
    ws.onopen = onopen;
    ws.onmessage = onmessage;
    ws.onclose = function () {
    };
    ws.onerror = function () {
    }
}

function onopen() {
    var message = '{"type":"admin_login","group_id":"' + GROUP_ID + '","admin_id":"' + ADMIN_ID + '"}';
    if (ws && ws.readyState == 1) {
        ws.send(message)
    } else {
        error = true
    }
}

function grin(tag) {
    var myField;
    tag = " " + tag + " ";
    if (document.getElementById("msg_content") && document.getElementById("msg_content").type == "textarea") {
        myField = document.getElementById("msg_content")
    } else {
        return false
    }
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = tag;
        myField.focus()
    } else {
        if (myField.selectionStart || myField.selectionStart == "0") {
            var startPos = myField.selectionStart;
            var endPos = myField.selectionEnd;
            var cursorPos = endPos;
            myField.value = myField.value.substring(0, startPos) + tag + myField.value.substring(endPos, myField.value.length);
            cursorPos += tag.length;
            myField.focus();
            myField.selectionStart = cursorPos;
            myField.selectionEnd = cursorPos
        } else {
            myField.value += tag;
            myField.focus()
        }
    }
}

function onmessage(e) {
    var data = eval("(" + e.data + ")");
    switch (data["type"]) {
        case "ping":
            ws.send('{"type":"pong"}');
            break;
        case "say_ok":
            sendMsg(data["time"], data["content"], data["content_type"]);
            break;
        case "send_ok":
            payAudio();
            if (uuid) {
                get_message(uuid, GROUP_ID);
                var from_uid = data["from_uid"];
                if (from_uid == uuid) {
                    update_user_active(data["content_type"], data["time"], data["content"])
                } else {
                    var t = $(".container_s .left ul li[data-uuid='" + from_uid + "']");
                    if (t.length == 0) {
                        var htmls = '<li class="person" data-uuid="' + from_uid + '"><img class="head-img" src="' + data["head_img"] + '" alt=""/><span class="name">' + data["nickname"] + "</span>";
                        htmls += '<span class="time">' + data["time"] + "</span>";
                        if (data["content_type"] == 0) {
                            htmls += '<span class="preview">' + data["content"] + "</span>"
                        } else {
                            if (data["content_type"] == 1) {
                                htmls += '<span class="preview"><i class="layui-icon layui-icon-picture" style="font-size: 16px; color: #6c7e8a;"></i> 发来了一张图片 ...</span>'
                            } else {
                                htmls += '<span class="preview"><i class="layui-icon layui-icon-file-b" style="font-size: 16px; color: #6c7e8a;"></i> 发来了一个附件 ...</span>'
                            }
                        }
                        if (data["unread"] > 0) {
                            htmls += '<div class="tip">' + data["unread"] + "</div></li>"
                        } else {
                            htmls += '<div class="tip" style="display: none;"></div></li>'
                        }
                        $(".container_s .left ul li:first-child").before(htmls)
                    } else {
                        t.find(".time").text(data["time"]);
                        if (data["content_type"] == 1) {
                            t.find(".preview").html('<i class="layui-icon layui-icon-picture" style="font-size: 16px; color: #6c7e8a;"></i> 发来了一张图片 ...')
                        } else {
                            if (data["content_type"] == 2) {
                                t.find(".preview").html('<i class="layui-icon layui-icon-file-b" style="font-size: 16px; color: #6c7e8a;"></i> 发来了一个附件 ...')
                            } else {
                                t.find(".preview").html(data["content"])
                            }
                        }
                        t.find(".head-img").removeClass("off").addClass("on");
                        t.find(".tip").text(data["unread"]);
                        t.find(".tip").css("display", "inline-block");
                        $(".container_s .left ul li[data-uuid='" + uuid + "']").addClass("active")
                    }
                }
            } else {
                get_userlist()
            }
            break;
        case "onclose":
            get_userlist(uuid);
            break;
        case "online":
            get_userlist(uuid);
            break;
        case "error":
            layer.alert(data["msg"], {
                icon: 2
            });
            break
    }
}

function get_userlist(uuid) {
    $.ajax({
        url: REQUEST_USER_LIST,
        type: "get",
        data: {
            group_id: GROUP_ID
        },
        dataType: "json",
        success: function (ret) {
            if (ret.code == 0) {
                listUser(ADMIN_ID, GROUP_ID, ret.data, uuid)
            }
        },
        error: function () {
            layer.alert("网络错误，请稍后再试！")
        }
    })
}

function update_user_active(type, time, content) {
    var t = $(".container_s").find(".people").find(".active");
    t.find(".time").text(time);
    if (type == 0) {
        t.find(".preview").html(content)
    } else {
        if (type == 1) {
            t.find(".preview").html('<i class="layui-icon layui-icon-picture" style="font-size: 16px; color: #6c7e8a;"></i> 发来了一张图片 ...')
        } else {
            t.find(".preview").html('<i class="layui-icon layui-icon-file-b" style="font-size: 16px; color: #6c7e8a;"></i> 发来了一个附件 ...')
        }
    }
}

function listUser(admin_id, group_id, data, uuid) {
    $("#people-people").html("");
    var htmls = "";
    for (var i = 0; i < data.length; i++) {
        var off = data[i].status == "off" ? "off" : "on";
        htmls += '<li class="person" data-uuid="' + data[i].uuid + '"><img class="head-img ' + off + '" src="' + data[i].head_img + '" alt=""/><span class="name">' + data[i].nickname + "</span>";
        if (data[i].msg) {
            htmls += '<span class="time">' + data[i].msg.time + "</span>";
            if (data[i].msg.type == 0) {
                var re_html = "";
                if (data[i].msg.source_status == 2) {
                    re_html = "回复："
                }
                htmls += '<span class="preview">' + re_html + data[i].msg.content.text + "</span>"
            } else {
                if (data[i].msg.type == 1) {
                    htmls += '<span class="preview"><i class="layui-icon layui-icon-picture" style="font-size: 16px; color: #6c7e8a;"></i> 发来了一张图片 ...</span>'
                } else {
                    htmls += '<span class="preview"><i class="layui-icon layui-icon-file-b" style="font-size: 16px; color: #6c7e8a;"></i> 发来了一个附件 ...</span>'
                }
            }
        }
        if (data[i].unread > 0) {
            htmls += '<div class="tip">' + data[i].unread + "</div></li>"
        } else {
            htmls += '<div class="tip" style="display: none;"></div></li>'
        }
    }
    $("#people-people").append(htmls);
    if (uuid) {
        $(".container_s .left ul li[data-uuid='" + uuid + "']").addClass("active")
    }
}


function get_black_list() {
    $.ajax({
        url: BLACK_LIST_URL,
        type: "get",
        dataType: "json",
        success: function (ret) {
            if (ret.code == 1) {
                $("#black_list").html("");
                var htmls = "";
                for (var i = 0; i < ret.data.length; i++) {
                    htmls += '<div class="visiter"><img class="am-radius v-avatar" src="' + ret.data[i].head_img + '" width="50px"><span style="position:absolute;left:58px;top:20px;font-size: 14px;">' + ret.data[i].nickname + '</span><button class="btn btn-danger" style="position:absolute;right:10px;top:20px;" onclick="cutBlack(' + "'" + ret.data[i].uuid + "'" + ')">删除</button></div>'
                }
                $("#black_list").append(htmls)
            }
        }
    })
}

function cutBlack(c_uuid) {
    $.ajax({
        url: CHANGE_BLACK_URL,
        type: "get",
        data: {
            uuid: c_uuid,
            is_black: 0
        },
        dataType: "json",
        success: function (ret) {
            get_black_list();
            layer.msg("操作成功！", {
                icon: 1
            })
        },
        error: function () {
            layer.alert("操作失败请重试")
        }
    })
}

function get_settings(uuid) {
    $.ajax({
        url: GET_SETTING_URL,
        type: "get",
        data: {
            uuid: uuid
        },
        dataType: "json",
        success: function (ret) {
            if (ret.code == 1) {
                $("#nickname").val(ret.data.nickname);
                $("#email").val(ret.data.email);
                $("#mobile").val(ret.data.mobile);
                $("#remark").val(ret.data.remark);
                $("#join").text(ret.data.log.joinip);
                var country = ret.data.log.country + "," + ret.data.log.region + "," + ret.data.log.city;
                $("#country").text(country);
                if (ret.data.log.agent) {
                    $("#user_agent").text(ret.data.log.agent.browser + " " + ret.data.log.agent.version)
                } else {
                    $("#user_agent").text(ret.data.log.user_agent.split(" ")[0])
                }
                $("#screen_size").text(ret.data.log.screen_size);
                $("#device_info").text(ret.data.log.device_info)
            }
        },
        error: function () {
            layer.alert("操作失败请重试")
        }
    })
}

function get_message(uuid, group_id) {
    $.ajax({
        url: REQUEST_MSG_LIST,
        type: "get",
        data: {
            uuid: uuid,
            group_id: group_id,
            page: 1,
            admin_id: ADMIN_ID
        },
        dataType: "json",
        success: function (ret) {
            $(".chat").addClass("active-chat");
            insertContent(uuid, group_id, ret.data)
        },
        error: function () {
            layer.alert("操作失败请重试")
        }
    })
}

function insertContent(uuid, group_id, data) {
    var showtime = "";
    var curentdata = new Date();
    var time = curentdata.toLocaleDateString();
    $("#message_box").html("");
    if (uuid != "") {
        data.data.reverse()
    } else {
        data.data = data
    }
    if (data.data.length > 0 && data.total > 10) {
        var htmls = '<div class="downUp"><a data-page="2" id="loading-page">点击加载更多</a></div>'
    } else {
        var htmls = ""
    }
    for (var i = 0; i < data.data.length; i++) {
        if (getdata_puttime) {
            if ((data.data[i].createtime - getdata_puttime) > 120) {
                var myDate = new Date(data.data[i].createtime * 1000);
                var puttime = myDate.toLocaleDateString();
                if (puttime == time) {
                    showtime = myDate.getHours() + ":" + pad(myDate.getMinutes(), 2)
                } else {
                    showtime = myDate.getFullYear() + "-" + (myDate.getMonth() + 1) + "-" + myDate.getDate() + " " + myDate.getHours() + ":" + pad(myDate.getMinutes(), 2)
                }
            } else {
                showtime = ""
            }
        } else {
            var myDate = new Date(data.data[i].createtime * 1000);
            var puttime = myDate.toLocaleDateString();
            if (puttime == time) {
                showtime = myDate.getHours() + ":" + pad(myDate.getMinutes(), 2)
            } else {
                showtime = myDate.getFullYear() + "-" + (myDate.getMonth() + 1) + "-" + myDate.getDate() + " " + myDate.getHours() + ":" + pad(myDate.getMinutes(), 2)
            }
        }
        var getdata_puttime = data.data[i].createtime;
        if (showtime) {
            htmls += '<div class="conversation-start"><span>' + showtime + "</span></div>"
        }
        var self = "me";
        if (data.data[i].source_status == 1) {
            self = "you"
        }
        var content = data.data[i].content.text;
        content = contentUrl(content);
        htmls += '<div class="bubble ' + self + '">' + content + "</div>"
    }
    $("#message_box").append(htmls);
    var div = document.getElementById("message_box");
    div.scrollTop = div.scrollHeight;
    $("#loading-page").on("click", function () {
        var page = $(this).data("page");
        $("#loading-page").css("margin-left", "0").addClass("loading").text("");
        $.ajax({
            url: REQUEST_MSG_LIST,
            type: "get",
            data: {
                page: page,
                uuid: uuid,
                group_id: group_id,
                admin_id: ADMIN_ID
            },
            dataType: "json",
            beforeSend: function () {
            },
            error: function (request) {
            },
            success: function (ret) {
                var list = ret.data;
                $("#loading-page").removeClass("loading").css("margin-left", "-40px").text("点击加载更多");
                var htmls = "";
                list.data.reverse();
                for (var i = 0; i < list.data.length; i++) {
                    if (getdata_puttime) {
                        if ((list.data[i].createtime - getdata_puttime) > 60) {
                            var myDate = new Date(list.data[i].createtime * 1000);
                            var puttime = myDate.toLocaleDateString();
                            if (puttime == time) {
                                showtime = myDate.getHours() + ":" + pad(myDate.getMinutes(), 2)
                            } else {
                                showtime = myDate.getFullYear() + "-" + (myDate.getMonth() + 1) + "-" + myDate.getDate() + " " + myDate.getHours() + ":" + pad(myDate.getMinutes(), 2)
                            }
                        } else {
                            showtime = ""
                        }
                    } else {
                        var myDate = new Date(list.data[i].createtime * 1000);
                        var puttime = myDate.toLocaleDateString();
                        if (puttime == time) {
                            showtime = myDate.getHours() + ":" + pad(myDate.getMinutes(), 2)
                        } else {
                            showtime = myDate.getFullYear() + "-" + (myDate.getMonth() + 1) + "-" + myDate.getDate() + " " + myDate.getHours() + ":" + pad(myDate.getMinutes(), 2)
                        }
                    }
                    var getdata_puttime = list.data[i].createtime;
                    if (showtime) {
                        htmls += '<div class="conversation-start"><span>' + showtime + "</span></div>"
                    }
                    var self = "me";
                    if (list.data[i].source_status == 1) {
                        self = "you"
                    }
                    var content = list.data[i].content.text;
                    content = contentUrl(content);
                    htmls += '<div class="bubble ' + self + '">' + content + "</div>"
                }
                $(".downUp").after(htmls);
                if (list.data.length > 0 && list.data.length == list.per_page) {
                    $("#loading-page").data("page", list.current_page + 1)
                } else {
                    $(".downUp").hide()
                }
            }
        })
    });
    setTimeout(function () {
        var div = document.getElementById("message_box");
        div.scrollTop = div.scrollHeight
    }, 500)
}

function sendMsg(time, content, content_type) {
    var htmls = "";
    if (time) {
        htmls = '<div class="conversation-start"><span>' + time + "</span></div>"
    }
    htmls += '<div class="bubble me"><span>' + contentUrl(content) + "</span></div>";
    $("#message_box").append(htmls);
    $(".container_s").find(".people").find(".active").find(".time").text(time);
    if (content_type == 0) {
        content = "回复：" + content
    } else {
        if (content_type == 1) {
            content = '<i class="layui-icon layui-icon-picture" style="font-size: 16px; color: #6c7e8a;"></i> 回复了一张图片 ...'
        } else {
            content = '<i class="layui-icon layui-icon-file-b" style="font-size: 16px; color: #6c7e8a;"></i> 回复了一个附件 ...'
        }
    }
    $(".container_s").find(".people").find(".active").find(".preview").html(content);
    setTimeout(function () {
        var div = document.getElementById("message_box");
        div.scrollTop = div.scrollHeight
    }, 500)
}

$(".send").on("click", function () {
    if (error) {
        layer.alert("通讯失败,无法发送内容!", {
            icon: 2
        });
        return false
    }
    if (!uuid) {
        layer.alert("请选择访客发送信息!", {
            icon: 2
        });
        return false
    }
    var content = $("#msg_content").val();
    if (content == "" || content.length == 0) {
        layer.alert("请输入内容...", {
            icon: 2
        });
        return false
    }
    var reg = new RegExp("<", "g");
    var msg2 = content.replace(reg, "<");
    var reg2 = new RegExp(">", "g");
    msg2 = msg2.replace(reg2, ">");
    msg2 = msg2.replace(/\r\n/g, "<br>");
    msg2 = msg2.replace(/\n/g, "<br>");
    var message = '{"type":"say_user","uuid":"' + uuid + '","content":"' + msg2 + '","group_id":"' + GROUP_ID + '","admin_id":"' + ADMIN_ID + '","file_name":""}';
    if (ws.readyState == 3) {
        layer.alert("WebSocket服务未启动，无法发送消息！", {
            icon: 2
        });
        $("#content").val("");
        return false
    }
    ws.send(message);
    $("#msg_content").val("");
    var div = document.getElementById("message_box");
    div.scrollTop = div.scrollHeight;
    error = false
});


function handleFiles(files) {
    if (!uuid) {
        layer.alert("请选择访客发送信息!", {
            icon: 2
        });
        return false
    }
    if (files.length === 0) {
        layer.alert("没选择文件!", {
            icon: 2
        });
        return false
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
        contentType: false,
        success: function (data) {
            if (data.code == 0) {
                var message = '{"type":"say_user","uuid":"' + uuid + '","content":"' + data.url + '","group_id":"' + GROUP_ID + '","admin_id":"' + ADMIN_ID + '","file_name":"' + data.file_name + '"}';
                if (ws.readyState == 3) {
                    layer.alert("WebSocket服务未启动，无法发送消息！", {
                        icon: 2
                    });
                    $("#content").val("");
                    return false
                }
                ws.send(message);
                $("#msg_content").val("");
                var div = document.getElementById("message_box");
                div.scrollTop = div.scrollHeight
            } else {
                layer.alert(data.msg, {
                    icon: 2
                });
                return false
            }
        },
        error: function () {
        }
    })
}

$(function () {
    $("#msg_content").pasteUploadImage({
        ajaxUrl: UPLOAD_FILE,
        sucCall: function (resp) {
            resp = resp || {};
            $("#msg_content").val("正在上传中...");
            if (resp.code == 1) {
                $("#msg_content").val("")
            }
            if (resp.code == 0) {
                var message = '{"type":"say_user","uuid":"' + uuid + '","content":"' + resp.url + '","group_id":"' + GROUP_ID + '","admin_id":"' + ADMIN_ID + '","file_name":"' + resp.file_name + '"}';
                if (ws.readyState == 3) {
                    layer.alert("WebSocket服务未启动，无法发送消息！", {
                        icon: 2
                    });
                    $("#content").val("");
                    return false
                }
                ws.send(message);
                $("#msg_content").val("");
                var div = document.getElementById("message_box");
                div.scrollTop = div.scrollHeight
            }
        },
        errCall: function (resp) {
        }
    });
    $("#join-blak").on("click", function () {
        if (!uuid) {
            layer.alert("请先选择访客!", {
                icon: 2
            });
            return false
        }
        layer.confirm("确定要将该访客加入黑名单？", {
            btn: ["确定", "取消"]
        }, function () {
            $.ajax({
                url: CHANGE_BLACK_URL,
                type: "get",
                data: {
                    uuid: uuid
                },
                dataType: "json",
                success: function (ret) {
                    layer.msg("操作成功！", {
                        icon: 1
                    });
                    $(".container_s .left .people .active").remove();
                    $(".container_s .center .top .name").text("");
                    $("#message_box").html("");
                    get_black_list()
                },
                error: function () {
                    layer.alert("操作失败请重试")
                }
            })
        }, function () {
        })
    })
});

function show(obj) {
    $(obj).find("i").removeClass("mysize")
}

function hide(obj) {
    $(obj).find("i").addClass("mysize")
}

function quickReply() {
    $.ajax({
        url: QUICK_REPLY_URL,
        type: "post",
        success: function (res) {
            if (res.code == 1) {
                $("#quit_reply").empty();
                var str = "";
                $.each(res.data, function (k, v) {
                    var tag = v.tag_name;
                    if (tag.length > 11) {
                        tag = tag.substring(0, 11) + "..."
                    }
                    str += '<div  id="reply' + v.id + '" onmouseover="show(this)" onmouseout="hide(this)">';
                    str += '<a href="javascript:showon(' + "'" + v.tag_word + "'" + ')">' + tag + "</a>";
                    str += '<a href="javascript:close(' + v.id + ')"><i style="margin-left:5px;" class="layui-icon mysize">ဆ</i></a></div>'
                });
                str += '<div onclick="addreply()" ><i class="layui-icon"></i></div>';
                $("#quit_reply").prepend(str)
            }
        }
    })
}

function addreply() {
    var html = '<form class="layui-form" style="margin-top:30px;">';
    html += '<div class="layui-form-item"><label class="layui-form-label">标签：</label>';
    html += '<div class="layui-input-block"><input id="tag_name" maxlength="30" type="text" class="layui-input" style="width:260px" /></div></div>';
    html += '<div class="layui-form-item layui-form-text"><label class="layui-form-label">内容：</label>';
    html += '<div class="layui-input-block"><textarea id="tag_word" name="content" class="layui-textarea" style="width:260px;resize: none;" ></textarea></div></div>';
    html += "</form>";
    layer.open({
        type: 1,
        title: "添加快捷回复语句",
        area: ["400px", "300px"],
        content: html,
        btn: ["确定", "取消"],
        yes: function (res) {
            if ($("#tag_word").val() == "" || $("#tag_name").val() == "") {
                layer.msg("请输入内容");
                return false
            }
            $.ajax({
                url: ADD_REPLY_URL,
                type: "post",
                data: {
                    tag_word: $("#tag_word").val(),
                    tag_name: $("#tag_name").val(),
                    admin_id: ADMIN_ID,
                    group_id: GROUP_ID
                },
                success: function (res) {
                    if (res.code == 1) {
                        layer.msg(res.msg, {
                            icon: 1,
                            time: 2000,
                            end: function () {
                                var tag = $("#tag_name").val();
                                if (tag.length > 11) {
                                    tag = tag.substring(0, 11) + "..."
                                }
                                var str = '<div id="reply' + res.data.id + '" onmouseover="show(this)" onmouseout="hide(this)">';
                                str += '<a class="tag" href="javascript:showon(' + "'" + res.data.tag_word + "'" + ')">' + tag + "</a>";
                                str += '<a href="javascript:close(' + res.data.id + ')"><i style="margin-left:5px;" class="layui-icon mysize">ဆ</i></a></div>';
                                $("#quit_reply").prepend(str);
                                layer.closeAll()
                            }
                        })
                    } else {
                        layer.msg(res.msg)
                    }
                }
            })
        }
    })
}

function showon(str) {
    if (uuid) {
        $("#msg_content").val(str)
    }
}

function close(id) {
    $.ajax({
        url: DEl_REPLY_URL,
        type: "post",
        data: {
            id: id
        },
        success: function (res) {
            if (res.code == 1) {
                layer.msg(res.msg, {
                    icon: 1,
                    end: function () {
                        $("#reply" + id).remove()
                    }
                })
            }
        }
    })
};